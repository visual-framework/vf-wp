<?php
/**
 * PDF attachment indexing service.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_PDF_Indexer {
	/**
	 * @var VFWP_Intranet_Search_Index_Repository
	 */
	private $repository;

	/**
	 * @var VFWP_Intranet_Search_Normalizer
	 */
	private $normalizer;

	/**
	 * @var VFWP_Intranet_Search_PDF_Extractor
	 */
	private $extractor;

	/**
	 * Recursion guard by attachment ID.
	 *
	 * @var array
	 */
	private $indexing = array();

	/**
	 * @param VFWP_Intranet_Search_Index_Repository $repository Repository.
	 * @param VFWP_Intranet_Search_Normalizer       $normalizer Normalizer.
	 * @param VFWP_Intranet_Search_PDF_Extractor    $extractor PDF extractor.
	 */
	public function __construct(VFWP_Intranet_Search_Index_Repository $repository, VFWP_Intranet_Search_Normalizer $normalizer, VFWP_Intranet_Search_PDF_Extractor $extractor) {
		$this->repository = $repository;
		$this->normalizer = $normalizer;
		$this->extractor = $extractor;
	}

	/**
	 * Register attachment lifecycle hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action('add_attachment', array($this, 'handle_attachment_saved'), 20);
		add_action('edit_attachment', array($this, 'handle_attachment_saved'), 20);
		add_action('attachment_updated', array($this, 'handle_attachment_updated'), 20, 3);
		add_filter('wp_update_attachment_metadata', array($this, 'handle_attachment_metadata_updated'), 20, 2);
		add_action('added_post_meta', array($this, 'handle_attachment_meta_changed'), 20, 4);
		add_action('updated_post_meta', array($this, 'handle_attachment_meta_changed'), 20, 4);
		add_action('trashed_post', array($this, 'handle_attachment_removed'));
		add_action('untrashed_post', array($this, 'handle_attachment_saved'));
		add_action('delete_attachment', array($this, 'handle_attachment_removed'));
	}

	/**
	 * Index an attachment after save.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return void
	 */
	public function handle_attachment_saved($attachment_id) {
		$this->index_attachment((int) $attachment_id);
	}

	/**
	 * Index an attachment after an update hook.
	 *
	 * @param int     $attachment_id Attachment ID.
	 * @param WP_Post $after Updated attachment.
	 * @param WP_Post $before Previous attachment.
	 * @return void
	 */
	public function handle_attachment_updated($attachment_id, $after, $before) {
		$this->index_attachment((int) $attachment_id);
	}

	/**
	 * Index after attachment metadata is generated or updated.
	 *
	 * @param array|false $metadata Attachment metadata.
	 * @param int         $attachment_id Attachment ID.
	 * @return array|false
	 */
	public function handle_attachment_metadata_updated($metadata, $attachment_id) {
		$this->index_attachment((int) $attachment_id);

		return $metadata;
	}

	/**
	 * Re-index when file metadata changes.
	 *
	 * @param int    $meta_id Meta ID.
	 * @param int    $object_id Object ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @return void
	 */
	public function handle_attachment_meta_changed($meta_id, $object_id, $meta_key, $meta_value) {
		if (!in_array($meta_key, array('_wp_attached_file', '_wp_attachment_metadata'), true)) {
			return;
		}

		$this->index_attachment((int) $object_id, true);
	}

	/**
	 * Remove an attachment from the PDF index.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return void
	 */
	public function handle_attachment_removed($attachment_id) {
		$post = get_post((int) $attachment_id);

		if ($post instanceof WP_Post && $post->post_type !== 'attachment') {
			return;
		}

		$this->repository->delete((int) $attachment_id, 'pdf');
	}

	/**
	 * Index one PDF attachment.
	 *
	 * @param int  $attachment_id Attachment ID.
	 * @param bool $force Force extraction.
	 * @return string inserted|updated|skipped|deleted|ignored|failed
	 */
	public function index_attachment($attachment_id, $force = false, $rebuild_token = '') {
		$attachment_id = (int) $attachment_id;

		if ($attachment_id <= 0 || isset($this->indexing[$attachment_id])) {
			return 'ignored';
		}

		$this->indexing[$attachment_id] = true;

		try {
			$attachment = get_post($attachment_id);

			if (!$attachment instanceof WP_Post || !$this->is_indexable_pdf($attachment)) {
				$this->repository->delete($attachment_id, 'pdf');
				return 'ignored';
			}

			$file_path = get_attached_file($attachment_id);
			$source_hash = $this->build_source_hash($attachment, $file_path);
			$existing = $this->repository->find($attachment_id, 'pdf');

			if (
				!$force
				&& is_array($existing)
				&& isset($existing['source_hash'])
				&& hash_equals((string) $existing['source_hash'], $source_hash)
				&& (int) $existing['schema_version'] === VFWP_Intranet_Search_Schema::VERSION
			) {
				$this->repository->mark_rebuild_token($attachment_id, 'pdf', (string) $rebuild_token);
				return 'skipped';
			}

			$extraction = $this->extractor->extract($file_path);
			$data = $this->build_index_data($attachment, $file_path, $source_hash, $extraction);

			if ($rebuild_token !== '') {
				$data['rebuild_token'] = (string) $rebuild_token;
			}

			return $this->repository->upsert($data, $force);
		} finally {
			unset($this->indexing[$attachment_id]);
		}
	}

	/**
	 * Determine whether an attachment should be indexed as a PDF.
	 *
	 * @param WP_Post $attachment Attachment.
	 * @return bool
	 */
	private function is_indexable_pdf(WP_Post $attachment) {
		if ($attachment->post_type !== 'attachment') {
			return false;
		}

		if (get_post_mime_type($attachment) !== 'application/pdf') {
			return false;
		}

		if (in_array($attachment->post_status, array('trash', 'private', 'draft', 'auto-draft'), true)) {
			return false;
		}

		if (!empty($attachment->post_password)) {
			return false;
		}

		if ((int) $attachment->post_parent > 0) {
			$parent = get_post((int) $attachment->post_parent);

			if ($parent instanceof WP_Post) {
				if ($parent->post_status !== 'publish' || !empty($parent->post_password)) {
					return false;
				}
			}
		}

		return (bool) apply_filters('vfwp_intranet_search_is_indexable_pdf', true, $attachment);
	}

	/**
	 * Build a source hash before extraction.
	 *
	 * @param WP_Post     $attachment Attachment.
	 * @param string|bool $file_path File path.
	 * @return string
	 */
	private function build_source_hash(WP_Post $attachment, $file_path) {
		$file_path = is_string($file_path) ? $file_path : '';
		$file_name = $file_path !== '' ? basename($file_path) : '';
		$file_size = $file_path !== '' && file_exists($file_path) ? filesize($file_path) : 0;
		$file_mtime = $file_path !== '' && file_exists($file_path) ? filemtime($file_path) : 0;

		return $this->normalizer->hash(array(
			'object_id'        => (int) $attachment->ID,
			'post_title'       => $attachment->post_title,
			'post_excerpt'     => $attachment->post_excerpt,
			'post_content'     => $attachment->post_content,
			'post_status'      => $attachment->post_status,
			'post_parent'      => (int) $attachment->post_parent,
			'file_name'        => $file_name,
			'file_size'        => $file_size,
			'file_mtime'       => $file_mtime,
			'schema_version'   => VFWP_Intranet_Search_Schema::VERSION,
			'extraction_class' => get_class($this->extractor),
		));
	}

	/**
	 * Build a PDF index row.
	 *
	 * @param WP_Post $attachment Attachment.
	 * @param string  $file_path File path.
	 * @param string  $source_hash Source hash.
	 * @param array   $extraction Extraction result.
	 * @return array
	 */
	private function build_index_data(WP_Post $attachment, $file_path, $source_hash, array $extraction) {
		$title = $this->normalizer->normalize_text($attachment->post_title);
		$excerpt = $this->normalizer->normalize_text($attachment->post_excerpt);
		$file_name = is_string($file_path) && $file_path !== '' ? basename($file_path) : '';
		$file_name_text = $this->normalizer->normalize_text(pathinfo($file_name, PATHINFO_FILENAME));
		$content = $this->normalizer->normalize_content($extraction['text']);
		$url = wp_get_attachment_url($attachment->ID);

		if ($title === '') {
			$title = $file_name_text;
		}

		$content_hash = $this->normalizer->hash(array(
			'object_type'       => 'pdf',
			'title'             => $title,
			'excerpt'           => $excerpt,
			'file_name'         => $file_name,
			'content'           => $content,
			'extraction_status' => $extraction['status'],
			'url'               => is_string($url) ? $url : '',
			'parent_object_id'  => (int) $attachment->post_parent,
		));

		return array(
			'object_id'         => (int) $attachment->ID,
			'object_type'       => 'pdf',
			'post_type'         => 'attachment',
			'post_status'       => $attachment->post_status,
			'visibility'        => 'public',
			'title'             => $title,
			'excerpt'           => $excerpt,
			'content'           => $content,
			'acf_keywords'      => $file_name_text,
			'url'               => is_string($url) ? $url : '',
			'published_at'      => get_post_time('Y-m-d H:i:s', true, $attachment),
			'updated_at'        => get_post_modified_time('Y-m-d H:i:s', true, $attachment),
			'schema_version'    => VFWP_Intranet_Search_Schema::VERSION,
			'content_hash'      => $content_hash,
			'source_hash'       => $source_hash,
			'parent_object_id'  => (int) $attachment->post_parent,
			'file_name'         => $file_name,
			'extraction_status' => $extraction['status'],
			'extraction_error'  => $extraction['error'],
		);
	}
}
