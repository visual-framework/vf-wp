<?php
/**
 * Post indexing service and content lifecycle hooks.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Indexer {
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
	private $pdf_extractor;

	/**
	 * Recursion guard by post ID.
	 *
	 * @var array
	 */
	private $indexing = array();

	/**
	 * @param VFWP_Intranet_Search_Index_Repository $repository Repository.
	 * @param VFWP_Intranet_Search_Normalizer       $normalizer Normalizer.
	 * @param VFWP_Intranet_Search_PDF_Extractor|null $pdf_extractor PDF extractor.
	 */
	public function __construct(VFWP_Intranet_Search_Index_Repository $repository, VFWP_Intranet_Search_Normalizer $normalizer, $pdf_extractor = null) {
		$this->repository = $repository;
		$this->normalizer = $normalizer;
		$this->pdf_extractor = $pdf_extractor instanceof VFWP_Intranet_Search_PDF_Extractor
			? $pdf_extractor
			: new VFWP_Intranet_Search_PDF_Extractor();
	}

	/**
	 * Register post lifecycle hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action('save_post', array($this, 'handle_save_post'), 20, 3);
		add_action('acf/save_post', array($this, 'handle_acf_save_post'), 30);
		add_action('transition_post_status', array($this, 'handle_status_transition'), 20, 3);
		add_action('trashed_post', array($this, 'handle_post_removed'));
		add_action('untrashed_post', array($this, 'handle_post_restored'));
		add_action('before_delete_post', array($this, 'handle_post_removed'));
		add_action('added_post_meta', array($this, 'handle_document_upload_meta_changed'), 20, 4);
		add_action('updated_post_meta', array($this, 'handle_document_upload_meta_changed'), 20, 4);
		add_action('deleted_post_meta', array($this, 'handle_document_upload_meta_changed'), 20, 4);
		add_action('edit_attachment', array($this, 'handle_document_pdf_attachment_changed'), 20);
		add_action('attachment_updated', array($this, 'handle_document_pdf_attachment_updated'), 20, 3);
		add_filter('wp_update_attachment_metadata', array($this, 'handle_document_pdf_attachment_metadata_updated'), 20, 2);
		add_action('delete_attachment', array($this, 'handle_document_pdf_attachment_changed'), 20);
		add_action('added_post_meta', array($this, 'handle_attachment_file_meta_changed'), 20, 4);
		add_action('updated_post_meta', array($this, 'handle_attachment_file_meta_changed'), 20, 4);
	}

	/**
	 * Index or remove a post after save.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 * @param bool    $update Whether this is an update.
	 * @return void
	 */
	public function handle_save_post($post_id, $post, $update) {
		$this->index_post($post_id);
	}

	/**
	 * Index after ACF has saved field values.
	 *
	 * @param mixed $post_id ACF post identifier.
	 * @return void
	 */
	public function handle_acf_save_post($post_id) {
		if (!is_numeric($post_id)) {
			return;
		}

		$this->index_post((int) $post_id);
	}

	/**
	 * Keep the index synchronized when visibility changes.
	 *
	 * @param string  $new_status New status.
	 * @param string  $old_status Old status.
	 * @param WP_Post $post Post object.
	 * @return void
	 */
	public function handle_status_transition($new_status, $old_status, $post) {
		if (!$post instanceof WP_Post) {
			return;
		}

		if ('publish' === $new_status) {
			$this->index_post($post->ID);
			return;
		}

		if ('publish' === $old_status && 'publish' !== $new_status) {
			$this->repository->delete($post->ID, 'post');
		}
	}

	/**
	 * Remove trashed or deleted posts from the index.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function handle_post_removed($post_id) {
		$this->repository->delete((int) $post_id, 'post');
	}

	/**
	 * Re-index restored content if it is searchable.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function handle_post_restored($post_id) {
		$this->index_post((int) $post_id, true);
	}

	/**
	 * Re-index a Document when its uploaded file ACF value changes.
	 *
	 * @param mixed  $meta_id Meta ID.
	 * @param int    $object_id Post ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @return void
	 */
	public function handle_document_upload_meta_changed($meta_id, $object_id, $meta_key, $meta_value) {
		if ($meta_key !== 'upload_file') {
			return;
		}

		if (get_post_type((int) $object_id) !== 'documents') {
			return;
		}

		$this->index_post((int) $object_id, true);
	}

	/**
	 * Re-index Documents that reference an updated PDF attachment.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return void
	 */
	public function handle_document_pdf_attachment_changed($attachment_id) {
		$this->reindex_documents_for_attachment((int) $attachment_id, true);
	}

	/**
	 * Re-index Documents that reference an updated attachment.
	 *
	 * @param int     $attachment_id Attachment ID.
	 * @param WP_Post $after Updated attachment.
	 * @param WP_Post $before Previous attachment.
	 * @return void
	 */
	public function handle_document_pdf_attachment_updated($attachment_id, $after, $before) {
		$this->reindex_documents_for_attachment((int) $attachment_id, true);
	}

	/**
	 * Re-index Documents after attachment metadata changes.
	 *
	 * @param mixed $metadata Attachment metadata.
	 * @param int   $attachment_id Attachment ID.
	 * @return mixed
	 */
	public function handle_document_pdf_attachment_metadata_updated($metadata, $attachment_id) {
		$this->reindex_documents_for_attachment((int) $attachment_id, true);

		return $metadata;
	}

	/**
	 * Re-index Documents when the physical attachment file changes.
	 *
	 * @param mixed  $meta_id Meta ID.
	 * @param int    $object_id Attachment ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @return void
	 */
	public function handle_attachment_file_meta_changed($meta_id, $object_id, $meta_key, $meta_value) {
		if (!in_array($meta_key, array('_wp_attached_file', '_wp_attachment_metadata'), true)) {
			return;
		}

		if (get_post_type((int) $object_id) !== 'attachment') {
			return;
		}

		$this->reindex_documents_for_attachment((int) $object_id, true);
	}

	/**
	 * Re-index all Documents linked to an attachment.
	 *
	 * @param int  $attachment_id Attachment ID.
	 * @param bool $force Force re-indexing.
	 * @return void
	 */
	public function reindex_documents_for_attachment($attachment_id, $force = false) {
		$this->repository->delete((int) $attachment_id, 'pdf');
		$document_ids = $this->get_document_ids_for_attachment((int) $attachment_id);

		foreach ($document_ids as $document_id) {
			$this->index_post((int) $document_id, (bool) $force);
		}
	}

	/**
	 * Index one post if it is publicly searchable.
	 *
	 * @param int  $post_id Post ID.
	 * @param bool $force Force rebuilding the row.
	 * @return string inserted|updated|skipped|deleted|ignored|failed
	 */
	public function index_post($post_id, $force = false, $rebuild_token = '') {
		$post_id = (int) $post_id;

		if ($post_id <= 0 || isset($this->indexing[$post_id])) {
			return 'ignored';
		}

		$this->indexing[$post_id] = true;

		try {
			$post = get_post($post_id);

			if (!$post instanceof WP_Post) {
				$this->repository->delete($post_id, 'post');
				return 'deleted';
			}

			if (!$this->is_indexable_post($post)) {
				$this->repository->delete($post_id, 'post');
				return 'ignored';
			}

			$acf_keywords = $this->get_acf_keywords($post);
			$document_pdf_source = $this->get_document_pdf_source($post);
			$source_hash = $this->build_source_hash($post, $acf_keywords, $document_pdf_source);
			$existing = $this->repository->find($post_id, 'post');

			if (
				!$force
				&& is_array($existing)
				&& isset($existing['source_hash'])
				&& hash_equals((string) $existing['source_hash'], $source_hash)
				&& (int) $existing['schema_version'] === VFWP_Intranet_Search_Schema::VERSION
			) {
				$this->repository->mark_rebuild_token($post_id, 'post', (string) $rebuild_token);
				return 'skipped';
			}

			$data = $this->build_index_data($post, $acf_keywords, $document_pdf_source, $source_hash);

			if ($rebuild_token !== '') {
				$data['rebuild_token'] = (string) $rebuild_token;
			}

			return $this->repository->upsert($data, $force);
		} finally {
			unset($this->indexing[$post_id]);
		}
	}

	/**
	 * Determine whether a post should be indexed.
	 *
	 * @param WP_Post $post Post object.
	 * @return bool
	 */
	public function is_indexable_post(WP_Post $post) {
		if (wp_is_post_revision($post) || wp_is_post_autosave($post)) {
			return false;
		}

		if ('publish' !== $post->post_status) {
			return false;
		}

		if (!empty($post->post_password)) {
			return false;
		}

		if (!in_array($post->post_type, $this->get_indexable_post_types(), true)) {
			return false;
		}

		return (bool) apply_filters('vfwp_intranet_search_is_indexable_post', true, $post);
	}

	/**
	 * Return public post types that are intended to appear in search.
	 *
	 * @return array
	 */
	public function get_indexable_post_types() {
		if (class_exists('VFWP_Intranet_Search_Settings')) {
			return (array) apply_filters(
				'vfwp_intranet_search_indexable_post_types',
				VFWP_Intranet_Search_Settings::get_enabled_post_types()
			);
		}

		return (array) apply_filters('vfwp_intranet_search_indexable_post_types', array());
	}

	/**
	 * Build an index row from a post object.
	 *
	 * @param WP_Post $post Post object.
	 * @return array
	 */
	private function build_index_data(WP_Post $post, $acf_keywords, array $document_pdf_source, $source_hash) {
		$title = $this->normalizer->normalize_text($post->post_title);
		$excerpt = $this->normalizer->normalize_text($post->post_excerpt);
		$content = $this->normalizer->normalize_content($post->post_content);
		$document_pdf_index = $this->extract_document_pdf_index_data($document_pdf_source);

		if ($post->post_type === 'documents') {
			$content = !empty($document_pdf_index['content']) ? $document_pdf_index['content'] : '';
		} elseif (!empty($document_pdf_index['content'])) {
			$content = trim($content . "\n\n" . $document_pdf_index['content']);
		}

		$url = get_permalink($post);

		$data = array(
			'object_id'         => (int) $post->ID,
			'object_type'       => 'post',
			'post_type'         => $post->post_type,
			'post_status'       => $post->post_status,
			'visibility'        => 'public',
			'title'             => $title,
			'excerpt'           => $excerpt,
			'content'           => $content,
			'acf_keywords'      => $acf_keywords,
			'url'               => is_string($url) ? $url : '',
			'published_at'      => get_post_time('Y-m-d H:i:s', true, $post),
			'updated_at'        => get_post_modified_time('Y-m-d H:i:s', true, $post),
			'schema_version'    => VFWP_Intranet_Search_Schema::VERSION,
			'source_hash'       => $source_hash,
			'parent_object_id'  => (int) $document_pdf_source['attachment_id'],
			'file_name'         => (string) $document_pdf_source['file_name'],
			'extraction_status' => (string) $document_pdf_index['extraction_status'],
			'extraction_error'  => (string) $document_pdf_index['extraction_error'],
		);

		$data['content_hash'] = $this->normalizer->hash(array(
			'post_type'    => $data['post_type'],
			'post_status'  => $data['post_status'],
			'visibility'   => $data['visibility'],
			'title'        => $data['title'],
			'excerpt'      => $data['excerpt'],
			'content'      => $data['content'],
			'acf_keywords' => $data['acf_keywords'],
			'file_name'    => $data['file_name'],
			'extraction_status' => $data['extraction_status'],
			'url'          => $data['url'],
			'published_at' => $data['published_at'],
			'updated_at'   => $data['updated_at'],
		));

		return $data;
	}

	/**
	 * Build a cheap source hash before any PDF text extraction runs.
	 *
	 * @param WP_Post $post Post object.
	 * @param string  $acf_keywords Normalized ACF keywords.
	 * @param array   $document_pdf_source Document PDF source metadata.
	 * @return string
	 */
	private function build_source_hash(WP_Post $post, $acf_keywords, array $document_pdf_source) {
		$url = get_permalink($post);

		return $this->normalizer->hash(array(
			'post_type'        => $post->post_type,
			'post_status'      => $post->post_status,
			'title'            => $this->normalizer->normalize_text($post->post_title),
			'excerpt'          => $this->normalizer->normalize_text($post->post_excerpt),
			'content'          => $this->normalizer->normalize_content($post->post_content),
			'acf_keywords'     => (string) $acf_keywords,
			'url'              => is_string($url) ? $url : '',
			'document_pdf'     => $document_pdf_source,
			'schema_version'   => VFWP_Intranet_Search_Schema::VERSION,
			'extraction_class' => get_class($this->pdf_extractor),
			'extraction_available' => $this->pdf_extractor->is_available(),
		));
	}

	/**
	 * Return metadata for a Document's uploaded PDF without extracting text.
	 *
	 * @param WP_Post $post Post object.
	 * @return array
	 */
	private function get_document_pdf_source(WP_Post $post) {
		$source = array(
			'attachment_id' => 0,
			'is_pdf'        => false,
			'file_path'     => '',
			'file_name'     => '',
			'file_size'     => 0,
			'file_mtime'    => 0,
		);

		if ($post->post_type !== 'documents') {
			return $source;
		}

		$attachment_id = $this->get_document_upload_file_attachment_id((int) $post->ID);

		if ($attachment_id <= 0) {
			return $source;
		}

		$source['attachment_id'] = $attachment_id;
		$attachment = get_post($attachment_id);

		if (!$attachment instanceof WP_Post || $attachment->post_type !== 'attachment') {
			return $source;
		}

		if (in_array($attachment->post_status, array('trash', 'private', 'draft', 'auto-draft'), true) || !empty($attachment->post_password)) {
			return $source;
		}

		if (get_post_mime_type($attachment) !== 'application/pdf') {
			return $source;
		}

		$file_path = get_attached_file($attachment_id);
		$file_path = is_string($file_path) ? $file_path : '';

		$source['is_pdf'] = true;
		$source['file_path'] = $file_path;
		$source['file_name'] = $file_path !== '' ? basename($file_path) : '';
		$source['file_size'] = $file_path !== '' && file_exists($file_path) ? (int) filesize($file_path) : 0;
		$source['file_mtime'] = $file_path !== '' && file_exists($file_path) ? (int) filemtime($file_path) : 0;

		return $source;
	}

	/**
	 * Extract normalized PDF text for a Document row.
	 *
	 * @param array $document_pdf_source Document PDF source metadata.
	 * @return array
	 */
	private function extract_document_pdf_index_data(array $document_pdf_source) {
		$result = array(
			'content'           => '',
			'extraction_status' => '',
			'extraction_error'  => '',
		);

		if (empty($document_pdf_source['is_pdf'])) {
			return $result;
		}

		$extraction = $this->pdf_extractor->extract((string) $document_pdf_source['file_path']);

		$result['content'] = $this->normalizer->normalize_content(isset($extraction['text']) ? $extraction['text'] : '');
		$result['extraction_status'] = isset($extraction['status']) ? (string) $extraction['status'] : 'failed';
		$result['extraction_error'] = isset($extraction['error']) ? (string) $extraction['error'] : '';

		return $result;
	}

	/**
	 * Return the attachment ID stored by the Document upload_file ACF field.
	 *
	 * @param int $post_id Document post ID.
	 * @return int
	 */
	private function get_document_upload_file_attachment_id($post_id) {
		$value = get_post_meta((int) $post_id, 'upload_file', true);

		if (is_numeric($value)) {
			return (int) $value;
		}

		if (is_array($value) && isset($value['ID']) && is_numeric($value['ID'])) {
			return (int) $value['ID'];
		}

		if (is_array($value) && isset($value['id']) && is_numeric($value['id'])) {
			return (int) $value['id'];
		}

		if (function_exists('get_field')) {
			$field_value = get_field('upload_file', (int) $post_id, false);

			if (is_numeric($field_value)) {
				return (int) $field_value;
			}
		}

		return 0;
	}

	/**
	 * Return Document IDs that reference an attachment through upload_file.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return array
	 */
	private function get_document_ids_for_attachment($attachment_id) {
		$attachment_id = (int) $attachment_id;

		if ($attachment_id <= 0) {
			return array();
		}

		global $wpdb;

		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT pm.post_id
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = %s
					AND pm.meta_value = %s
					AND p.post_type = %s",
				'upload_file',
				(string) $attachment_id,
				'documents'
			)
		);

		return is_array($ids) ? array_map('intval', $ids) : array();
	}

	/**
	 * Read configured ACF fields and return normalized keyword text.
	 *
	 * @param WP_Post $post Post object.
	 * @return string
	 */
	private function get_acf_keywords(WP_Post $post) {
		if (!class_exists('VFWP_Intranet_Search_Settings')) {
			return '';
		}

		$field_names = VFWP_Intranet_Search_Settings::get_acf_field_names();
		$keywords = array();

		foreach ($field_names as $field_name) {
			$value = get_post_meta($post->ID, $field_name, true);

			if (($value === '' || $value === null) && function_exists('get_field')) {
				$value = get_field($field_name, $post->ID, false);
			}

			$keywords = array_merge($keywords, $this->extract_acf_keyword_entries($value));
		}

		$keywords = array_values(array_unique(array_filter($keywords)));

		return implode(', ', $keywords);
	}

	/**
	 * Extract bounded keyword entries from configured ACF values.
	 *
	 * @param mixed $value Raw ACF value.
	 * @param int   $depth Recursion depth.
	 * @return array
	 */
	private function extract_acf_keyword_entries($value, $depth = 0) {
		if ($depth > 4) {
			return array();
		}

		if (is_string($value) || is_int($value) || is_float($value)) {
			$text = $this->normalizer->normalize_text($value);

			if ($text === '') {
				return array();
			}

			$parts = preg_split('/[,;\r\n|]+/u', $text);

			if (!is_array($parts)) {
				$parts = array($text);
			}

			$entries = array();

			foreach ($parts as $part) {
				$entry = $this->normalizer->normalize_text($part);

				if ($entry !== '') {
					$entries[] = $entry;
				}
			}

			return $entries;
		}

		if (!is_array($value)) {
			return array();
		}

		$entries = array();

		foreach ($value as $key => $child_value) {
			if ($this->should_skip_acf_keyword_key($key)) {
				continue;
			}

			$entries = array_merge($entries, $this->extract_acf_keyword_entries($child_value, $depth + 1));

			if (count($entries) >= 100) {
				break;
			}
		}

		return array_slice($entries, 0, 100);
	}

	/**
	 * Avoid common non-keyword ACF metadata.
	 *
	 * @param mixed $key Array key.
	 * @return bool
	 */
	private function should_skip_acf_keyword_key($key) {
		if (is_int($key)) {
			return false;
		}

		$key = strtolower((string) $key);

		if ($key === '' || strpos($key, '_') === 0) {
			return true;
		}

		return in_array(
			$key,
			array('id', 'url', 'uri', 'filename', 'filesize', 'mime_type', 'mime', 'type', 'subtype', 'icon', 'width', 'height', 'sizes'),
			true
		);
	}
}
