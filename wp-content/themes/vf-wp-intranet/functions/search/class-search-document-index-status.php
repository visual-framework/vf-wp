<?php
/**
 * Admin indicators for Document PDF search indexing state.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Document_Index_Status {
	/**
	 * @var VFWP_Intranet_Search_Index_Repository
	 */
	private $repository;

	/**
	 * @param VFWP_Intranet_Search_Index_Repository|null $repository Repository.
	 */
	public function __construct($repository = null) {
		$this->repository = $repository ? $repository : new VFWP_Intranet_Search_Index_Repository();
	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter('manage_documents_posts_columns', array($this, 'add_documents_column'));
		add_action('manage_documents_posts_custom_column', array($this, 'render_documents_column'), 10, 2);
		add_action('acf/render_field/name=upload_file', array($this, 'render_upload_file_status'), 20);
		add_action('admin_head', array($this, 'print_admin_styles'));
	}

	/**
	 * Add an index status column to the Documents list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_documents_column($columns) {
		$updated = array();

		foreach ($columns as $key => $label) {
			$updated[$key] = $label;

			if ('title' === $key) {
				$updated['vfwp_search_index_status'] = __('Search index', 'vfwp');
			}
		}

		if (!isset($updated['vfwp_search_index_status'])) {
			$updated['vfwp_search_index_status'] = __('Search index', 'vfwp');
		}

		return $updated;
	}

	/**
	 * Render the Documents list table index status column.
	 *
	 * @param string $column_name Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function render_documents_column($column_name, $post_id) {
		if ('vfwp_search_index_status' !== $column_name) {
			return;
		}

		$status = $this->get_document_status((int) $post_id);

		echo $this->get_badge_markup($status); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render a status indicator below the Document upload_file field.
	 *
	 * @param array $field ACF field.
	 * @return void
	 */
	public function render_upload_file_status($field) {
		$post_id = $this->get_current_admin_post_id();

		if ($post_id <= 0 || get_post_type($post_id) !== 'documents') {
			return;
		}

		$status = $this->get_document_status($post_id);

		echo '<div class="vfwp-search-index-status">';
		echo $this->get_badge_markup($status); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}

	/**
	 * Return index status for one Document post.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	private function get_document_status($post_id) {
		$post = get_post((int) $post_id);

		$status = array(
			'state'   => 'not_indexed',
			'label'   => __('Not indexed', 'vfwp'),
		);

		if (!$post instanceof WP_Post || $post->post_type !== 'documents') {
			return $status;
		}

		if ('publish' !== $post->post_status || !empty($post->post_password)) {
			$status['state'] = 'not_searchable';
			$status['label'] = __('Not searchable', 'vfwp');

			return $status;
		}

		$attachment = $this->get_document_attachment_data((int) $post->ID);
		$row = $this->repository->find((int) $post->ID, 'post');

		if (!is_array($row)) {
			return $status;
		}

		if ($attachment['attachment_id'] <= 0) {
			$status['state'] = 'indexed';
			$status['label'] = __('Indexed', 'vfwp');

			return $status;
		}

		if (!$attachment['is_pdf']) {
			$status['state'] = 'indexed';
			$status['label'] = __('Indexed', 'vfwp');

			return $status;
		}

		$row_attachment_id = isset($row['parent_object_id']) ? (int) $row['parent_object_id'] : 0;
		$row_file_name = isset($row['file_name']) ? (string) $row['file_name'] : '';

		if ($row_attachment_id !== (int) $attachment['attachment_id'] || $row_file_name !== (string) $attachment['file_name']) {
			$status['state'] = 'stale';
			$status['label'] = __('Needs reindex', 'vfwp');

			return $status;
		}

		if ($this->document_pdf_file_metadata_is_stale((int) $post->ID, $attachment)) {
			$status['state'] = 'stale';
			$status['label'] = __('Needs reindex', 'vfwp');

			return $status;
		}

		$extraction_status = isset($row['extraction_status']) ? (string) $row['extraction_status'] : '';

		if (in_array($extraction_status, array('success', 'success_truncated'), true)) {
			$status['state'] = 'indexed';
			$status['label'] = __('Indexed', 'vfwp');
		} else {
			$status['state'] = 'issue';
			$status['label'] = __('PDF issue', 'vfwp');
		}

		return $status;
	}

	/**
	 * Return current Document upload attachment data.
	 *
	 * @param int $post_id Document post ID.
	 * @return array
	 */
	private function get_document_attachment_data($post_id) {
		$attachment_id = $this->get_document_upload_file_attachment_id((int) $post_id);
		$data = array(
			'attachment_id' => $attachment_id,
			'is_pdf'        => false,
			'file_name'     => '',
			'file_size'     => 0,
			'file_mtime'    => 0,
			'mime_type'     => '',
		);

		if ($attachment_id <= 0) {
			return $data;
		}

		$mime_type = get_post_mime_type($attachment_id);
		$file_path = get_attached_file($attachment_id);

		$data['mime_type'] = is_string($mime_type) ? $mime_type : '';
		$data['is_pdf'] = 'application/pdf' === $data['mime_type'];
		$data['file_name'] = is_string($file_path) && $file_path !== '' ? basename($file_path) : basename((string) get_the_title($attachment_id));
		$data['file_size'] = is_string($file_path) && $file_path !== '' && file_exists($file_path) ? (int) filesize($file_path) : 0;
		$data['file_mtime'] = is_string($file_path) && $file_path !== '' && file_exists($file_path) ? (int) filemtime($file_path) : 0;

		return $data;
	}

	/**
	 * Determine whether stored PDF file metadata no longer matches the attachment.
	 *
	 * @param int   $post_id Document post ID.
	 * @param array $attachment Attachment data.
	 * @return bool
	 */
	private function document_pdf_file_metadata_is_stale($post_id, array $attachment) {
		$indexed_file_size = get_post_meta((int) $post_id, '_vfwp_search_pdf_file_size', true);
		$indexed_file_mtime = get_post_meta((int) $post_id, '_vfwp_search_pdf_file_mtime', true);

		if ($indexed_file_size !== '' && (int) $indexed_file_size !== (int) $attachment['file_size']) {
			return true;
		}

		if ($indexed_file_mtime !== '' && (int) $indexed_file_mtime !== (int) $attachment['file_mtime']) {
			return true;
		}

		return false;
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
	 * Return the current edited post ID in wp-admin.
	 *
	 * @return int
	 */
	private function get_current_admin_post_id() {
		if (isset($_GET['post']) && is_numeric($_GET['post'])) {
			return (int) $_GET['post'];
		}

		if (isset($_POST['post_ID']) && is_numeric($_POST['post_ID'])) {
			return (int) $_POST['post_ID'];
		}

		global $post;

		return $post instanceof WP_Post ? (int) $post->ID : 0;
	}

	/**
	 * Return a status badge.
	 *
	 * @param array $status Status data.
	 * @return string
	 */
	private function get_badge_markup(array $status) {
		$state = isset($status['state']) ? sanitize_html_class((string) $status['state']) : 'not_indexed';
		$label = isset($status['label']) ? (string) $status['label'] : __('Not indexed', 'vfwp');

		return sprintf(
			'<span class="vfwp-search-index-status__badge vfwp-search-index-status__badge--%1$s">%2$s</span>',
			esc_attr($state),
			esc_html($label)
		);
	}

	/**
	 * Print lightweight admin styles.
	 *
	 * @return void
	 */
	public function print_admin_styles() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : null;

		if (!$screen || 'documents' !== $screen->post_type) {
			return;
		}
		?>
		<style>
			.vfwp-search-index-status {
				margin-top: 8px;
			}

			.vfwp-search-index-status__badge {
				border-radius: 3px;
				display: inline-block;
				font-size: 12px;
				font-weight: 600;
				line-height: 1.4;
				padding: 2px 7px;
			}

			.vfwp-search-index-status__badge--indexed {
				background: #d7f0df;
				color: #24663a;
			}

			.vfwp-search-index-status__badge--stale,
			.vfwp-search-index-status__badge--issue {
				background: #fff4ce;
				color: #7a5600;
			}

			.vfwp-search-index-status__badge--not_indexed,
			.vfwp-search-index-status__badge--not_searchable {
				background: #f3f4f5;
				color: #50575e;
			}

		</style>
		<?php
	}
}
