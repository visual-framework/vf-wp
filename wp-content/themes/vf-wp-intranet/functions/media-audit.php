<?php
/**
 * Admin media usage audit for the intranet theme.
 *
 * The scanner favours keeping media when there is any signal that an
 * attachment may still be used.
 */

if (!defined('ABSPATH')) {
	exit;
}

define('VFWP_INTRANET_MEDIA_AUDIT_PAGE', 'vfwp-intranet-media-audit');
define('VFWP_INTRANET_MEDIA_AUDIT_NONCE', 'vfwp_intranet_media_audit');
define('VFWP_INTRANET_MEDIA_AUDIT_DEFAULT_PER_PAGE', 10);
define('VFWP_INTRANET_MEDIA_AUDIT_MIN_PER_PAGE', 5);
define('VFWP_INTRANET_MEDIA_AUDIT_MAX_PER_PAGE', 50);
define('VFWP_INTRANET_MEDIA_AUDIT_FULL_SCAN_BATCH_SIZE', 1);
define('VFWP_INTRANET_MEDIA_AUDIT_DUPLICATE_SIZE_BATCH_SIZE', 10);
define('VFWP_INTRANET_MEDIA_AUDIT_DUPLICATE_HASH_BATCH_SIZE', 1);

function vfwp_intranet_media_audit_admin_menu() {
	add_management_page(
		__('Intranet Media Audit', 'vfwp'),
		__('Intranet Media Audit', 'vfwp'),
		'manage_options',
		VFWP_INTRANET_MEDIA_AUDIT_PAGE,
		'vfwp_intranet_media_audit_render_page'
	);
}
add_action('admin_menu', 'vfwp_intranet_media_audit_admin_menu');

function vfwp_intranet_media_audit_export_csv() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to export this report.', 'vfwp'));
	}

	check_admin_referer(VFWP_INTRANET_MEDIA_AUDIT_NONCE);

	if (function_exists('set_time_limit')) {
		set_time_limit(120);
	}

	$safety_days = vfwp_intranet_media_audit_get_safety_days();
	$mime = isset($_GET['attachment_mime']) ? sanitize_text_field(wp_unslash($_GET['attachment_mime'])) : '';
	$status_filter = vfwp_intranet_media_audit_get_status_filter_from_request($_GET);
	$upload_order = vfwp_intranet_media_audit_get_upload_order_from_request($_GET);
	$uploaded_from = vfwp_intranet_media_audit_get_uploaded_date_from_request($_GET, 'uploaded_from');
	$uploaded_to = vfwp_intranet_media_audit_get_uploaded_date_from_request($_GET, 'uploaded_to');
	$filename = 'intranet-media-audit-' . gmdate('Y-m-d-His') . '.csv';

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $filename);
	header('Pragma: no-cache');
	header('Expires: 0');

	$output = fopen('php://output', 'w');
	fputcsv($output, array(
		'Attachment ID',
		'Title',
		'File',
		'Mime type',
		'Uploaded',
		'Size',
		'Status',
		'Evidence count',
		'Evidence',
		'Edit URL',
		'File URL',
	));

	$attachment_args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'orderby'        => 'date',
		'order'          => $upload_order,
	);

	if ($mime !== '') {
		$attachment_args['post_mime_type'] = $mime;
	}

	$date_query = vfwp_intranet_media_audit_get_uploaded_date_query($uploaded_from, $uploaded_to);
	if (!empty($date_query)) {
		$attachment_args['date_query'] = $date_query;
	}

	$attachment_ids = get_posts($attachment_args);

	foreach ($attachment_ids as $attachment_id) {
		$report = vfwp_intranet_media_audit_build_attachment_report((int) $attachment_id, $safety_days);
		if (!vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter)) {
			continue;
		}

		fputcsv($output, array(
			$report['id'],
			$report['title'],
			$report['relative_file'],
			$report['mime_type'],
			$report['uploaded'],
			$report['file_size_label'],
			$report['status_label'],
			count($report['evidence']),
			implode(' | ', wp_list_pluck($report['evidence'], 'label')),
			get_edit_post_link($report['id'], ''),
			$report['url'],
		));
	}

	fclose($output);
	exit;
}
add_action('admin_post_vfwp_intranet_media_audit_export', 'vfwp_intranet_media_audit_export_csv');

function vfwp_intranet_media_audit_bulk_action() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to update media.', 'vfwp'));
	}

	check_admin_referer(VFWP_INTRANET_MEDIA_AUDIT_NONCE);

	$redirect_args = vfwp_intranet_media_audit_get_redirect_args_from_request();
	if (!empty($_POST['scan_duplicates'])) {
		$redirect_args['scan_duplicates'] = 1;
		$redirect_args['refresh_duplicates'] = 1;
	} else {
		$redirect_args['media_audit_scan'] = 1;
	}

	$bulk_action = isset($_POST['bulk_action']) ? sanitize_key(wp_unslash($_POST['bulk_action'])) : '';
	$attachment_ids = isset($_POST['attachment_ids']) && is_array($_POST['attachment_ids'])
		? array_map('absint', wp_unslash($_POST['attachment_ids']))
		: array();
	$attachment_ids = array_values(array_unique(array_filter($attachment_ids)));

	if ($bulk_action !== 'delete' || empty($attachment_ids)) {
		$redirect_args['media_audit_notice'] = 'no_selection';
		wp_safe_redirect(add_query_arg($redirect_args, admin_url('tools.php')));
		exit;
	}

	$deleted = 0;
	$skipped = 0;
	$failed = 0;

	foreach ($attachment_ids as $attachment_id) {
		$attachment = get_post($attachment_id);

		if (!$attachment || $attachment->post_type !== 'attachment') {
			$skipped++;
			continue;
		}

		$result = wp_delete_attachment($attachment_id, true);

		if ($result && !get_post($attachment_id)) {
			$deleted++;
		} else {
			$failed++;
		}
	}

	$redirect_args['media_audit_deleted'] = $deleted;
	$redirect_args['media_audit_skipped'] = $skipped;
	$redirect_args['media_audit_failed'] = $failed;
	delete_transient('vfwp_intranet_media_audit_duplicate_groups');
	wp_safe_redirect(add_query_arg($redirect_args, admin_url('tools.php')));
	exit;
}
add_action('admin_post_vfwp_intranet_media_audit_bulk_action', 'vfwp_intranet_media_audit_bulk_action');

function vfwp_intranet_media_audit_clear_duplicate_cache() {
	delete_transient('vfwp_intranet_media_audit_duplicate_groups');
}
add_action('delete_attachment', 'vfwp_intranet_media_audit_clear_duplicate_cache');

function vfwp_intranet_media_audit_ajax_scan() {
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array(
			'message' => __('You do not have permission to scan media.', 'vfwp'),
		), 403);
	}

	check_ajax_referer(VFWP_INTRANET_MEDIA_AUDIT_NONCE, 'nonce');

	if (function_exists('set_time_limit')) {
		set_time_limit(120);
	}

	$paged = isset($_POST['paged']) ? max(1, (int) $_POST['paged']) : 1;
	$per_page = vfwp_intranet_media_audit_get_per_page_from_request($_POST);
	$safety_days = isset($_POST['safety_days']) ? min(365, max(1, (int) $_POST['safety_days'])) : 90;
	$mime = isset($_POST['attachment_mime']) ? sanitize_text_field(wp_unslash($_POST['attachment_mime'])) : '';
	$status_filter = vfwp_intranet_media_audit_get_status_filter_from_request($_POST);
	$upload_order = vfwp_intranet_media_audit_get_upload_order_from_request($_POST);
	$uploaded_from = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_from');
	$uploaded_to = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_to');
	$scan_duplicates = isset($_POST['scan_duplicates']);
	$refresh_duplicates = isset($_POST['refresh_duplicates']);

	register_shutdown_function('vfwp_intranet_media_audit_ajax_shutdown_handler');

	ob_start();

	if ($scan_duplicates) {
		$duplicate_groups = vfwp_intranet_media_audit_get_duplicate_groups($refresh_duplicates, $uploaded_from, $uploaded_to, $mime);
		vfwp_intranet_media_audit_render_duplicate_groups($duplicate_groups, $safety_days, $upload_order, $uploaded_from, $uploaded_to, $mime, $status_filter);
	} else {
		$scan = vfwp_intranet_media_audit_get_report_page($paged, $per_page, $mime, $safety_days, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
		vfwp_intranet_media_audit_render_scan_results(
			$scan['reports'],
			$scan['query'],
			$paged,
			$per_page,
			$safety_days,
			$mime,
			$upload_order,
			$uploaded_from,
			$uploaded_to,
			$status_filter
		);
		vfwp_intranet_media_audit_render_scan_pagination($scan['query'], $paged, $per_page, $safety_days, $mime, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
	}

	wp_send_json_success(array(
		'html' => ob_get_clean(),
	));
}
add_action('wp_ajax_vfwp_intranet_media_audit_scan', 'vfwp_intranet_media_audit_ajax_scan');

function vfwp_intranet_media_audit_ajax_full_scan_batch() {
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array(
			'message' => __('You do not have permission to scan media.', 'vfwp'),
		), 403);
	}

	check_ajax_referer(VFWP_INTRANET_MEDIA_AUDIT_NONCE, 'nonce');

	if (function_exists('set_time_limit')) {
		set_time_limit(60);
	}

	register_shutdown_function('vfwp_intranet_media_audit_ajax_shutdown_handler');

	$paged = isset($_POST['paged']) ? max(1, (int) $_POST['paged']) : 1;
	$batch_size = VFWP_INTRANET_MEDIA_AUDIT_FULL_SCAN_BATCH_SIZE;
	$safety_days = isset($_POST['safety_days']) ? min(365, max(1, (int) $_POST['safety_days'])) : 90;
	$mime = isset($_POST['attachment_mime']) ? sanitize_text_field(wp_unslash($_POST['attachment_mime'])) : '';
	$status_filter = vfwp_intranet_media_audit_get_status_filter_from_request($_POST);
	$upload_order = vfwp_intranet_media_audit_get_upload_order_from_request($_POST);
	$uploaded_from = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_from');
	$uploaded_to = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_to');
	$query = vfwp_intranet_media_audit_get_attachment_query($paged, $batch_size, $mime, $upload_order, $uploaded_from, $uploaded_to);

	ob_start();
	foreach ($query->posts as $attachment) {
		$report = vfwp_intranet_media_audit_build_attachment_report((int) $attachment->ID, $safety_days);
		if (!vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter)) {
			continue;
		}

		vfwp_intranet_media_audit_render_report_row($report);
	}
	$rows_html = ob_get_clean();

	$total = max(0, (int) $query->found_posts);
	$processed = min($total, $paged * $batch_size);
	$done = $total === 0 || $processed >= $total || empty($query->posts);

	wp_send_json_success(array(
		'rows_html' => $rows_html,
		'total' => $total,
		'processed' => $processed,
		'next_paged' => $paged + 1,
		'done' => $done,
	));
}
add_action('wp_ajax_vfwp_intranet_media_audit_full_scan_batch', 'vfwp_intranet_media_audit_ajax_full_scan_batch');

