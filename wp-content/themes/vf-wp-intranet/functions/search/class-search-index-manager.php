<?php
/**
 * Batch/background index management for the theme search system.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Index_Manager {
	const STATUS_OPTION = 'vfwp_intranet_search_index_status';
	const LAST_FULL_REBUILD_OPTION = 'vfwp_intranet_search_last_full_rebuild';
	const CRON_HOOK = 'vfwp_intranet_search_process_index_batch';
	const LOCK_TRANSIENT = 'vfwp_intranet_search_index_batch_lock';
	const DEFAULT_BATCH_SIZE = 25;
	const MAX_BATCH_SIZE = 100;

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
	 * Register hooks for background processing and admin actions.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action(self::CRON_HOOK, array($this, 'process_batch'));
		add_action('admin_post_vfwp_intranet_search_index_action', array($this, 'handle_admin_action'));
		add_action('wp_ajax_vfwp_intranet_search_process_index_batch', array($this, 'handle_ajax_process_batch'));
	}

	/**
	 * Start a full rebuild.
	 *
	 * @param bool $clear_first Whether to clear the table before rebuilding.
	 * @param int  $batch_size Batch size.
	 * @return array
	 */
	public function start_full_rebuild($clear_first = false, $batch_size = self::DEFAULT_BATCH_SIZE) {
		return $this->start_job($clear_first ? 'clear_rebuild' : 'full', $batch_size, $clear_first);
	}

	/**
	 * Start a changed-content pass. Unchanged content is skipped by hashes.
	 *
	 * @param int $batch_size Batch size.
	 * @return array
	 */
	public function start_changed_reindex($batch_size = self::DEFAULT_BATCH_SIZE) {
		return $this->start_job('changed', $batch_size, false);
	}

	/**
	 * Process one indexing batch.
	 *
	 * @return array
	 */
	public function process_batch() {
		$status = $this->get_status();

		if (empty($status['active'])) {
			return $status;
		}

		if (get_transient(self::LOCK_TRANSIENT)) {
			return $status;
		}

		set_transient(self::LOCK_TRANSIENT, 1, 5 * MINUTE_IN_SECONDS);

		try {
			$status['status'] = 'running';
			$status['last_activity_at'] = current_time('mysql', true);
			$this->save_status($status);

			if ($status['phase'] === 'posts') {
				$status = $this->process_posts_batch($status);
			} elseif ($status['phase'] === 'pdfs') {
				$status['phase'] = in_array($status['mode'], array('full', 'clear_rebuild'), true) ? 'prune' : 'complete';

				if ($status['phase'] === 'complete') {
					$status = $this->complete_job($status);
				}
			} elseif ($status['phase'] === 'prune') {
				$status = $this->process_prune_phase($status);
			}

			if (!empty($status['active'])) {
				$this->schedule_next_batch();
			}

			$this->save_status($status);
			return $status;
		} catch (Exception $exception) {
			$status['active'] = 0;
			$status['status'] = 'failed';
			$status['message'] = sanitize_text_field($exception->getMessage());
			$status['last_activity_at'] = current_time('mysql', true);
			$this->save_status($status);
			$this->clear_scheduled_batches();

			return $status;
		} finally {
			delete_transient(self::LOCK_TRANSIENT);
		}
	}

	/**
	 * Return current index counts and job state.
	 *
	 * @return array
	 */
	public function get_dashboard_data() {
		$status = $this->get_status();
		$counts = $this->repository->get_counts();
		$pdf_issue_count = $this->repository->count_pdf_extraction_issues();
		$rebuild_required = get_option(VFWP_Intranet_Search_Settings::REBUILD_REQUIRED_OPTION, array());

		return array(
			'counts'                => $counts,
			'pdf_issue_count'       => $pdf_issue_count,
			'status'                => $status,
			'schema_version'        => VFWP_Intranet_Search_Schema::VERSION,
			'installed_version'     => (int) get_option(VFWP_Intranet_Search_Schema::OPTION_NAME, 0),
			'last_full_rebuild'     => get_option(self::LAST_FULL_REBUILD_OPTION, ''),
			'rebuild_required'      => is_array($rebuild_required) ? $rebuild_required : array(),
			'next_scheduled_batch'  => wp_next_scheduled(self::CRON_HOOK),
		);
	}

	/**
	 * Handle Settings > Search index management actions.
	 *
	 * @return void
	 */
	public function handle_admin_action() {
		if (!current_user_can(VFWP_Intranet_Search_Settings::ADMIN_CAPABILITY)) {
			wp_die(esc_html__('You do not have permission to manage the search index.', 'vfwp'));
		}

		check_admin_referer('vfwp_intranet_search_index_action');

		$action = isset($_POST['search_index_action']) ? sanitize_key(wp_unslash($_POST['search_index_action'])) : '';
		$result = array(
			'started' => false,
			'message' => __('Unknown search index action.', 'vfwp'),
		);

		if ($action === 'rebuild_full') {
			$result = $this->start_full_rebuild(false);
		} elseif ($action === 'reindex_changed') {
			$result = $this->start_changed_reindex();
		} elseif ($action === 'clear_recreate') {
			$result = $this->start_full_rebuild(true);
		}

		if (!empty($result['started'])) {
			$status = $this->process_batch();
			$result['status'] = $status;
			$result['started'] = $status['status'] !== 'failed';
			$result['message'] = empty($status['active'])
				? $status['message']
				: __('Search index rebuild started. Processing continues in batches while this page is open.', 'vfwp');
		}

		$redirect_url = add_query_arg(
			array(
				'page'                      => 'vfwp-intranet-search',
				'vfwp_search_index_notice'  => $result['started'] ? 'started' : 'blocked',
				'vfwp_search_index_message' => rawurlencode($result['message']),
			),
			admin_url('options-general.php')
		);

		wp_safe_redirect($redirect_url);
		exit;
	}

	/**
	 * Process one batch from the Settings > Search page.
	 *
	 * @return void
	 */
	public function handle_ajax_process_batch() {
		if (!current_user_can(VFWP_Intranet_Search_Settings::ADMIN_CAPABILITY)) {
			wp_send_json_error(
				array(
					'message' => __('You do not have permission to manage the search index.', 'vfwp'),
				),
				403
			);
		}

		check_ajax_referer('vfwp_intranet_search_process_batch', 'nonce');

		$status = $this->process_batch();

		wp_send_json_success(
			array(
				'status'    => $status,
				'dashboard' => $this->get_dashboard_data(),
			)
		);
	}

	/**
	 * Return the persisted status object.
	 *
	 * @return array
	 */
	public function get_status() {
		$status = get_option(self::STATUS_OPTION, array());

		if (!is_array($status)) {
			$status = array();
		}

		return wp_parse_args(
			$status,
			array(
				'active'           => 0,
				'status'           => 'idle',
				'mode'             => '',
				'phase'            => '',
				'rebuild_token'    => '',
				'batch_size'       => self::DEFAULT_BATCH_SIZE,
				'total_planned'    => 0,
				'processed'        => 0,
				'indexed'          => 0,
				'skipped'          => 0,
				'failed'           => 0,
				'deleted'          => 0,
				'post_offset'      => 0,
				'pdf_offset'       => 0,
				'document_pdf_total' => 0,
				'document_pdf_processed' => 0,
				'document_pdf_extracted' => 0,
				'document_pdf_failed' => 0,
				'document_pdf_text_updated' => 0,
				'started_at'       => '',
				'last_activity_at' => '',
				'completed_at'     => '',
				'message'          => '',
			)
		);
	}

	/**
	 * Start a batch job.
	 *
	 * @param string $mode Job mode.
	 * @param int    $batch_size Batch size.
	 * @param bool   $clear_first Whether to clear first.
	 * @return array
	 */
	private function start_job($mode, $batch_size, $clear_first) {
		$current_status = $this->get_status();

		if (!empty($current_status['active'])) {
			return array(
				'started' => false,
				'message' => __('A search index job is already running.', 'vfwp'),
				'status'  => $current_status,
			);
		}

		VFWP_Intranet_Search_Schema::install();
		$this->clear_scheduled_batches();

		if ($clear_first) {
			$this->repository->truncate();
		}

		$batch_size = min(self::MAX_BATCH_SIZE, max(1, (int) $batch_size));
		$now = current_time('mysql', true);
		$status = array(
			'active'           => 1,
			'status'           => 'queued',
			'mode'             => $mode,
			'phase'            => 'posts',
			'rebuild_token'    => wp_generate_uuid4(),
			'batch_size'       => $batch_size,
			'total_planned'    => $this->count_planned_items(),
			'processed'        => 0,
			'indexed'          => 0,
			'skipped'          => 0,
			'failed'           => 0,
			'deleted'          => 0,
			'post_offset'      => 0,
			'pdf_offset'       => 0,
			'document_pdf_total' => $this->count_planned_document_pdfs(),
			'document_pdf_processed' => 0,
			'document_pdf_extracted' => 0,
			'document_pdf_failed' => 0,
			'document_pdf_text_updated' => 0,
			'started_at'       => $now,
			'last_activity_at' => $now,
			'completed_at'     => '',
			'message'          => $clear_first ? __('Search index cleared. Rebuild started.', 'vfwp') : __('Search index rebuild started.', 'vfwp'),
		);

		$this->save_status($status);
		$this->schedule_next_batch();

		return array(
			'started' => true,
			'message' => $status['message'],
			'status'  => $status,
		);
	}

	/**
	 * Process public searchable posts.
	 *
	 * @param array $status Status.
	 * @return array
	 */
	private function process_posts_batch(array $status) {
		$post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();

		if (empty($post_types)) {
			$status['phase'] = in_array($status['mode'], array('full', 'clear_rebuild'), true) ? 'prune' : 'complete';

			if ($status['phase'] === 'complete') {
				$status = $this->complete_job($status);
			}

			return $status;
		}

		$post_ids = get_posts(array(
			'post_type'              => $post_types,
			'post_status'            => 'publish',
			'has_password'           => false,
			'fields'                 => 'ids',
			'posts_per_page'         => (int) $status['batch_size'],
			'offset'                 => (int) $status['post_offset'],
			'orderby'                => 'ID',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		));

		if (empty($post_ids)) {
			$status['phase'] = in_array($status['mode'], array('full', 'clear_rebuild'), true) ? 'prune' : 'complete';

			if ($status['phase'] === 'complete') {
				$status = $this->complete_job($status);
			}

			return $status;
		}

		foreach ($post_ids as $post_id) {
			$force = in_array($status['mode'], array('full', 'clear_rebuild'), true);
			$document_pdf_context = $this->get_document_pdf_context((int) $post_id);
			$result = vfwp_intranet_search_index_post((int) $post_id, $force, $this->get_active_rebuild_token($status));
			$status = $this->record_result($status, $result);
			$status = $this->record_document_pdf_result($status, (int) $post_id, $document_pdf_context);
		}

		$status['post_offset'] += count($post_ids);
		$status['last_activity_at'] = current_time('mysql', true);

		return $status;
	}

	/**
	 * Process PDF attachments.
	 *
	 * @param array $status Status.
	 * @return array
	 */
	private function process_pdfs_batch(array $status) {
		$status['phase'] = in_array($status['mode'], array('full', 'clear_rebuild'), true) ? 'prune' : 'complete';

		if ($status['phase'] === 'complete') {
			$status = $this->complete_job($status);
		}

		return $status;
	}

	/**
	 * Prune stale rows after a full rebuild.
	 *
	 * @param array $status Status.
	 * @return array
	 */
	private function process_prune_phase(array $status) {
		$status['deleted'] += $this->repository->delete_rows_not_in_rebuild($status['rebuild_token']);

		return $this->complete_job($status);
	}

	/**
	 * Complete a job.
	 *
	 * @param array $status Status.
	 * @return array
	 */
	private function complete_job(array $status) {
		$completed_at = current_time('mysql', true);
		$status['active'] = 0;
		$status['status'] = 'completed';
		$status['phase'] = 'complete';
		$status['completed_at'] = $completed_at;
		$status['last_activity_at'] = $completed_at;
		$status['message'] = __('Search index job completed.', 'vfwp');

		if (in_array($status['mode'], array('full', 'clear_rebuild'), true)) {
			update_option(self::LAST_FULL_REBUILD_OPTION, $completed_at, false);
			delete_option(VFWP_Intranet_Search_Settings::REBUILD_REQUIRED_OPTION);
		}

		$this->clear_scheduled_batches();

		return $status;
	}

	/**
	 * Record one index result into status counters.
	 *
	 * @param array  $status Status.
	 * @param string $result Result string.
	 * @return array
	 */
	private function record_result(array $status, $result) {
		$status['processed']++;

		if (in_array($result, array('inserted', 'updated'), true)) {
			$status['indexed']++;
		} elseif ($result === 'skipped' || $result === 'ignored') {
			$status['skipped']++;
		} elseif ($result === 'deleted') {
			$status['deleted']++;
		} else {
			$status['failed']++;
		}

		return $status;
	}

	/**
	 * Count planned items for progress display.
	 *
	 * @return int
	 */
	private function count_planned_items() {
		$post_count = 0;
		$post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();

		if (!empty($post_types)) {
			$post_query = new WP_Query(array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'has_password'   => false,
				'fields'         => 'ids',
				'posts_per_page' => 1,
			));
			$post_count = (int) $post_query->found_posts;
		}

		return $post_count;
	}

	/**
	 * Count public Document posts that point to a PDF attachment.
	 *
	 * @return int
	 */
	private function count_planned_document_pdfs() {
		$post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();

		if (!in_array('documents', $post_types, true)) {
			return 0;
		}

		$document_ids = get_posts(array(
			'post_type'              => 'documents',
			'post_status'            => 'publish',
			'has_password'           => false,
			'fields'                 => 'ids',
			'posts_per_page'         => -1,
			'orderby'                => 'ID',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => array(
				array(
					'key'     => 'upload_file',
					'compare' => 'EXISTS',
				),
			),
		));

		$count = 0;

		foreach ($document_ids as $document_id) {
			if ($this->document_has_pdf_attachment((int) $document_id)) {
				$count++;
			}
		}

		return $count;
	}

	/**
	 * Return PDF context for a Document before it is indexed.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	private function get_document_pdf_context($post_id) {
		$context = array(
			'has_pdf'         => false,
			'pdf_text_before' => '',
		);

		if (get_post_type((int) $post_id) !== 'documents') {
			return $context;
		}

		if (!$this->document_has_pdf_attachment((int) $post_id)) {
			return $context;
		}

		$context['has_pdf'] = true;
		$context['pdf_text_before'] = (string) get_post_meta((int) $post_id, 'pdf_text', true);

		return $context;
	}

	/**
	 * Record PDF extraction and ACF field update progress for one Document.
	 *
	 * @param array $status Job status.
	 * @param int   $post_id Post ID.
	 * @param array $context Document PDF context.
	 * @return array
	 */
	private function record_document_pdf_result(array $status, $post_id, array $context) {
		if (empty($context['has_pdf'])) {
			return $status;
		}

		$status['document_pdf_processed'] = isset($status['document_pdf_processed']) ? (int) $status['document_pdf_processed'] + 1 : 1;

		$existing = $this->repository->find((int) $post_id, 'post');
		$extraction_status = is_array($existing) && isset($existing['extraction_status']) ? (string) $existing['extraction_status'] : '';

		if (in_array($extraction_status, array('success', 'success_truncated'), true)) {
			$status['document_pdf_extracted'] = isset($status['document_pdf_extracted']) ? (int) $status['document_pdf_extracted'] + 1 : 1;
		} elseif ($extraction_status !== '') {
			$status['document_pdf_failed'] = isset($status['document_pdf_failed']) ? (int) $status['document_pdf_failed'] + 1 : 1;
		}

		$pdf_text_after = (string) get_post_meta((int) $post_id, 'pdf_text', true);

		if ($pdf_text_after !== '' && $pdf_text_after !== (string) $context['pdf_text_before']) {
			$status['document_pdf_text_updated'] = isset($status['document_pdf_text_updated']) ? (int) $status['document_pdf_text_updated'] + 1 : 1;
		}

		return $status;
	}

	/**
	 * Determine whether a Document post points to a public PDF attachment.
	 *
	 * @param int $post_id Document post ID.
	 * @return bool
	 */
	private function document_has_pdf_attachment($post_id) {
		$attachment_id = $this->get_document_upload_file_attachment_id((int) $post_id);

		if ($attachment_id <= 0) {
			return false;
		}

		$attachment = get_post($attachment_id);

		if (!$attachment instanceof WP_Post || $attachment->post_type !== 'attachment') {
			return false;
		}

		if (in_array($attachment->post_status, array('trash', 'private', 'draft', 'auto-draft'), true) || !empty($attachment->post_password)) {
			return false;
		}

		return get_post_mime_type($attachment) === 'application/pdf';
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
	 * Schedule the next single batch if none is pending.
	 *
	 * @return void
	 */
	private function schedule_next_batch() {
		if (!wp_next_scheduled(self::CRON_HOOK)) {
			wp_schedule_single_event(time() + 1, self::CRON_HOOK);
		}
	}

	/**
	 * Clear pending single batch events.
	 *
	 * @return void
	 */
	private function clear_scheduled_batches() {
		while ($timestamp = wp_next_scheduled(self::CRON_HOOK)) {
			wp_unschedule_event($timestamp, self::CRON_HOOK);
		}
	}

	/**
	 * Return the rebuild token only for jobs that will prune stale rows.
	 *
	 * @param array $status Status.
	 * @return string
	 */
	private function get_active_rebuild_token(array $status) {
		return in_array($status['mode'], array('full', 'clear_rebuild'), true) ? (string) $status['rebuild_token'] : '';
	}

	/**
	 * Persist status.
	 *
	 * @param array $status Status.
	 * @return void
	 */
	private function save_status(array $status) {
		update_option(self::STATUS_OPTION, $status, false);
	}
}
