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
	 * Recursion guard by post ID.
	 *
	 * @var array
	 */
	private $indexing = array();

	/**
	 * @param VFWP_Intranet_Search_Index_Repository $repository Repository.
	 * @param VFWP_Intranet_Search_Normalizer       $normalizer Normalizer.
	 */
	public function __construct(VFWP_Intranet_Search_Index_Repository $repository, VFWP_Intranet_Search_Normalizer $normalizer) {
		$this->repository = $repository;
		$this->normalizer = $normalizer;
	}

	/**
	 * Register post lifecycle hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action('save_post', array($this, 'handle_save_post'), 20, 3);
		add_action('transition_post_status', array($this, 'handle_status_transition'), 20, 3);
		add_action('trashed_post', array($this, 'handle_post_removed'));
		add_action('untrashed_post', array($this, 'handle_post_restored'));
		add_action('before_delete_post', array($this, 'handle_post_removed'));
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

			$data = $this->build_index_data($post);

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
	private function build_index_data(WP_Post $post) {
		$title = $this->normalizer->normalize_text($post->post_title);
		$excerpt = $this->normalizer->normalize_text($post->post_excerpt);
		$content = $this->normalizer->normalize_content($post->post_content);
		$acf_keywords = $this->get_acf_keywords($post);
		$url = get_permalink($post);

		$data = array(
			'object_id'      => (int) $post->ID,
			'object_type'    => 'post',
			'post_type'      => $post->post_type,
			'post_status'    => $post->post_status,
			'visibility'     => 'public',
			'title'          => $title,
			'excerpt'        => $excerpt,
			'content'        => $content,
			'acf_keywords'   => $acf_keywords,
			'url'            => is_string($url) ? $url : '',
			'published_at'   => get_post_time('Y-m-d H:i:s', true, $post),
			'updated_at'     => get_post_modified_time('Y-m-d H:i:s', true, $post),
			'schema_version' => VFWP_Intranet_Search_Schema::VERSION,
		);

		$data['content_hash'] = $this->normalizer->hash(array(
			'post_type'    => $data['post_type'],
			'post_status'  => $data['post_status'],
			'visibility'   => $data['visibility'],
			'title'        => $data['title'],
			'excerpt'      => $data['excerpt'],
			'content'      => $data['content'],
			'acf_keywords' => $data['acf_keywords'],
			'url'          => $data['url'],
			'published_at' => $data['published_at'],
			'updated_at'   => $data['updated_at'],
		));

		return $data;
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

			$normalized_value = $this->normalizer->normalize_acf_value($value);

			if ($normalized_value !== '') {
				$keywords[] = $normalized_value;
			}
		}

		return $this->normalizer->normalize_text(implode(' ', $keywords));
	}
}