function vfwp_intranet_media_audit_ajax_duplicate_scan_batch() {
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array(
			'message' => __('You do not have permission to scan media.', 'vfwp'),
		), 403);
	}

	check_ajax_referer(VFWP_INTRANET_MEDIA_AUDIT_NONCE, 'nonce');

	if (function_exists('set_time_limit')) {
		set_time_limit(60);
	}

	register_shutdown_function('vfwp_intranet_media_audit_ajax_shutdown_handler');

	$token = isset($_POST['duplicate_token']) ? sanitize_key(wp_unslash($_POST['duplicate_token'])) : '';
	$reset = !empty($_POST['reset_duplicate_scan']);
	$safety_days = isset($_POST['safety_days']) ? min(365, max(1, (int) $_POST['safety_days'])) : 90;
	$mime = isset($_POST['attachment_mime']) ? sanitize_text_field(wp_unslash($_POST['attachment_mime'])) : '';
	$status_filter = vfwp_intranet_media_audit_get_status_filter_from_request($_POST);
	$upload_order = vfwp_intranet_media_audit_get_upload_order_from_request($_POST);
	$uploaded_from = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_from');
	$uploaded_to = vfwp_intranet_media_audit_get_uploaded_date_from_request($_POST, 'uploaded_to');

	if ($reset || $token === '') {
		$token = wp_generate_uuid4();
		$state = vfwp_intranet_media_audit_create_duplicate_scan_state($mime, $upload_order, $uploaded_from, $uploaded_to);
	} else {
		$state = vfwp_intranet_media_audit_get_duplicate_scan_state($token);
		if (!is_array($state)) {
			wp_send_json_error(array(
				'message' => __('The duplicate scan state expired. Start the duplicate scan again.', 'vfwp'),
			), 410);
		}
	}

	if ($state['stage'] === 'sizes') {
		$state = vfwp_intranet_media_audit_process_duplicate_size_batch($state);
	} elseif ($state['stage'] === 'hashes') {
		$state = vfwp_intranet_media_audit_process_duplicate_hash_batch($state);
	}

	$response = vfwp_intranet_media_audit_get_duplicate_scan_response($token, $state);

	if (!empty($response['done'])) {
		$duplicate_groups = isset($response['duplicate_groups']) ? $response['duplicate_groups'] : array();
		unset($response['duplicate_groups']);

		ob_start();
		vfwp_intranet_media_audit_render_duplicate_groups($duplicate_groups, $safety_days, $upload_order, $uploaded_from, $uploaded_to, $mime, $status_filter);
		$response['html'] = ob_get_clean();
		vfwp_intranet_media_audit_delete_duplicate_scan_state($token);
	} else {
		vfwp_intranet_media_audit_set_duplicate_scan_state($token, $state);
	}

	wp_send_json_success($response);
}
add_action('wp_ajax_vfwp_intranet_media_audit_duplicate_scan_batch', 'vfwp_intranet_media_audit_ajax_duplicate_scan_batch');

function vfwp_intranet_media_audit_render_page() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to view this report.', 'vfwp'));
	}

	$paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
	$per_page = vfwp_intranet_media_audit_get_per_page_from_request($_GET);
	$safety_days = vfwp_intranet_media_audit_get_safety_days();
	$mime = isset($_GET['attachment_mime']) ? sanitize_text_field(wp_unslash($_GET['attachment_mime'])) : '';
	$status_filter = vfwp_intranet_media_audit_get_status_filter_from_request($_GET);
	$upload_order = vfwp_intranet_media_audit_get_upload_order_from_request($_GET);
	$uploaded_from = vfwp_intranet_media_audit_get_uploaded_date_from_request($_GET, 'uploaded_from');
	$uploaded_to = vfwp_intranet_media_audit_get_uploaded_date_from_request($_GET, 'uploaded_to');
	$has_scan = isset($_GET['media_audit_scan']);

	$reports = array();
	$query = null;

	if ($has_scan) {
		$scan = vfwp_intranet_media_audit_get_report_page($paged, $per_page, $mime, $safety_days, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
		$query = $scan['query'];
		$reports = $scan['reports'];
	}

	$duplicate_groups = array();
	if (isset($_GET['scan_duplicates'])) {
		$duplicate_groups = vfwp_intranet_media_audit_get_duplicate_groups(isset($_GET['refresh_duplicates']), $uploaded_from, $uploaded_to, $mime);
	}

	$base_url = menu_page_url(VFWP_INTRANET_MEDIA_AUDIT_PAGE, false);
	$export_url = wp_nonce_url(
		add_query_arg(
			array(
				'action'          => 'vfwp_intranet_media_audit_export',
				'safety_days'     => $safety_days,
				'attachment_mime' => $mime,
				'status_filter'   => $status_filter,
				'upload_order'    => $upload_order,
				'uploaded_from'   => $uploaded_from,
				'uploaded_to'     => $uploaded_to,
			),
			admin_url('admin-post.php')
		),
		VFWP_INTRANET_MEDIA_AUDIT_NONCE
	);
	?>
	<div class="wrap vfwp-media-audit">
		<h1><?php echo esc_html__('Intranet Media Audit', 'vfwp'); ?></h1>
		<p>
			<?php echo esc_html__('This report finds media usage signals across post content, featured images, ACF/post meta, user meta, term meta, options, and this theme. Bulk deletion permanently removes selected media files.', 'vfwp'); ?>
		</p>

		<?php vfwp_intranet_media_audit_render_admin_notices(); ?>

		<form method="get" class="vfwp-media-audit__filters" data-vfwp-media-audit-form>
			<input type="hidden" name="page" value="<?php echo esc_attr(VFWP_INTRANET_MEDIA_AUDIT_PAGE); ?>">
			<label>
				<?php echo esc_html__('Mime type', 'vfwp'); ?>
				<input type="text" name="attachment_mime" value="<?php echo esc_attr($mime); ?>" placeholder="image/jpeg">
			</label>
			<label>
				<?php echo esc_html__('Status', 'vfwp'); ?>
				<select name="status_filter">
					<option value="" <?php selected($status_filter, ''); ?>><?php echo esc_html__('All statuses', 'vfwp'); ?></option>
					<option value="used" <?php selected($status_filter, 'used'); ?>><?php echo esc_html__('Evidence found', 'vfwp'); ?></option>
					<option value="needs-review" <?php selected($status_filter, 'needs-review'); ?>><?php echo esc_html__('Needs review - recently uploaded', 'vfwp'); ?></option>
					<option value="candidate-unused" <?php selected($status_filter, 'candidate-unused'); ?>><?php echo esc_html__('Candidate unused', 'vfwp'); ?></option>
				</select>
			</label>
			<label>
				<?php echo esc_html__('Safety window days', 'vfwp'); ?>
				<input type="number" name="safety_days" value="<?php echo esc_attr($safety_days); ?>" min="1" max="365">
			</label>
			<label>
				<?php echo esc_html__('Rows per page', 'vfwp'); ?>
				<input type="number" name="per_page" value="<?php echo esc_attr($per_page); ?>" min="<?php echo esc_attr(VFWP_INTRANET_MEDIA_AUDIT_MIN_PER_PAGE); ?>" max="<?php echo esc_attr(VFWP_INTRANET_MEDIA_AUDIT_MAX_PER_PAGE); ?>">
			</label>
			<label>
				<?php echo esc_html__('Uploaded from', 'vfwp'); ?>
				<input type="date" name="uploaded_from" value="<?php echo esc_attr($uploaded_from); ?>">
			</label>
			<label>
				<?php echo esc_html__('Uploaded to', 'vfwp'); ?>
				<input type="date" name="uploaded_to" value="<?php echo esc_attr($uploaded_to); ?>">
			</label>
			<label>
				<?php echo esc_html__('Uploaded date', 'vfwp'); ?>
				<select name="upload_order">
					<option value="DESC" <?php selected($upload_order, 'DESC'); ?>><?php echo esc_html__('Newest first', 'vfwp'); ?></option>
					<option value="ASC" <?php selected($upload_order, 'ASC'); ?>><?php echo esc_html__('Oldest first', 'vfwp'); ?></option>
				</select>
			</label>
			<button type="submit" class="button button-primary" name="media_audit_scan" value="1"><?php echo esc_html__('Scan', 'vfwp'); ?></button>
			<a class="button" href="<?php echo esc_url($export_url); ?>"><?php echo esc_html__('Export CSV', 'vfwp'); ?></a>
			<button type="submit" class="button" name="scan_duplicates" value="1"><?php echo esc_html__('Find Exact Duplicates', 'vfwp'); ?></button>
		</form>

		<div class="vfwp-media-audit__loading" data-vfwp-media-audit-loading hidden>
			<div class="vfwp-media-audit__loading-label" data-vfwp-media-audit-loading-label><?php echo esc_html__('Scanning media library...', 'vfwp'); ?></div>
			<div class="vfwp-media-audit__loading-track">
				<div class="vfwp-media-audit__loading-bar" data-vfwp-media-audit-loading-bar></div>
			</div>
			<div class="vfwp-media-audit__loading-progress" data-vfwp-media-audit-full-progress hidden></div>
		</div>

		<div data-vfwp-media-audit-results>
			<?php if (isset($_GET['scan_duplicates'])) : ?>
				<?php vfwp_intranet_media_audit_render_duplicate_groups($duplicate_groups, $safety_days, $upload_order, $uploaded_from, $uploaded_to, $mime, $status_filter); ?>
			<?php elseif ($has_scan) : ?>
				<?php
				vfwp_intranet_media_audit_render_scan_results($reports, $query, $paged, $per_page, $safety_days, $mime, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
				vfwp_intranet_media_audit_render_scan_pagination($query, $paged, $per_page, $safety_days, $mime, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
				?>
			<?php else : ?>
				<div class="notice notice-warning inline">
					<p><?php echo esc_html__('Set filters if needed, then click Scan. The media library is not scanned automatically when this page opens.', 'vfwp'); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php

		vfwp_intranet_media_audit_render_reference_box();
		?>
	</div>

	<style>
		.vfwp-media-audit__filters {
			align-items: end;
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
			margin: 16px 0;
		}

		.vfwp-media-audit__filters label {
			display: grid;
			font-weight: 600;
			gap: 4px;
		}

		.vfwp-media-audit__filters input {
			font-weight: 400;
			min-width: 140px;
		}

		.vfwp-media-audit__status {
			border-radius: 3px;
			display: inline-block;
			font-weight: 600;
			padding: 4px 8px;
		}

		.vfwp-media-audit__status--used {
			background: #d1e7dd;
			color: #0f5132;
		}

		.vfwp-media-audit__status--candidate-unused {
			background: #fff3cd;
			color: #664d03;
		}

		.vfwp-media-audit__status--needs-review {
			background: #cff4fc;
			color: #055160;
		}

		.vfwp-media-audit__evidence {
			list-style: disc;
			margin: 0 0 0 18px;
		}

		.vfwp-media-audit__duplicates {
			margin: 18px 0;
		}

		.vfwp-media-audit__reference {
			background: #fff;
			border: 1px solid #c3c4c7;
			margin-top: 24px;
			padding: 16px;
		}

		.vfwp-media-audit__reference h2 {
			margin-top: 0;
		}

		.vfwp-media-audit__reference h3 {
			margin-bottom: 6px;
		}

		.vfwp-media-audit__reference dl {
			display: grid;
			gap: 10px;
			margin: 0;
		}

		.vfwp-media-audit__reference dt {
			font-weight: 600;
		}

		.vfwp-media-audit__reference dd {
			margin: -8px 0 0;
		}

		.vfwp-media-audit__loading {
			background: #fff;
			border: 1px solid #c3c4c7;
			margin: 16px 0;
			padding: 12px;
		}

		.vfwp-media-audit__loading-label {
			font-weight: 600;
			margin-bottom: 8px;
		}

		.vfwp-media-audit__loading-track {
			background: #dcdcde;
			height: 8px;
			overflow: hidden;
			position: relative;
		}

		.vfwp-media-audit__loading-bar {
			animation: vfwp-media-audit-loading 1.2s ease-in-out infinite;
			background: #2271b1;
			height: 8px;
			left: -35%;
			position: absolute;
			width: 35%;
		}

		.vfwp-media-audit__loading.is-full-scan .vfwp-media-audit__loading-bar {
			animation: none;
			left: 0;
			transition: width 160ms ease;
			width: 0;
		}

		.vfwp-media-audit__loading-progress {
			margin-top: 8px;
		}

		@keyframes vfwp-media-audit-loading {
			0% {
				left: -35%;
			}

			100% {
				left: 100%;
			}
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var form = document.querySelector('[data-vfwp-media-audit-form]');
			var loading = document.querySelector('[data-vfwp-media-audit-loading]');
			var results = document.querySelector('[data-vfwp-media-audit-results]');
			var loadingLabel = document.querySelector('[data-vfwp-media-audit-loading-label]');
			var loadingBar = document.querySelector('[data-vfwp-media-audit-loading-bar]');
			var fullProgress = document.querySelector('[data-vfwp-media-audit-full-progress]');
			var ajaxUrl = '<?php echo esc_js(admin_url('admin-ajax.php')); ?>';
			var adminPostUrl = '<?php echo esc_js(admin_url('admin-post.php')); ?>';
			var nonce = '<?php echo esc_js(wp_create_nonce(VFWP_INTRANET_MEDIA_AUDIT_NONCE)); ?>';
			var pageSlug = '<?php echo esc_js(VFWP_INTRANET_MEDIA_AUDIT_PAGE); ?>';
			var defaultLoadingLabel = '<?php echo esc_js(__('Scanning media library...', 'vfwp')); ?>';

			if (!form || !loading) {
				return;
			}

			function setLoading(isLoading, label) {
				loading.hidden = !isLoading;

				if (loadingLabel) {
					loadingLabel.textContent = label || defaultLoadingLabel;
				}

				if (!isLoading) {
					loading.classList.remove('is-full-scan');

					if (loadingBar) {
						loadingBar.style.left = '';
						loadingBar.style.width = '';
					}

					if (fullProgress) {
						fullProgress.hidden = true;
						fullProgress.textContent = '';
					}
				}

				if (results) {
					results.setAttribute('aria-busy', isLoading ? 'true' : 'false');
				}
			}

			function setFullScanProgress(processed, total, message) {
				var percent = total > 0 ? Math.min(100, Math.round((processed / total) * 100)) : 0;

				loading.classList.add('is-full-scan');

				if (loadingBar) {
					loadingBar.style.left = '0';
					loadingBar.style.width = percent + '%';
				}

				if (fullProgress) {
					fullProgress.hidden = false;
					fullProgress.textContent = total > 0
						? (message ? message + ' ' : '<?php echo esc_js(__('Scanned media items:', 'vfwp')); ?> ') + processed + ' <?php echo esc_js(__('of', 'vfwp')); ?> ' + total
						: (message || '<?php echo esc_js(__('Preparing scan...', 'vfwp')); ?>');
				}
			}

			function bindBulkControls() {
				document.querySelectorAll('[data-vfwp-media-audit-bulk-form]').forEach(function (bulkForm) {
					if (bulkForm.dataset.vfwpMediaAuditBound === '1') {
						return;
					}

					bulkForm.dataset.vfwpMediaAuditBound = '1';
					var selectAll = bulkForm.querySelector('[data-vfwp-media-audit-select-all]');

					if (selectAll) {
						selectAll.addEventListener('change', function () {
							bulkForm.querySelectorAll('[data-vfwp-media-audit-checkbox]').forEach(function (checkbox) {
								checkbox.checked = selectAll.checked;
							});
						});
					}

					bulkForm.addEventListener('submit', function (event) {
						var selectedAction = bulkForm.querySelector('select[name="bulk_action"]');
						var selectedItems = bulkForm.querySelectorAll('[data-vfwp-media-audit-checkbox]:checked');

						if (!selectedAction || selectedAction.value !== 'delete') {
							event.preventDefault();
							window.alert('<?php echo esc_js(__('Choose "Delete permanently" before applying a bulk action.', 'vfwp')); ?>');
							return;
						}

						if (!selectedItems.length) {
							event.preventDefault();
							window.alert('<?php echo esc_js(__('Select at least one media item first.', 'vfwp')); ?>');
							return;
						}

						if (!window.confirm('<?php echo esc_js(__('Permanently delete selected media items? This removes the attachment records and files and cannot be restored from WordPress.', 'vfwp')); ?>')) {
							event.preventDefault();
							return;
						}

						setLoading(true);
					});
				});
			}

			function updateUrlFromData(data) {
				if (!window.history || !window.history.pushState) {
					return;
				}

				var params = new URLSearchParams();
				params.set('page', pageSlug);

				['attachment_mime', 'status_filter', 'safety_days', 'per_page', 'paged', 'upload_order', 'uploaded_from', 'uploaded_to'].forEach(function (key) {
					var value = data.get(key);
					if (value) {
						params.set(key, value);
					}
				});

				if (data.has('scan_duplicates')) {
					params.set('scan_duplicates', '1');

					if (data.has('refresh_duplicates')) {
						params.set('refresh_duplicates', '1');
					}
				} else if (data.has('media_audit_full_scan')) {
					params.set('media_audit_full_scan', '1');
				} else {
					params.set('media_audit_scan', '1');
				}

				window.history.pushState(null, '', window.location.pathname + '?' + params.toString());
			}

			function parseAjaxResponse(response) {
				return response.text()
					.then(function (text) {
						return {
							response: response,
							text: text
						};
					})
					.then(function (result) {
						var payload;

						try {
							payload = JSON.parse(result.text);
						} catch (error) {
							var details = '';
							var titleMatch = result.text.match(/<title[^>]*>([\s\S]*?)<\/title>/i);

							if (result.response && result.response.status) {
								details += ' HTTP status: ' + result.response.status + '.';
							}

							if (titleMatch && titleMatch[1]) {
								details += ' Page title: ' + titleMatch[1].replace(/\s+/g, ' ').trim() + '.';
							}

							throw new Error('<?php echo esc_js(__('The server returned HTML instead of JSON, usually because the request timed out, PHP hit a fatal error, the session expired, or a security layer blocked admin-ajax.php.', 'vfwp')); ?>' + details);
						}

						if (!result.response.ok && payload && payload.data && payload.data.message) {
							throw new Error(payload.data.message);
						}

						if (!result.response.ok) {
							throw new Error('<?php echo esc_js(__('The media scan request failed.', 'vfwp')); ?> HTTP status: ' + result.response.status + '.');
						}

						return payload;
					});
			}

			function runAjaxScan(data) {
				data.set('action', 'vfwp_intranet_media_audit_scan');
				data.set('nonce', nonce);
				setLoading(true);

				window.fetch(ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data
				})
					.then(parseAjaxResponse)
					.then(function (payload) {
						if (!payload || !payload.success || !payload.data || typeof payload.data.html !== 'string') {
							throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The media scan did not return usable results.', 'vfwp')); ?>');
						}

						results.innerHTML = payload.data.html;
						updateUrlFromData(data);
						bindBulkControls();
					})
					.catch(function (error) {
						var notice = document.createElement('div');
						var paragraph = document.createElement('p');

						notice.className = 'notice notice-error inline';
						paragraph.appendChild(document.createTextNode(error.message));
						notice.appendChild(paragraph);
						results.innerHTML = '';
						results.appendChild(notice);
					})
					.finally(function () {
						setLoading(false);
					});
			}

			function appendHiddenInput(parent, name, value) {
				var input = document.createElement('input');

				input.type = 'hidden';
				input.name = name;
				input.value = value || '';
				parent.appendChild(input);
			}

			function buildFullScanShell(data) {
				var bulkForm = document.createElement('form');
				var topNav = document.createElement('div');
				var table = document.createElement('table');
				var tbody = document.createElement('tbody');
				var bottomNav = document.createElement('div');

				bulkForm.method = 'post';
				bulkForm.action = adminPostUrl;
				bulkForm.setAttribute('data-vfwp-media-audit-bulk-form', '');

				appendHiddenInput(bulkForm, 'action', 'vfwp_intranet_media_audit_bulk_action');
				appendHiddenInput(bulkForm, 'page', pageSlug);
				appendHiddenInput(bulkForm, 'paged', '1');
				appendHiddenInput(bulkForm, 'per_page', data.get('per_page'));
				appendHiddenInput(bulkForm, 'safety_days', data.get('safety_days'));
				appendHiddenInput(bulkForm, 'attachment_mime', data.get('attachment_mime'));
				appendHiddenInput(bulkForm, 'status_filter', data.get('status_filter'));
				appendHiddenInput(bulkForm, 'upload_order', data.get('upload_order'));
				appendHiddenInput(bulkForm, 'uploaded_from', data.get('uploaded_from'));
				appendHiddenInput(bulkForm, 'uploaded_to', data.get('uploaded_to'));
				appendHiddenInput(bulkForm, '_wpnonce', nonce);

				topNav.className = 'tablenav top';
				topNav.innerHTML =
					'<div class="alignleft actions bulkactions">' +
						'<label for="vfwp-media-audit-full-bulk-action" class="screen-reader-text"><?php echo esc_js(__('Select bulk action', 'vfwp')); ?></label>' +
						'<select name="bulk_action" id="vfwp-media-audit-full-bulk-action">' +
							'<option value=""><?php echo esc_js(__('Bulk actions', 'vfwp')); ?></option>' +
							'<option value="delete"><?php echo esc_js(__('Delete permanently', 'vfwp')); ?></option>' +
						'</select> ' +
						'<button type="submit" class="button action"><?php echo esc_js(__('Apply', 'vfwp')); ?></button>' +
					'</div>';

				table.className = 'widefat striped';
				table.innerHTML =
					'<thead><tr>' +
						'<td class="manage-column column-cb check-column"><input type="checkbox" data-vfwp-media-audit-select-all></td>' +
						'<th><?php echo esc_js(__('Media', 'vfwp')); ?></th>' +
						'<th><?php echo esc_js(__('Uploaded', 'vfwp')); ?></th>' +
						'<th><?php echo esc_js(__('Size', 'vfwp')); ?></th>' +
						'<th><?php echo esc_js(__('Status', 'vfwp')); ?></th>' +
						'<th><?php echo esc_js(__('Evidence', 'vfwp')); ?></th>' +
					'</tr></thead>';
				table.appendChild(tbody);

				bottomNav.className = 'tablenav bottom';
				bottomNav.innerHTML =
					'<div class="alignleft actions bulkactions">' +
						'<button type="submit" class="button action"><?php echo esc_js(__('Apply bulk action', 'vfwp')); ?></button>' +
					'</div>';

				bulkForm.appendChild(topNav);
				bulkForm.appendChild(table);
				bulkForm.appendChild(bottomNav);

				results.innerHTML = '';
				results.appendChild(bulkForm);
				bindBulkControls();

				return tbody;
			}

			function appendFullScanNotice(message, type) {
				var notice = document.createElement('div');
				var paragraph = document.createElement('p');

				notice.className = 'notice notice-' + (type || 'info') + ' inline';
				paragraph.appendChild(document.createTextNode(message));
				notice.appendChild(paragraph);
				results.insertBefore(notice, results.firstChild);
			}

			function runFullScan(data) {
				var tbody = buildFullScanShell(data);

				data.delete('media_audit_scan');
				data.delete('scan_duplicates');
				data.delete('refresh_duplicates');
				data.set('action', 'vfwp_intranet_media_audit_full_scan_batch');
				data.set('nonce', nonce);
				data.set('paged', '1');
				setLoading(true, '<?php echo esc_js(__('Scanning media library...', 'vfwp')); ?>');
				setFullScanProgress(0, 0);
				updateUrlFromData(data);

				function scanNextBatch() {
					window.fetch(ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body: data
					})
						.then(parseAjaxResponse)
						.then(function (payload) {
							if (!payload || !payload.success || !payload.data) {
								throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The full media scan did not return usable results.', 'vfwp')); ?>');
							}

							if (typeof payload.data.rows_html === 'string' && payload.data.rows_html !== '') {
								tbody.insertAdjacentHTML('beforeend', payload.data.rows_html);
							}

							setFullScanProgress(payload.data.processed || 0, payload.data.total || 0);

							if (payload.data.done) {
								setLoading(false);

								if (!tbody.children.length) {
									tbody.innerHTML = '<tr><td colspan="6"><?php echo esc_js(__('No media found for this filter.', 'vfwp')); ?></td></tr>';
								}

								appendFullScanNotice('<?php echo esc_js(__('Media scan complete.', 'vfwp')); ?>', 'success');
								bindBulkControls();
								return;
							}

							data.set('paged', String(payload.data.next_paged || (parseInt(data.get('paged'), 10) + 1)));
							window.setTimeout(scanNextBatch, 120);
						})
						.catch(function (error) {
							setLoading(false);
							appendFullScanNotice(error.message, 'error');
						});
				}

				scanNextBatch();
			}

			function runDuplicateScan(data) {
				data.delete('media_audit_scan');
				data.delete('media_audit_full_scan');
				data.delete('refresh_duplicates');
				data.set('scan_duplicates', '1');
				data.set('reset_duplicate_scan', '1');
				data.set('action', 'vfwp_intranet_media_audit_duplicate_scan_batch');
				data.set('nonce', nonce);
				setLoading(true, '<?php echo esc_js(__('Finding exact duplicates...', 'vfwp')); ?>');
				setFullScanProgress(0, 0, '<?php echo esc_js(__('Preparing duplicate scan...', 'vfwp')); ?>');

				function scanNextDuplicateBatch() {
					window.fetch(ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body: data
					})
						.then(parseAjaxResponse)
						.then(function (payload) {
							if (!payload || !payload.success || !payload.data) {
								throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The duplicate scan did not return usable results.', 'vfwp')); ?>');
							}

							if (payload.data.token) {
								data.set('duplicate_token', payload.data.token);
								data.delete('reset_duplicate_scan');
							}

							if (loadingLabel && payload.data.message) {
								loadingLabel.textContent = payload.data.message;
							}

							setFullScanProgress(payload.data.processed || 0, payload.data.total || 0, payload.data.message || '');

							if (payload.data.done) {
								if (typeof payload.data.html !== 'string') {
									throw new Error('<?php echo esc_js(__('The duplicate scan finished without returning results.', 'vfwp')); ?>');
								}

								results.innerHTML = payload.data.html;
								setLoading(false);
								bindBulkControls();
								return;
							}

							window.setTimeout(scanNextDuplicateBatch, 120);
						})
						.catch(function (error) {
							var notice = document.createElement('div');
							var paragraph = document.createElement('p');

							setLoading(false);
							notice.className = 'notice notice-error inline';
							paragraph.appendChild(document.createTextNode(error.message));
							notice.appendChild(paragraph);
							results.innerHTML = '';
							results.appendChild(notice);
						});
				}

				scanNextDuplicateBatch();
			}

			form.addEventListener('submit', function (event) {
				if (!window.fetch || !window.FormData || !results) {
					setLoading(true);
					return;
				}

				event.preventDefault();

				var data = new FormData(form);
				var submitter = event.submitter;
				data.set('paged', '1');

				if (submitter && submitter.name) {
					data.set(submitter.name, submitter.value || '1');
				}

				if (submitter && submitter.name === 'media_audit_scan') {
					data.delete('media_audit_scan');
					data.set('media_audit_full_scan', '1');
					runFullScan(data);
					return;
				}

				if (submitter && submitter.name === 'scan_duplicates') {
					runDuplicateScan(data);
					return;
				}

				runAjaxScan(data);
			});

			document.addEventListener('click', function (event) {
				var pageLink = event.target.closest('[data-vfwp-media-audit-results] .tablenav-pages a');
				var trigger = event.target.closest('[data-vfwp-media-audit-loading-trigger]');

				if (!trigger && !pageLink) {
					return;
				}

				if (!window.fetch || !window.FormData || !results) {
					setLoading(true);
					return;
				}

				event.preventDefault();

				var url = new URL(trigger ? trigger.href : pageLink.href);
				var data = new FormData();
				url.searchParams.forEach(function (value, key) {
					data.set(key, value);
				});

				if (trigger) {
					data.set('scan_duplicates', '1');
					data.set('refresh_duplicates', '1');
					runDuplicateScan(data);
					return;
				} else {
					data.set('media_audit_scan', '1');
				}

				runAjaxScan(data);
			});

			bindBulkControls();
		});
	</script>
	<?php
	wp_reset_postdata();
}

function vfwp_intranet_media_audit_get_safety_days() {
	$safety_days = isset($_GET['safety_days']) ? (int) $_GET['safety_days'] : 90;
	return min(365, max(1, $safety_days));
}

function vfwp_intranet_media_audit_get_per_page_from_request($source) {
	$per_page = isset($source['per_page']) ? (int) wp_unslash($source['per_page']) : VFWP_INTRANET_MEDIA_AUDIT_DEFAULT_PER_PAGE;
	return min(VFWP_INTRANET_MEDIA_AUDIT_MAX_PER_PAGE, max(VFWP_INTRANET_MEDIA_AUDIT_MIN_PER_PAGE, $per_page));
}

function vfwp_intranet_media_audit_get_upload_order_from_request($source) {
	$order = isset($source['upload_order']) ? strtoupper(sanitize_key(wp_unslash($source['upload_order']))) : 'DESC';
	return in_array($order, array('ASC', 'DESC'), true) ? $order : 'DESC';
}

function vfwp_intranet_media_audit_get_status_filter_from_request($source) {
	$status = isset($source['status_filter']) ? sanitize_key(wp_unslash($source['status_filter'])) : '';
	return in_array($status, array('used', 'needs-review', 'candidate-unused'), true) ? $status : '';
}

function vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter) {
	return $status_filter === '' || (isset($report['status']) && $report['status'] === $status_filter);
}

function vfwp_intranet_media_audit_get_uploaded_date_from_request($source, $key) {
	if (empty($source[$key])) {
		return '';
	}

	$date = sanitize_text_field(wp_unslash($source[$key]));
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
		return '';
	}

	$parts = explode('-', $date);
	return checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0]) ? $date : '';
}

function vfwp_intranet_media_audit_get_uploaded_date_query($uploaded_from = '', $uploaded_to = '') {
	if ($uploaded_from === '' && $uploaded_to === '') {
		return array();
	}

	$date_filter = array(
		'column'    => 'post_date',
		'inclusive' => true,
	);

	if ($uploaded_from !== '') {
		$date_filter['after'] = $uploaded_from;
	}

	if ($uploaded_to !== '') {
		$date_filter['before'] = $uploaded_to;
	}

	return array($date_filter);
}

function vfwp_intranet_media_audit_get_attachment_query($paged, $per_page, $mime, $upload_order = 'DESC', $uploaded_from = '', $uploaded_to = '') {
	$args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'any',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => $upload_order,
	);

	if ($mime !== '') {
		$args['post_mime_type'] = $mime;
	}

	$date_query = vfwp_intranet_media_audit_get_uploaded_date_query($uploaded_from, $uploaded_to);
	if (!empty($date_query)) {
		$args['date_query'] = $date_query;
	}

	return new WP_Query($args);
}

function vfwp_intranet_media_audit_get_report_page($paged, $per_page, $mime, $safety_days, $upload_order = 'DESC', $uploaded_from = '', $uploaded_to = '', $status_filter = '') {
	$query = vfwp_intranet_media_audit_get_attachment_query($paged, $per_page, $mime, $upload_order, $uploaded_from, $uploaded_to);
	$reports = array();

	foreach ($query->posts as $attachment) {
		$report = vfwp_intranet_media_audit_build_attachment_report((int) $attachment->ID, $safety_days);
		if (vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter)) {
			$reports[] = $report;
		}
	}

	return array(
		'query' => $query,
		'reports' => $reports,
	);
}

function vfwp_intranet_media_audit_render_scan_results($reports, $query, $paged, $per_page, $safety_days, $mime, $upload_order = 'DESC', $uploaded_from = '', $uploaded_to = '', $status_filter = '') {
	?>
	<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" data-vfwp-media-audit-bulk-form>
		<input type="hidden" name="action" value="vfwp_intranet_media_audit_bulk_action">
		<input type="hidden" name="page" value="<?php echo esc_attr(VFWP_INTRANET_MEDIA_AUDIT_PAGE); ?>">
		<input type="hidden" name="paged" value="<?php echo esc_attr($paged); ?>">
		<input type="hidden" name="per_page" value="<?php echo esc_attr($per_page); ?>">
		<input type="hidden" name="safety_days" value="<?php echo esc_attr($safety_days); ?>">
		<input type="hidden" name="attachment_mime" value="<?php echo esc_attr($mime); ?>">
		<input type="hidden" name="status_filter" value="<?php echo esc_attr($status_filter); ?>">
		<input type="hidden" name="upload_order" value="<?php echo esc_attr($upload_order); ?>">
		<input type="hidden" name="uploaded_from" value="<?php echo esc_attr($uploaded_from); ?>">
		<input type="hidden" name="uploaded_to" value="<?php echo esc_attr($uploaded_to); ?>">
		<?php wp_nonce_field(VFWP_INTRANET_MEDIA_AUDIT_NONCE); ?>

		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<label for="vfwp-media-audit-bulk-action" class="screen-reader-text"><?php echo esc_html__('Select bulk action', 'vfwp'); ?></label>
				<select name="bulk_action" id="vfwp-media-audit-bulk-action">
					<option value=""><?php echo esc_html__('Bulk actions', 'vfwp'); ?></option>
					<option value="delete"><?php echo esc_html__('Delete permanently', 'vfwp'); ?></option>
				</select>
				<button type="submit" class="button action"><?php echo esc_html__('Apply', 'vfwp'); ?></button>
			</div>
		</div>

		<table class="widefat striped">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column">
						<input type="checkbox" data-vfwp-media-audit-select-all>
					</td>
					<th><?php echo esc_html__('Media', 'vfwp'); ?></th>
					<th><?php echo esc_html__('Uploaded', 'vfwp'); ?></th>
					<th><?php echo esc_html__('Size', 'vfwp'); ?></th>
					<th><?php echo esc_html__('Status', 'vfwp'); ?></th>
					<th><?php echo esc_html__('Evidence', 'vfwp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($reports)) : ?>
					<tr>
						<td colspan="6"><?php echo esc_html__('No media found for this filter.', 'vfwp'); ?></td>
					</tr>
				<?php endif; ?>

				<?php foreach ($reports as $report) : ?>
					<?php vfwp_intranet_media_audit_render_report_row($report); ?>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<button type="submit" class="button action"><?php echo esc_html__('Apply bulk action', 'vfwp'); ?></button>
			</div>
		</div>
	</form>
	<?php
}

function vfwp_intranet_media_audit_render_report_row($report, $group = null) {
	?>
	<tr>
		<th scope="row" class="check-column">
			<input type="checkbox" name="attachment_ids[]" value="<?php echo esc_attr($report['id']); ?>" data-vfwp-media-audit-checkbox>
		</th>
		<td>
			<strong>
				<a href="<?php echo esc_url(get_edit_post_link($report['id'])); ?>">
					<?php echo esc_html($report['title']); ?>
				</a>
			</strong>
			<?php if (is_array($group)) : ?>
				<div class="vfwp-media-audit__duplicate-meta">
					<?php echo esc_html__('Duplicate group', 'vfwp'); ?>
					<code><?php echo esc_html(substr($group['hash'], 0, 12)); ?></code>
					<span aria-hidden="true"> | </span>
					<?php echo esc_html__('Potential duplicate storage', 'vfwp'); ?>:
					<?php echo esc_html(size_format(max(0, count($group['items']) - 1) * (int) $group['size'])); ?>
				</div>
			<?php endif; ?>
			<br>
			<code><?php echo esc_html($report['relative_file']); ?></code>
			<br>
			<a href="<?php echo esc_url($report['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('Open file', 'vfwp'); ?></a>
			<span aria-hidden="true"> | </span>
			<a href="<?php echo esc_url(get_edit_post_link($report['id'])); ?>"><?php echo esc_html__('Edit media', 'vfwp'); ?></a>
		</td>
		<td><?php echo esc_html($report['uploaded']); ?></td>
		<td><?php echo esc_html($report['file_size_label']); ?></td>
		<td>
			<span class="vfwp-media-audit__status vfwp-media-audit__status--<?php echo esc_attr($report['status']); ?>">
				<?php echo esc_html($report['status_label']); ?>
			</span>
		</td>
		<td>
			<?php if (!empty($report['evidence'])) : ?>
				<ul class="vfwp-media-audit__evidence">
					<?php foreach ($report['evidence'] as $evidence) : ?>
						<li><?php vfwp_intranet_media_audit_render_evidence($evidence); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<?php echo esc_html__('No database, content, option, user, term, or theme reference found.', 'vfwp'); ?>
			<?php endif; ?>
		</td>
	</tr>
	<?php
}

function vfwp_intranet_media_audit_render_scan_pagination($query, $paged, $per_page, $safety_days, $mime, $upload_order = 'DESC', $uploaded_from = '', $uploaded_to = '', $status_filter = '') {
	$total_pages = $query instanceof WP_Query ? max(1, (int) $query->max_num_pages) : 1;

	if ($total_pages <= 1) {
		return;
	}

	$base_url = menu_page_url(VFWP_INTRANET_MEDIA_AUDIT_PAGE, false);
	echo '<div class="tablenav"><div class="tablenav-pages">';
	echo wp_kses_post(paginate_links(array(
		'base'      => add_query_arg(
			array(
				'paged'            => '%#%',
				'per_page'         => $per_page,
				'safety_days'      => $safety_days,
				'attachment_mime'  => $mime,
				'status_filter'    => $status_filter,
				'upload_order'     => $upload_order,
				'uploaded_from'    => $uploaded_from,
				'uploaded_to'      => $uploaded_to,
				'media_audit_scan' => 1,
			),
			$base_url
		),
		'format'    => '',
		'current'   => $paged,
		'total'     => $total_pages,
		'prev_text' => __('&laquo;', 'vfwp'),
		'next_text' => __('&raquo;', 'vfwp'),
	)));
	echo '</div></div>';
}

function vfwp_intranet_media_audit_get_redirect_args_from_request() {
	$source = !empty($_POST) ? $_POST : $_GET;

	return array(
		'page'            => VFWP_INTRANET_MEDIA_AUDIT_PAGE,
		'paged'           => isset($source['paged']) ? max(1, (int) $source['paged']) : 1,
		'per_page'        => vfwp_intranet_media_audit_get_per_page_from_request($source),
		'safety_days'     => isset($source['safety_days']) ? min(365, max(1, (int) $source['safety_days'])) : 90,
		'attachment_mime' => isset($source['attachment_mime']) ? sanitize_text_field(wp_unslash($source['attachment_mime'])) : '',
		'status_filter'   => vfwp_intranet_media_audit_get_status_filter_from_request($source),
		'upload_order'    => vfwp_intranet_media_audit_get_upload_order_from_request($source),
		'uploaded_from'   => vfwp_intranet_media_audit_get_uploaded_date_from_request($source, 'uploaded_from'),
		'uploaded_to'     => vfwp_intranet_media_audit_get_uploaded_date_from_request($source, 'uploaded_to'),
	);
}

function vfwp_intranet_media_audit_ajax_shutdown_handler() {
	if (!function_exists('wp_doing_ajax') || !wp_doing_ajax()) {
		return;
	}

	$action = isset($_REQUEST['action']) ? sanitize_key(wp_unslash($_REQUEST['action'])) : '';
	if (!in_array($action, array('vfwp_intranet_media_audit_scan', 'vfwp_intranet_media_audit_full_scan_batch', 'vfwp_intranet_media_audit_duplicate_scan_batch'), true)) {
		return;
	}

	$error = error_get_last();
	$fatal_types = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR);

	if (!$error || empty($error['type']) || !in_array($error['type'], $fatal_types, true) || headers_sent()) {
		return;
	}

	while (ob_get_level() > 0) {
		ob_end_clean();
	}

	wp_send_json_error(array(
		'message' => sprintf(
			__('The media scan stopped because PHP reported a fatal error: %1$s in %2$s on line %3$d. Try a smaller Rows per page value, then check the PHP error log.', 'vfwp'),
			isset($error['message']) ? $error['message'] : __('Unknown error', 'vfwp'),
			isset($error['file']) ? basename($error['file']) : __('unknown file', 'vfwp'),
			isset($error['line']) ? (int) $error['line'] : 0
		),
	), 500);
}

function vfwp_intranet_media_audit_render_admin_notices() {
	if (!empty($_GET['media_audit_deleted']) || !empty($_GET['media_audit_skipped']) || !empty($_GET['media_audit_failed'])) {
		$deleted = isset($_GET['media_audit_deleted']) ? (int) $_GET['media_audit_deleted'] : 0;
		$skipped = isset($_GET['media_audit_skipped']) ? (int) $_GET['media_audit_skipped'] : 0;
		$failed = isset($_GET['media_audit_failed']) ? (int) $_GET['media_audit_failed'] : 0;
		?>
		<div class="notice notice-success is-dismissible">
			<p>
				<?php
				echo esc_html(sprintf(
					__('Permanently deleted %1$d media item(s). Skipped: %2$d. Failed: %3$d.', 'vfwp'),
					$deleted,
					$skipped,
					$failed
				));
				?>
			</p>
		</div>
		<?php
	}

	if (empty($_GET['media_audit_notice'])) {
		return;
	}

	$notice = sanitize_key(wp_unslash($_GET['media_audit_notice']));
	$message = '';

	if ($notice === 'no_selection') {
		$message = __('No media items were selected.', 'vfwp');
	}

	if ($message === '') {
		return;
	}
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php echo esc_html($message); ?></p>
	</div>
	<?php
}

function vfwp_intranet_media_audit_render_reference_box() {
	?>
	<div class="vfwp-media-audit__reference">
		<h2><?php echo esc_html__('Status and Evidence Guide', 'vfwp'); ?></h2>

		<h3><?php echo esc_html__('Bulk Actions', 'vfwp'); ?></h3>
		<p><?php echo esc_html__('Delete permanently removes the selected attachment records and their files from the media library. This cannot be restored from WordPress.', 'vfwp'); ?></p>

		<h3><?php echo esc_html__('Statuses', 'vfwp'); ?></h3>
		<dl>
			<dt><?php echo esc_html__('Evidence found', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The scanner found at least one signal that the media item is used. Do not delete without reviewing the evidence links.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Needs review - recently uploaded', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('No usage evidence was found, but the file is inside the safety window. It may be part of work in progress, so it is not marked as unused yet.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Candidate unused', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('No usage evidence was found and the file is older than the safety window. Treat this as a review queue, not automatic proof that deletion is safe.', 'vfwp'); ?></dd>
		</dl>

		<h3><?php echo esc_html__('Evidence Types', 'vfwp'); ?></h3>
		<dl>
			<dt><?php echo esc_html__('Attached to {post type} #{ID}: {title}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment has a WordPress parent post. This is useful context, but it does not always prove the file is visible on the page.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Post meta {field} on #{ID}: {title}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment ID was found in a known media-related field, such as a featured image, ACF document upload, annex file, or similar post meta field. The title is linked to the public page.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Possible serialized post meta reference {field} on #{ID}: {title}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment ID was found inside serialized or JSON-like post meta that is not one of the known media fields. Review the linked page before deciding.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Content URL reference in {post type} #{ID}: {title}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL, upload path, or generated image-size URL appears directly in post content or excerpt. The title is linked to the public page.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Post meta URL reference {field} on #{ID}: {title}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL or upload path appears inside post meta or ACF data. The title is linked to the public page.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('User meta {field} on user #{ID}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment ID appears in user profile metadata, for example an avatar field.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('User meta URL reference {field} on user #{ID}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL or upload path appears in user profile metadata.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Term meta {field} on term #{ID}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment ID appears in taxonomy term metadata.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Term meta URL reference {field} on term #{ID}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL or upload path appears in taxonomy term metadata.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Option reference: {option name}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment ID appears in a persistent WordPress option. Transients and the people/team sync stats caches are ignored because they are not reliable page-usage signals.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Option URL reference: {option name}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL or upload path appears in a persistent WordPress option.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Theme file reference: {file path}', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The attachment URL or upload path is hard-coded somewhere in the active intranet theme files.', 'vfwp'); ?></dd>
		</dl>
	</div>
	<?php
}

function vfwp_intranet_media_audit_build_attachment_report($attachment_id, $safety_days) {
	$post = get_post($attachment_id);
	$file = get_attached_file($attachment_id);
	$url = wp_get_attachment_url($attachment_id);
	$relative_file = get_post_meta($attachment_id, '_wp_attached_file', true);
	$uploaded_time = $post ? strtotime($post->post_date_gmt . ' GMT') : false;
	$is_new = $uploaded_time ? $uploaded_time > strtotime('-' . (int) $safety_days . ' days') : true;
	$evidence = vfwp_intranet_media_audit_find_usage_evidence($attachment_id);

	if (!empty($evidence)) {
		$status = 'used';
		$status_label = __('Evidence found', 'vfwp');
	} elseif ($is_new) {
		$status = 'needs-review';
		$status_label = __('Needs review - recently uploaded', 'vfwp');
	} else {
		$status = 'candidate-unused';
		$status_label = __('Candidate unused', 'vfwp');
	}

	return array(
		'id'              => $attachment_id,
		'title'           => $post && $post->post_title !== '' ? $post->post_title : basename((string) $relative_file),
		'mime_type'       => get_post_mime_type($attachment_id),
		'uploaded'        => $post ? get_date_from_gmt($post->post_date_gmt, get_option('date_format')) : '',
		'uploaded_time'   => $uploaded_time ? (int) $uploaded_time : 0,
		'file_size_label' => $file && file_exists($file) ? size_format(filesize($file)) : __('Missing on disk', 'vfwp'),
		'relative_file'   => $relative_file,
		'url'             => is_string($url) ? $url : '',
		'status'          => $status,
		'status_label'    => $status_label,
		'evidence'        => $evidence,
	);
}

function vfwp_intranet_media_audit_find_usage_evidence($attachment_id) {
	$evidence = array();
	$post = get_post($attachment_id);

	if (!$post) {
		return $evidence;
	}

	if ((int) $post->post_parent > 0) {
		$parent = get_post((int) $post->post_parent);
		if ($parent && $parent->post_type !== 'attachment' && $parent->post_status !== 'trash') {
			$evidence[] = vfwp_intranet_media_audit_post_evidence(
				sprintf(__('Attached to %1$s #%2$d: ', 'vfwp'), $parent->post_type, $parent->ID),
				$parent->ID
			);
		}
	}

	$evidence = array_merge($evidence, vfwp_intranet_media_audit_find_id_references($attachment_id));
	$evidence = array_merge($evidence, vfwp_intranet_media_audit_find_url_references($attachment_id));
	$evidence = array_merge($evidence, vfwp_intranet_media_audit_find_theme_references($attachment_id));

	return vfwp_intranet_media_audit_unique_evidence($evidence);
}

function vfwp_intranet_media_audit_post_evidence($prefix, $post_id) {
	$title = get_the_title($post_id);
	if ($title === '') {
		$title = sprintf(__('Post #%d', 'vfwp'), (int) $post_id);
	}

	$permalink = get_permalink($post_id);

	return array(
		'label' => $prefix . $title,
		'prefix' => $prefix,
		'title' => $title,
		'url'   => is_string($permalink) ? $permalink : '',
	);
}

function vfwp_intranet_media_audit_render_evidence($evidence) {
	if (!empty($evidence['title']) && !empty($evidence['url'])) {
		echo esc_html($evidence['prefix']);
		echo '<a href="' . esc_url($evidence['url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($evidence['title']) . '</a>';
		return;
	}

	echo esc_html($evidence['label']);
}

function vfwp_intranet_media_audit_find_id_references($attachment_id) {
	global $wpdb;

	$evidence = array();
	$id = (string) $attachment_id;
	$serialized_int = '%i:' . $wpdb->esc_like($id) . ';%';
	$serialized_string = '%:"' . $wpdb->esc_like($id) . '";%';
	$json_id = '%"id":' . $wpdb->esc_like($id) . '%';

	$postmeta_rows = $wpdb->get_results($wpdb->prepare(
		"SELECT post_id, meta_key
		FROM {$wpdb->postmeta}
		WHERE post_id <> %d
			AND (
				meta_key = '_thumbnail_id'
				OR meta_key = 'upload_file'
				OR meta_key = 'file'
				OR meta_key = 'vf_wp_avatar_image'
				OR meta_key LIKE 'annexes\\_%%\\_file'
			)
			AND (
				meta_value = %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
			)
		LIMIT 20",
		$attachment_id,
		$id,
		$serialized_int,
		$serialized_string,
		$json_id
	));

	foreach ($postmeta_rows as $row) {
		$meta_label = $row->meta_key === '_thumbnail_id' ? __('featured image', 'vfwp') : $row->meta_key;
		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			sprintf(__('Post meta %1$s on #%2$d: ', 'vfwp'), $meta_label, (int) $row->post_id),
			(int) $row->post_id
		);
	}

	$generic_rows = $wpdb->get_results($wpdb->prepare(
		"SELECT post_id, meta_key
		FROM {$wpdb->postmeta}
		WHERE post_id <> %d
			AND meta_key NOT IN ('_wp_attachment_metadata', '_wp_attached_file')
			AND (
				meta_value LIKE %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
			)
		LIMIT 10",
		$attachment_id,
		$serialized_int,
		$serialized_string,
		$json_id
	));

	foreach ($generic_rows as $row) {
		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			sprintf(__('Possible serialized post meta reference %1$s on #%2$d: ', 'vfwp'), $row->meta_key, (int) $row->post_id),
			(int) $row->post_id
		);
	}

	$usermeta_rows = $wpdb->get_results($wpdb->prepare(
		"SELECT user_id, meta_key
		FROM {$wpdb->usermeta}
		WHERE (
				meta_key = 'vf_wp_avatar_image'
				OR meta_value = %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
			)
			AND (
				meta_value = %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
				OR meta_value LIKE %s
			)
		LIMIT 20",
		$id,
		$serialized_int,
		$serialized_string,
		$json_id,
		$id,
		$serialized_int,
		$serialized_string,
		$json_id
	));

	foreach ($usermeta_rows as $row) {
		$user = get_userdata((int) $row->user_id);
		$evidence[] = array(
			'label' => sprintf(__('User meta %1$s on user #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->user_id, $user ? ': ' . $user->display_name : ''),
		);
	}

	$termmeta_rows = $wpdb->get_results($wpdb->prepare(
		"SELECT term_id, meta_key
		FROM {$wpdb->termmeta}
		WHERE meta_value = %s
			OR meta_value LIKE %s
			OR meta_value LIKE %s
			OR meta_value LIKE %s
		LIMIT 10",
		$id,
		$serialized_int,
		$serialized_string,
		$json_id
	));

	foreach ($termmeta_rows as $row) {
		$term = get_term((int) $row->term_id);
		$evidence[] = array(
			'label' => sprintf(__('Term meta %1$s on term #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->term_id, $term && !is_wp_error($term) ? ': ' . $term->name : ''),
		);
	}

	$option_rows = $wpdb->get_results($wpdb->prepare(
		"SELECT option_name
		FROM {$wpdb->options}
		WHERE option_name NOT LIKE '\\_transient\\_%%'
			AND option_name NOT LIKE '\\_site\\_transient\\_%%'
			AND option_name NOT IN ('vfwp_people_sync_stats', 'vfwp_teams_sync_stats')
			AND (
				option_value = %s
				OR option_value LIKE %s
				OR option_value LIKE %s
				OR option_value LIKE %s
			)
		LIMIT 20",
		$id,
		$serialized_int,
		$serialized_string,
		$json_id
	));

	foreach ($option_rows as $row) {
		$evidence[] = array(
			'label' => sprintf(__('Option reference: %s', 'vfwp'), $row->option_name),
		);
	}

	return $evidence;
}

function vfwp_intranet_media_audit_find_url_references($attachment_id) {
	global $wpdb;

	$evidence = array();
	$needles = vfwp_intranet_media_audit_get_attachment_url_needles($attachment_id);

	if (empty($needles)) {
		return $evidence;
	}

	$likes = array();
	foreach ($needles as $needle) {
		$likes[] = '%' . $wpdb->esc_like($needle) . '%';
	}

	$post_conditions = array();
	$post_params = array($attachment_id);
	foreach ($likes as $like) {
		$post_conditions[] = '(post_content LIKE %s OR post_excerpt LIKE %s)';
		$post_params[] = $like;
		$post_params[] = $like;
	}

	$post_rows = $wpdb->get_results(vfwp_intranet_media_audit_prepare_sql(
		"SELECT ID, post_type, post_title
		FROM {$wpdb->posts}
		WHERE ID <> %d
			AND post_type <> 'attachment'
			AND post_status <> 'trash'
			AND (" . implode(' OR ', $post_conditions) . ")
		LIMIT 20",
		$post_params
	));

	foreach ($post_rows as $row) {
		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			sprintf(__('Content URL reference in %1$s #%2$d: ', 'vfwp'), $row->post_type, (int) $row->ID),
			(int) $row->ID
		);
	}

	$value_conditions = implode(' OR ', array_fill(0, count($likes), 'meta_value LIKE %s'));

	$postmeta_rows = $wpdb->get_results(vfwp_intranet_media_audit_prepare_sql(
		"SELECT post_id, meta_key
		FROM {$wpdb->postmeta}
		WHERE post_id <> %d
			AND meta_key NOT IN ('_wp_attachment_metadata', '_wp_attached_file')
			AND (" . $value_conditions . ")
		LIMIT 20",
		array_merge(array($attachment_id), $likes)
	));

	foreach ($postmeta_rows as $row) {
		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			sprintf(__('Post meta URL reference %1$s on #%2$d: ', 'vfwp'), $row->meta_key, (int) $row->post_id),
			(int) $row->post_id
		);
	}

	$usermeta_rows = $wpdb->get_results(vfwp_intranet_media_audit_prepare_sql(
		"SELECT user_id, meta_key
		FROM {$wpdb->usermeta}
		WHERE " . $value_conditions . "
		LIMIT 20",
		$likes
	));

	foreach ($usermeta_rows as $row) {
		$user = get_userdata((int) $row->user_id);
		$evidence[] = array(
			'label' => sprintf(__('User meta URL reference %1$s on user #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->user_id, $user ? ': ' . $user->display_name : ''),
		);
	}

	$termmeta_rows = $wpdb->get_results(vfwp_intranet_media_audit_prepare_sql(
		"SELECT term_id, meta_key
		FROM {$wpdb->termmeta}
		WHERE " . $value_conditions . "
		LIMIT 20",
		$likes
	));

	foreach ($termmeta_rows as $row) {
		$term = get_term((int) $row->term_id);
		$evidence[] = array(
			'label' => sprintf(__('Term meta URL reference %1$s on term #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->term_id, $term && !is_wp_error($term) ? ': ' . $term->name : ''),
		);
	}

	$option_conditions = implode(' OR ', array_fill(0, count($likes), 'option_value LIKE %s'));
	$option_rows = $wpdb->get_results(vfwp_intranet_media_audit_prepare_sql(
		"SELECT option_name
		FROM {$wpdb->options}
		WHERE option_name NOT LIKE '\\_transient\\_%%'
			AND option_name NOT LIKE '\\_site\\_transient\\_%%'
			AND option_name NOT IN ('vfwp_people_sync_stats', 'vfwp_teams_sync_stats')
			AND (" . $option_conditions . ")
		LIMIT 20",
		$likes
	));

	foreach ($option_rows as $row) {
		$evidence[] = array(
			'label' => sprintf(__('Option URL reference: %s', 'vfwp'), $row->option_name),
		);
	}

	return $evidence;
}

function vfwp_intranet_media_audit_prepare_sql($query, $args) {
	global $wpdb;

	return call_user_func_array(array($wpdb, 'prepare'), array_merge(array($query), $args));
}

function vfwp_intranet_media_audit_find_theme_references($attachment_id) {
	$evidence = array();
	$needles = vfwp_intranet_media_audit_get_attachment_url_needles($attachment_id);
	static $theme_files = null;

	if (empty($needles)) {
		return $evidence;
	}

	if ($theme_files === null) {
		$theme_files = array();
		$theme_dir = get_stylesheet_directory();
		$extensions = array('php', 'css', 'js', 'json', 'html');

		try {
			$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($theme_dir, FilesystemIterator::SKIP_DOTS));
		} catch (Exception $exception) {
			return $evidence;
		}

		foreach ($iterator as $file) {
			if (!$file->isFile()) {
				continue;
			}

			$extension = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
			if (!in_array($extension, $extensions, true) || $file->getSize() > 1048576) {
				continue;
			}

			$contents = file_get_contents($file->getPathname());
			if (!is_string($contents) || $contents === '') {
				continue;
			}

			$theme_files[] = array(
				'relative' => str_replace(trailingslashit($theme_dir), '', $file->getPathname()),
				'contents' => $contents,
			);
		}
	}

	foreach ($theme_files as $theme_file) {
		foreach ($needles as $needle) {
			if (strpos($theme_file['contents'], $needle) === false) {
				continue;
			}

			$evidence[] = array(
				'label' => sprintf(__('Theme file reference: %s', 'vfwp'), $theme_file['relative']),
			);
			break;
		}
	}

	return $evidence;
}

function vfwp_intranet_media_audit_get_attachment_url_needles($attachment_id) {
	$needles = array();
	$url = wp_get_attachment_url($attachment_id);
	$relative_file = get_post_meta($attachment_id, '_wp_attached_file', true);
	$uploads = wp_get_upload_dir();

	if (is_string($url) && $url !== '') {
		$needles[] = $url;
		$path = wp_parse_url($url, PHP_URL_PATH);
		if (is_string($path) && $path !== '') {
			$needles[] = $path;
		}
	}

	if (is_string($relative_file) && $relative_file !== '') {
		$needles[] = $relative_file;

		if (!empty($uploads['baseurl'])) {
			$needles[] = trailingslashit($uploads['baseurl']) . ltrim($relative_file, '/');
		}

		$upload_path = wp_parse_url($uploads['baseurl'], PHP_URL_PATH);
		if (is_string($upload_path) && $upload_path !== '') {
			$needles[] = trailingslashit($upload_path) . ltrim($relative_file, '/');
		}
	}

	$metadata = wp_get_attachment_metadata($attachment_id);
	if (is_array($metadata) && !empty($metadata['sizes']) && is_array($metadata['sizes']) && is_string($relative_file)) {
		$directory = trailingslashit(dirname($relative_file));
		foreach ($metadata['sizes'] as $size_data) {
			if (empty($size_data['file'])) {
				continue;
			}
			$size_relative = $directory . $size_data['file'];
			$needles[] = $size_relative;
			if (!empty($uploads['baseurl'])) {
				$needles[] = trailingslashit($uploads['baseurl']) . ltrim($size_relative, '/');
			}
			$upload_path = wp_parse_url($uploads['baseurl'], PHP_URL_PATH);
			if (is_string($upload_path) && $upload_path !== '') {
				$needles[] = trailingslashit($upload_path) . ltrim($size_relative, '/');
			}
		}
	}

	return array_values(array_unique(array_filter($needles)));
}

function vfwp_intranet_media_audit_unique_evidence($evidence) {
	$seen = array();
	$unique = array();

	foreach ($evidence as $item) {
		if (empty($item['label']) || isset($seen[$item['label']])) {
			continue;
		}
		$seen[$item['label']] = true;
		$unique[] = $item;
	}

	return $unique;
}

function vfwp_intranet_media_audit_get_duplicate_scan_transient_key($token) {
	return 'vfwp_media_audit_dup_scan_' . get_current_user_id() . '_' . sanitize_key($token);
}

function vfwp_intranet_media_audit_create_duplicate_scan_state($mime, $upload_order, $uploaded_from, $uploaded_to) {
	return array(
		'stage' => 'sizes',
		'mime' => $mime,
		'upload_order' => $upload_order,
		'uploaded_from' => $uploaded_from,
		'uploaded_to' => $uploaded_to,
		'size_paged' => 1,
		'size_processed' => 0,
		'size_total' => 0,
		'size_groups' => array(),
		'hash_index' => 0,
		'hash_total' => 0,
		'hash_queue' => array(),
		'hashes' => array(),
	);
}

function vfwp_intranet_media_audit_get_duplicate_scan_state($token) {
	return get_transient(vfwp_intranet_media_audit_get_duplicate_scan_transient_key($token));
}

function vfwp_intranet_media_audit_set_duplicate_scan_state($token, $state) {
	set_transient(vfwp_intranet_media_audit_get_duplicate_scan_transient_key($token), $state, HOUR_IN_SECONDS);
}

function vfwp_intranet_media_audit_delete_duplicate_scan_state($token) {
	delete_transient(vfwp_intranet_media_audit_get_duplicate_scan_transient_key($token));
}

function vfwp_intranet_media_audit_process_duplicate_size_batch($state) {
	$query = vfwp_intranet_media_audit_get_attachment_query(
		(int) $state['size_paged'],
		VFWP_INTRANET_MEDIA_AUDIT_DUPLICATE_SIZE_BATCH_SIZE,
		$state['mime'],
		$state['upload_order'],
		$state['uploaded_from'],
		$state['uploaded_to']
	);

	$state['size_total'] = max(0, (int) $query->found_posts);

	foreach ($query->posts as $attachment) {
		$attachment_id = (int) $attachment->ID;

		if ($attachment->post_status === 'trash') {
			continue;
		}

		$file_path = get_attached_file($attachment_id);
		if (!$file_path || !file_exists($file_path) || !is_readable($file_path)) {
			continue;
		}

		$size = filesize($file_path);
		if ($size === false) {
			continue;
		}

		$size_key = (string) $size;
		if (!isset($state['size_groups'][$size_key])) {
			$state['size_groups'][$size_key] = array();
		}

		$state['size_groups'][$size_key][] = array(
			'id' => $attachment_id,
			'title' => get_the_title($attachment_id),
			'file' => get_post_meta($attachment_id, '_wp_attached_file', true),
		);
	}

	$state['size_processed'] = min($state['size_total'], (int) $state['size_paged'] * VFWP_INTRANET_MEDIA_AUDIT_DUPLICATE_SIZE_BATCH_SIZE);

	if ($state['size_total'] === 0 || empty($query->posts) || $state['size_processed'] >= $state['size_total']) {
		$state['hash_queue'] = vfwp_intranet_media_audit_build_duplicate_hash_queue($state['size_groups']);
		$state['hash_total'] = count($state['hash_queue']);
		$state['hash_index'] = 0;
		$state['stage'] = $state['hash_total'] > 0 ? 'hashes' : 'done';
		return $state;
	}

	$state['size_paged'] = (int) $state['size_paged'] + 1;
	return $state;
}

function vfwp_intranet_media_audit_build_duplicate_hash_queue($size_groups) {
	$queue = array();

	foreach ($size_groups as $size => $items) {
		if (!is_array($items) || count($items) < 2) {
			continue;
		}

		foreach ($items as $item) {
			$item['size'] = (int) $size;
			$queue[] = $item;
		}
	}

	return $queue;
}

function vfwp_intranet_media_audit_process_duplicate_hash_batch($state) {
	$limit = VFWP_INTRANET_MEDIA_AUDIT_DUPLICATE_HASH_BATCH_SIZE;
	$processed = 0;

	while ($processed < $limit && (int) $state['hash_index'] < (int) $state['hash_total']) {
		$item = $state['hash_queue'][(int) $state['hash_index']];
		$attachment_id = isset($item['id']) ? (int) $item['id'] : 0;
		$file_path = $attachment_id > 0 ? get_attached_file($attachment_id) : '';
		$attachment = $attachment_id > 0 ? get_post($attachment_id) : null;

		if ($attachment && $attachment->post_type === 'attachment' && $attachment->post_status !== 'trash' && $file_path && file_exists($file_path) && is_readable($file_path)) {
			$hash = sha1_file($file_path);
			if (is_string($hash) && $hash !== '') {
				if (!isset($state['hashes'][$hash])) {
					$state['hashes'][$hash] = array(
						'hash' => $hash,
						'size' => isset($item['size']) ? (int) $item['size'] : filesize($file_path),
						'items' => array(),
					);
				}

				unset($item['size']);
				$state['hashes'][$hash]['items'][] = $item;
			}
		}

		$state['hash_index'] = (int) $state['hash_index'] + 1;
		$processed++;
	}

	if ((int) $state['hash_index'] >= (int) $state['hash_total']) {
		$state['stage'] = 'done';
	}

	return $state;
}

function vfwp_intranet_media_audit_get_duplicate_scan_response($token, $state) {
	if ($state['stage'] === 'sizes') {
		return array(
			'token' => $token,
			'stage' => 'sizes',
			'processed' => (int) $state['size_processed'],
			'total' => (int) $state['size_total'],
			'done' => false,
			'message' => __('Checking file sizes...', 'vfwp'),
		);
	}

	if ($state['stage'] === 'hashes') {
		return array(
			'token' => $token,
			'stage' => 'hashes',
			'processed' => (int) $state['hash_index'],
			'total' => (int) $state['hash_total'],
			'done' => false,
			'message' => __('Comparing duplicate candidates...', 'vfwp'),
		);
	}

	$duplicate_groups = vfwp_intranet_media_audit_build_duplicate_groups_from_hashes($state['hashes']);
	$has_filter = $state['mime'] !== '' || $state['uploaded_from'] !== '' || $state['uploaded_to'] !== '';

	if (!$has_filter) {
		set_transient('vfwp_intranet_media_audit_duplicate_groups', $duplicate_groups, HOUR_IN_SECONDS * 6);
	}

	return array(
		'token' => $token,
		'stage' => 'done',
		'processed' => (int) $state['hash_total'],
		'total' => (int) $state['hash_total'],
		'done' => true,
		'message' => __('Duplicate scan complete.', 'vfwp'),
		'duplicate_groups' => $duplicate_groups,
	);
}

function vfwp_intranet_media_audit_build_duplicate_groups_from_hashes($hashes) {
	$groups = array();

	foreach ($hashes as $group) {
		if (!empty($group['items']) && is_array($group['items']) && count($group['items']) > 1) {
			$groups[] = $group;
		}
	}

	usort($groups, 'vfwp_intranet_media_audit_sort_duplicate_groups');
	return $groups;
}

function vfwp_intranet_media_audit_get_duplicate_groups($refresh, $uploaded_from = '', $uploaded_to = '', $mime = '') {
	$has_date_filter = $uploaded_from !== '' || $uploaded_to !== '';
	$has_filter = $has_date_filter || $mime !== '';

	if (!$refresh && !$has_filter) {
		$cached = get_transient('vfwp_intranet_media_audit_duplicate_groups');
		if (is_array($cached)) {
			return vfwp_intranet_media_audit_filter_duplicate_groups($cached);
		}
	}

	if (function_exists('set_time_limit')) {
		set_time_limit(120);
	}

	$uploads = wp_get_upload_dir();
	$attachment_args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);

	if ($mime !== '') {
		$attachment_args['post_mime_type'] = $mime;
	}

	$date_query = vfwp_intranet_media_audit_get_uploaded_date_query($uploaded_from, $uploaded_to);
	if (!empty($date_query)) {
		$attachment_args['date_query'] = $date_query;
	}

	$attachment_ids = get_posts($attachment_args);

	$size_groups = array();
	foreach ($attachment_ids as $attachment_id) {
		$attachment = get_post((int) $attachment_id);
		if (!$attachment || $attachment->post_type !== 'attachment' || $attachment->post_status === 'trash') {
			continue;
		}

		$relative_file = get_post_meta((int) $attachment_id, '_wp_attached_file', true);
		if (!is_string($relative_file) || $relative_file === '') {
			continue;
		}

		$file_path = trailingslashit($uploads['basedir']) . ltrim($relative_file, '/');
		if (!file_exists($file_path) || !is_readable($file_path)) {
			continue;
		}

		$size = filesize($file_path);
		if (!isset($size_groups[$size])) {
			$size_groups[$size] = array();
		}

		$size_groups[$size][] = array(
			'id' => (int) $attachment_id,
			'title' => get_the_title((int) $attachment_id),
			'file' => $relative_file,
			'path' => $file_path,
		);
	}

	$hashes = array();
	foreach ($size_groups as $size => $items) {
		if (count($items) < 2) {
			continue;
		}

		foreach ($items as $item) {
			$file_path = $item['path'];
			unset($item['path']);

			$hash = sha1_file($file_path);
			if (!is_string($hash) || $hash === '') {
				continue;
			}

			if (!isset($hashes[$hash])) {
				$hashes[$hash] = array(
					'hash' => $hash,
					'size' => $size,
					'items' => array(),
				);
			}

			$hashes[$hash]['items'][] = $item;
		}
	}

	$groups = array();
	foreach ($hashes as $group) {
		if (count($group['items']) > 1) {
			$groups[] = $group;
		}
	}

	usort($groups, 'vfwp_intranet_media_audit_sort_duplicate_groups');
	if (!$has_filter) {
		set_transient('vfwp_intranet_media_audit_duplicate_groups', $groups, HOUR_IN_SECONDS * 6);
	}

	return $groups;
}

function vfwp_intranet_media_audit_filter_duplicate_groups($groups) {
	$filtered_groups = array();

	foreach ($groups as $group) {
		if (empty($group['items']) || !is_array($group['items'])) {
			continue;
		}

		$items = array();
		foreach ($group['items'] as $item) {
			$attachment_id = isset($item['id']) ? (int) $item['id'] : 0;
			$attachment = $attachment_id > 0 ? get_post($attachment_id) : null;

			if (!$attachment || $attachment->post_type !== 'attachment' || $attachment->post_status === 'trash') {
				continue;
			}

			$file = get_attached_file($attachment_id);
			if (!$file || !file_exists($file) || !is_readable($file)) {
				continue;
			}

			$items[] = array(
				'id'    => $attachment_id,
				'title' => get_the_title($attachment_id),
				'file'  => get_post_meta($attachment_id, '_wp_attached_file', true),
			);
		}

		if (count($items) < 2) {
			continue;
		}

		$group['items'] = $items;
		$filtered_groups[] = $group;
	}

	return $filtered_groups;
}

function vfwp_intranet_media_audit_sort_duplicate_groups($a, $b) {
	$a_waste = max(0, count($a['items']) - 1) * (int) $a['size'];
	$b_waste = max(0, count($b['items']) - 1) * (int) $b['size'];

	if ($a_waste === $b_waste) {
		return 0;
	}

	return $a_waste > $b_waste ? -1 : 1;
}

function vfwp_intranet_media_audit_render_duplicate_groups($duplicate_groups, $safety_days = 90, $upload_order = 'DESC', $uploaded_from = '', $uploaded_to = '', $mime = '', $status_filter = '') {
	$duplicate_groups = vfwp_intranet_media_audit_filter_duplicate_groups($duplicate_groups);
	$base_url = menu_page_url(VFWP_INTRANET_MEDIA_AUDIT_PAGE, false);
	$refresh_url = add_query_arg(array(
		'scan_duplicates'    => 1,
		'refresh_duplicates' => 1,
		'safety_days'        => $safety_days,
		'attachment_mime'    => $mime,
		'status_filter'      => $status_filter,
		'upload_order'       => $upload_order,
		'uploaded_from'      => $uploaded_from,
		'uploaded_to'        => $uploaded_to,
	), $base_url);
	?>
	<div class="vfwp-media-audit__duplicates">
		<h2><?php echo esc_html__('Exact Duplicate Files', 'vfwp'); ?></h2>
		<p>
			<?php echo esc_html__('Duplicates are grouped only when the original uploaded files have the same SHA-1 hash. Review usage evidence before removing any duplicate.', 'vfwp'); ?>
			<a class="button button-small" href="<?php echo esc_url($refresh_url); ?>" data-vfwp-media-audit-loading-trigger><?php echo esc_html__('Refresh duplicate scan', 'vfwp'); ?></a>
		</p>

		<?php if (empty($duplicate_groups)) : ?>
			<p><?php echo esc_html__('No exact duplicate files found.', 'vfwp'); ?></p>
			<?php return; ?>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" data-vfwp-media-audit-bulk-form>
			<input type="hidden" name="action" value="vfwp_intranet_media_audit_bulk_action">
			<input type="hidden" name="page" value="<?php echo esc_attr(VFWP_INTRANET_MEDIA_AUDIT_PAGE); ?>">
			<input type="hidden" name="safety_days" value="<?php echo esc_attr($safety_days); ?>">
			<input type="hidden" name="attachment_mime" value="<?php echo esc_attr($mime); ?>">
			<input type="hidden" name="status_filter" value="<?php echo esc_attr($status_filter); ?>">
			<input type="hidden" name="upload_order" value="<?php echo esc_attr($upload_order); ?>">
			<input type="hidden" name="uploaded_from" value="<?php echo esc_attr($uploaded_from); ?>">
			<input type="hidden" name="uploaded_to" value="<?php echo esc_attr($uploaded_to); ?>">
			<input type="hidden" name="scan_duplicates" value="1">
			<?php wp_nonce_field(VFWP_INTRANET_MEDIA_AUDIT_NONCE); ?>

			<div class="tablenav top">
				<div class="alignleft actions bulkactions">
					<label for="vfwp-media-audit-duplicate-bulk-action" class="screen-reader-text"><?php echo esc_html__('Select bulk action', 'vfwp'); ?></label>
					<select name="bulk_action" id="vfwp-media-audit-duplicate-bulk-action">
						<option value=""><?php echo esc_html__('Bulk actions', 'vfwp'); ?></option>
						<option value="delete"><?php echo esc_html__('Delete permanently', 'vfwp'); ?></option>
					</select>
					<button type="submit" class="button action"><?php echo esc_html__('Apply', 'vfwp'); ?></button>
				</div>
			</div>

			<table class="widefat striped">
				<thead>
					<tr>
						<td class="manage-column column-cb check-column">
							<input type="checkbox" data-vfwp-media-audit-select-all>
						</td>
						<th><?php echo esc_html__('Media', 'vfwp'); ?></th>
						<th><?php echo esc_html__('Uploaded', 'vfwp'); ?></th>
						<th><?php echo esc_html__('Size', 'vfwp'); ?></th>
						<th><?php echo esc_html__('Status', 'vfwp'); ?></th>
						<th><?php echo esc_html__('Evidence', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rows = array();
					foreach (array_slice($duplicate_groups, 0, 20) as $group) {
						foreach ($group['items'] as $item) {
							$report = vfwp_intranet_media_audit_build_attachment_report($item['id'], $safety_days);
							if (!vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter)) {
								continue;
							}

							$rows[] = array(
								'report' => $report,
								'group'  => $group,
							);
						}
					}

					usort($rows, function ($a, $b) use ($upload_order) {
						$a_time = isset($a['report']['uploaded_time']) ? (int) $a['report']['uploaded_time'] : 0;
						$b_time = isset($b['report']['uploaded_time']) ? (int) $b['report']['uploaded_time'] : 0;

						if ($a_time === $b_time) {
							return 0;
						}

						if ($upload_order === 'ASC') {
							return $a_time < $b_time ? -1 : 1;
						}

						return $a_time > $b_time ? -1 : 1;
					});
					?>

					<?php if (empty($rows)) : ?>
						<tr>
							<td colspan="6"><?php echo esc_html__('No exact duplicate files found for this status filter.', 'vfwp'); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ($rows as $row) : ?>
							<?php vfwp_intranet_media_audit_render_report_row($row['report'], $row['group']); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
					<button type="submit" class="button action"><?php echo esc_html__('Apply bulk action', 'vfwp'); ?></button>
				</div>
			</div>
		</form>
	</div>
	<?php
}
