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
define('VFWP_INTRANET_MEDIA_AUDIT_EXPORT_BATCH_SIZE', 100);
define('VFWP_INTRANET_MEDIA_AUDIT_DEEP_EVIDENCE_LIMIT', 20);
define('VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE', 500);
define('VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNKS_PER_REQUEST', 4);

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

	$filters = vfwp_intranet_media_audit_get_scan_filters_from_request($_GET);
	$safety_days = $filters['safety_days'];
	$mime = $filters['mime'];
	$upload_order = $filters['upload_order'];
	$uploaded_from = $filters['uploaded_from'];
	$uploaded_to = $filters['uploaded_to'];
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
		'posts_per_page' => VFWP_INTRANET_MEDIA_AUDIT_EXPORT_BATCH_SIZE,
		'fields'         => 'ids',
		'orderby'        => 'date',
		'order'          => $upload_order,
		'no_found_rows'  => true,
	);

	if ($mime !== '') {
		$attachment_args['post_mime_type'] = $mime;
	}

	$date_query = vfwp_intranet_media_audit_get_uploaded_date_query($uploaded_from, $uploaded_to);
	if (!empty($date_query)) {
		$attachment_args['date_query'] = $date_query;
	}

	$paged = 1;
	do {
		$attachment_args['paged'] = $paged;
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

		$paged++;
	} while (count($attachment_ids) === VFWP_INTRANET_MEDIA_AUDIT_EXPORT_BATCH_SIZE);

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
	$redirect_args['media_audit_scan'] = 1;

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
	wp_safe_redirect(add_query_arg($redirect_args, admin_url('tools.php')));
	exit;
}
add_action('admin_post_vfwp_intranet_media_audit_bulk_action', 'vfwp_intranet_media_audit_bulk_action');

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

	$filters = vfwp_intranet_media_audit_get_scan_filters_from_request($_POST);
	$paged = $filters['paged'];
	$per_page = $filters['per_page'];
	$safety_days = $filters['safety_days'];
	$mime = $filters['mime'];
	$status_filter = $filters['status_filter'];
	$upload_order = $filters['upload_order'];
	$uploaded_from = $filters['uploaded_from'];
	$uploaded_to = $filters['uploaded_to'];

	register_shutdown_function('vfwp_intranet_media_audit_ajax_shutdown_handler');

	ob_start();

	$scan = vfwp_intranet_media_audit_get_report_page($paged, $per_page, $mime, $safety_days, $upload_order, $uploaded_from, $uploaded_to);
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

	$filters = vfwp_intranet_media_audit_get_scan_filters_from_request($_POST);
	$paged = $filters['paged'];
	$batch_size = VFWP_INTRANET_MEDIA_AUDIT_FULL_SCAN_BATCH_SIZE;
	$safety_days = $filters['safety_days'];
	$mime = $filters['mime'];
	$upload_order = $filters['upload_order'];
	$uploaded_from = $filters['uploaded_from'];
	$uploaded_to = $filters['uploaded_to'];
	$query = vfwp_intranet_media_audit_get_attachment_query($paged, $batch_size, $mime, $upload_order, $uploaded_from, $uploaded_to);

	ob_start();
	foreach ($query->posts as $attachment) {
		$report = vfwp_intranet_media_audit_build_attachment_report((int) $attachment->ID, $safety_days);
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

function vfwp_intranet_media_audit_ajax_selected_scan_batch() {
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

	$safety_days = isset($_POST['safety_days']) ? min(365, max(1, (int) $_POST['safety_days'])) : 90;
	$token = isset($_POST['selected_scan_token']) ? sanitize_key(wp_unslash($_POST['selected_scan_token'])) : '';
	$reset = !empty($_POST['reset_selected_scan']);

	if ($reset || $token === '') {
		$attachment_ids = isset($_POST['attachment_ids']) && is_array($_POST['attachment_ids'])
			? array_map('absint', wp_unslash($_POST['attachment_ids']))
			: array();
		$attachment_ids = array_values(array_unique(array_filter($attachment_ids)));

		if (empty($attachment_ids)) {
			wp_send_json_error(array(
				'message' => __('Select at least one media item first.', 'vfwp'),
			), 400);
		}

		$token = wp_generate_uuid4();
		$state = vfwp_intranet_media_audit_create_selected_deep_scan_state($attachment_ids, $safety_days);
	} else {
		$state = vfwp_intranet_media_audit_get_selected_deep_scan_state($token);
		if (!is_array($state)) {
			wp_send_json_error(array(
				'message' => __('The selected deep scan state expired. Start the selected scan again.', 'vfwp'),
			), 410);
		}
	}

	$result = vfwp_intranet_media_audit_process_selected_deep_scan_state($state);
	$state = $result['state'];

	if (!empty($result['done'])) {
		vfwp_intranet_media_audit_delete_selected_deep_scan_state($token);
	} else {
		vfwp_intranet_media_audit_set_selected_deep_scan_state($token, $state);
	}

	wp_send_json_success(array(
		'token' => $token,
		'rows_html' => $result['rows_html'],
		'completed_ids' => $result['completed_ids'],
		'filtered_out_ids' => $result['filtered_out_ids'],
		'total' => (int) $state['total'],
		'processed' => (int) $state['processed'],
		'next_offset' => (int) $state['processed'],
		'done' => !empty($result['done']),
		'message' => $result['message'],
		'progress_percent' => vfwp_intranet_media_audit_get_selected_deep_scan_progress_percent($state),
	));
}
add_action('wp_ajax_vfwp_intranet_media_audit_selected_scan_batch', 'vfwp_intranet_media_audit_ajax_selected_scan_batch');

function vfwp_intranet_media_audit_get_selected_deep_scan_transient_key($token) {
	return 'vfwp_media_audit_selected_deep_' . get_current_user_id() . '_' . sanitize_key($token);
}

function vfwp_intranet_media_audit_create_selected_deep_scan_state($attachment_ids, $safety_days) {
	return array(
		'attachment_ids' => array_values(array_map('absint', $attachment_ids)),
		'safety_days' => (int) $safety_days,
		'current_index' => 0,
		'processed' => 0,
		'total' => count($attachment_ids),
		'current' => null,
	);
}

function vfwp_intranet_media_audit_get_selected_deep_scan_state($token) {
	return get_transient(vfwp_intranet_media_audit_get_selected_deep_scan_transient_key($token));
}

function vfwp_intranet_media_audit_set_selected_deep_scan_state($token, $state) {
	set_transient(vfwp_intranet_media_audit_get_selected_deep_scan_transient_key($token), $state, HOUR_IN_SECONDS);
}

function vfwp_intranet_media_audit_delete_selected_deep_scan_state($token) {
	delete_transient(vfwp_intranet_media_audit_get_selected_deep_scan_transient_key($token));
}

function vfwp_intranet_media_audit_process_selected_deep_scan_state($state) {
	$rows_html = '';
	$completed_ids = array();
	$filtered_out_ids = array();
	$message = __('Preparing selected deep scan...', 'vfwp');
	$steps = 0;

	while ($steps < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNKS_PER_REQUEST && (int) $state['processed'] < (int) $state['total']) {
		if (empty($state['current']) || !is_array($state['current'])) {
			$attachment_id = isset($state['attachment_ids'][(int) $state['current_index']])
				? (int) $state['attachment_ids'][(int) $state['current_index']]
				: 0;

			$attachment = $attachment_id > 0 ? get_post($attachment_id) : null;
			if (!$attachment || $attachment->post_type !== 'attachment' || $attachment->post_status === 'trash') {
				if ($attachment_id > 0) {
					$filtered_out_ids[] = $attachment_id;
				}
				$state['current_index'] = (int) $state['current_index'] + 1;
				$state['processed'] = min((int) $state['total'], (int) $state['processed'] + 1);
				continue;
			}

			$state['current'] = vfwp_intranet_media_audit_create_deep_attachment_scan_state($attachment_id);
		}

		$state['current'] = vfwp_intranet_media_audit_process_deep_attachment_scan_step($state['current']);
		$message = vfwp_intranet_media_audit_get_deep_attachment_scan_message($state['current'], (int) $state['current_index'] + 1, (int) $state['total']);
		$steps++;

		if (empty($state['current']['done'])) {
			continue;
		}

		$report = vfwp_intranet_media_audit_build_attachment_report_from_evidence(
			(int) $state['current']['attachment_id'],
			(int) $state['safety_days'],
			'deep',
			isset($state['current']['evidence']) && is_array($state['current']['evidence']) ? $state['current']['evidence'] : array()
		);

		$completed_ids[] = (int) $state['current']['attachment_id'];
		ob_start();
		vfwp_intranet_media_audit_render_report_row($report);
		$rows_html .= ob_get_clean();

		$state['current'] = null;
		$state['current_index'] = (int) $state['current_index'] + 1;
		$state['processed'] = min((int) $state['total'], (int) $state['processed'] + 1);
		break;
	}

	$done = (int) $state['processed'] >= (int) $state['total'];

	return array(
		'state' => $state,
		'rows_html' => $rows_html,
		'completed_ids' => $completed_ids,
		'filtered_out_ids' => $filtered_out_ids,
		'done' => $done,
		'message' => $done ? __('Selected deep scan complete.', 'vfwp') : $message,
	);
}

function vfwp_intranet_media_audit_create_deep_attachment_scan_state($attachment_id) {
	$evidence = vfwp_intranet_media_audit_find_usage_evidence($attachment_id);

	return array(
		'attachment_id' => (int) $attachment_id,
		'stage' => count($evidence) >= VFWP_INTRANET_MEDIA_AUDIT_DEEP_EVIDENCE_LIMIT ? 'done' : 'id_postmeta',
		'last_id' => 0,
		'evidence' => vfwp_intranet_media_audit_unique_evidence($evidence),
		'needles' => vfwp_intranet_media_audit_get_attachment_url_needles($attachment_id),
		'done' => count($evidence) >= VFWP_INTRANET_MEDIA_AUDIT_DEEP_EVIDENCE_LIMIT,
	);
}

function vfwp_intranet_media_audit_process_deep_attachment_scan_step($scan) {
	if (empty($scan['stage']) || $scan['stage'] === 'done' || count($scan['evidence']) >= VFWP_INTRANET_MEDIA_AUDIT_DEEP_EVIDENCE_LIMIT) {
		$scan['stage'] = 'done';
		$scan['done'] = true;
		return $scan;
	}

	$result = null;
	switch ($scan['stage']) {
		case 'id_postmeta':
			$result = vfwp_intranet_media_audit_scan_selected_postmeta_chunk($scan, 'id');
			break;
		case 'id_usermeta':
			$result = vfwp_intranet_media_audit_scan_selected_usermeta_chunk($scan, 'id');
			break;
		case 'id_termmeta':
			$result = vfwp_intranet_media_audit_scan_selected_termmeta_chunk($scan, 'id');
			break;
		case 'id_options':
			$result = vfwp_intranet_media_audit_scan_selected_options_chunk($scan, 'id');
			break;
		case 'url_posts':
			$result = vfwp_intranet_media_audit_scan_selected_posts_chunk($scan);
			break;
		case 'url_postmeta':
			$result = vfwp_intranet_media_audit_scan_selected_postmeta_chunk($scan, 'url');
			break;
		case 'url_usermeta':
			$result = vfwp_intranet_media_audit_scan_selected_usermeta_chunk($scan, 'url');
			break;
		case 'url_termmeta':
			$result = vfwp_intranet_media_audit_scan_selected_termmeta_chunk($scan, 'url');
			break;
		case 'url_options':
			$result = vfwp_intranet_media_audit_scan_selected_options_chunk($scan, 'url');
			break;
	}

	if (!is_array($result)) {
		$scan['stage'] = 'done';
		$scan['done'] = true;
		return $scan;
	}

	$scan['last_id'] = (int) $result['last_id'];
	if (!empty($result['evidence'])) {
		$scan['evidence'] = vfwp_intranet_media_audit_unique_evidence(array_merge($scan['evidence'], $result['evidence']));
	}

	if (count($scan['evidence']) >= VFWP_INTRANET_MEDIA_AUDIT_DEEP_EVIDENCE_LIMIT) {
		$scan['stage'] = 'done';
		$scan['done'] = true;
		return $scan;
	}

	if (!empty($result['complete'])) {
		$scan['stage'] = vfwp_intranet_media_audit_get_next_deep_attachment_stage($scan['stage'], $scan);
		$scan['last_id'] = 0;
	}

	$scan['done'] = $scan['stage'] === 'done';
	return $scan;
}

function vfwp_intranet_media_audit_get_next_deep_attachment_stage($stage, $scan) {
	$stages = vfwp_intranet_media_audit_get_deep_attachment_stage_order();
	$index = array_search($stage, $stages, true);
	if ($index === false) {
		return 'done';
	}

	for ($i = $index + 1; $i < count($stages); $i++) {
		if (strpos($stages[$i], 'url_') === 0 && empty($scan['needles'])) {
			continue;
		}

		return $stages[$i];
	}

	return 'done';
}

function vfwp_intranet_media_audit_get_deep_attachment_scan_message($scan, $current_number, $total) {
	$labels = array(
		'id_postmeta' => __('checking broad post meta ID references', 'vfwp'),
		'id_usermeta' => __('checking user meta ID references', 'vfwp'),
		'id_termmeta' => __('checking term meta ID references', 'vfwp'),
		'id_options' => __('checking option ID references', 'vfwp'),
		'url_posts' => __('checking content URL references', 'vfwp'),
		'url_postmeta' => __('checking post meta URL references', 'vfwp'),
		'url_usermeta' => __('checking user meta URL references', 'vfwp'),
		'url_termmeta' => __('checking term meta URL references', 'vfwp'),
		'url_options' => __('checking option URL references', 'vfwp'),
		'done' => __('finishing item', 'vfwp'),
	);
	$stage = isset($scan['stage']) ? $scan['stage'] : 'done';
	$label = isset($labels[$stage]) ? $labels[$stage] : $labels['done'];

	return sprintf(__('Deep scanning selected item %1$d of %2$d, %3$s.', 'vfwp'), $current_number, $total, $label);
}

function vfwp_intranet_media_audit_get_selected_deep_scan_progress_percent($state) {
	$total = isset($state['total']) ? (int) $state['total'] : 0;
	if ($total <= 0) {
		return 100;
	}

	if ((int) $state['processed'] >= $total) {
		return 100;
	}

	$stage_count = count(vfwp_intranet_media_audit_get_deep_attachment_stage_order());
	$completed_units = (int) $state['processed'] * $stage_count;

	if (!empty($state['current']) && is_array($state['current'])) {
		$stage_index = vfwp_intranet_media_audit_get_deep_attachment_stage_index($state['current']['stage']);
		$stage_fraction = vfwp_intranet_media_audit_get_deep_attachment_stage_fraction($state['current']);
		$completed_units += $stage_index + $stage_fraction;
	}

	return min(99, max(0, (int) floor(($completed_units / ($total * $stage_count)) * 100)));
}

function vfwp_intranet_media_audit_get_deep_attachment_stage_order() {
	return array(
		'id_postmeta',
		'id_usermeta',
		'id_termmeta',
		'id_options',
		'url_posts',
		'url_postmeta',
		'url_usermeta',
		'url_termmeta',
		'url_options',
	);
}

function vfwp_intranet_media_audit_get_deep_attachment_stage_index($stage) {
	$stages = vfwp_intranet_media_audit_get_deep_attachment_stage_order();
	$index = array_search($stage, $stages, true);

	return $index === false ? count($stages) : (int) $index;
}

function vfwp_intranet_media_audit_get_deep_attachment_stage_fraction($scan) {
	$stage = isset($scan['stage']) ? $scan['stage'] : '';
	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$max_id = vfwp_intranet_media_audit_get_deep_attachment_stage_max_id($stage);

	if ($max_id <= 0) {
		return 0;
	}

	return min(0.99, max(0, $last_id / $max_id));
}

function vfwp_intranet_media_audit_get_deep_attachment_stage_max_id($stage) {
	global $wpdb;
	static $max_ids = array();

	if (isset($max_ids[$stage])) {
		return $max_ids[$stage];
	}

	switch ($stage) {
		case 'id_postmeta':
		case 'url_postmeta':
			$max_ids[$stage] = (int) $wpdb->get_var("SELECT MAX(meta_id) FROM {$wpdb->postmeta}");
			break;
		case 'id_usermeta':
		case 'url_usermeta':
			$max_ids[$stage] = (int) $wpdb->get_var("SELECT MAX(umeta_id) FROM {$wpdb->usermeta}");
			break;
		case 'id_termmeta':
		case 'url_termmeta':
			$max_ids[$stage] = (int) $wpdb->get_var("SELECT MAX(meta_id) FROM {$wpdb->termmeta}");
			break;
		case 'id_options':
		case 'url_options':
			$max_ids[$stage] = (int) $wpdb->get_var(
				"SELECT MAX(option_id)
				FROM {$wpdb->options}
				WHERE option_name NOT LIKE '\\_transient\\_%%'
					AND option_name NOT LIKE '\\_site\\_transient\\_%%'
					AND option_name NOT IN ('vfwp_people_sync_stats', 'vfwp_teams_sync_stats')"
			);
			break;
		case 'url_posts':
			$max_ids[$stage] = (int) $wpdb->get_var(
				"SELECT MAX(ID)
				FROM {$wpdb->posts}
				WHERE post_type <> 'attachment'
					AND post_status <> 'trash'"
			);
			break;
		default:
			$max_ids[$stage] = 0;
			break;
	}

	return $max_ids[$stage];
}

function vfwp_intranet_media_audit_scan_selected_posts_chunk($scan) {
	global $wpdb;

	$attachment_id = (int) $scan['attachment_id'];
	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$needles = isset($scan['needles']) && is_array($scan['needles']) ? $scan['needles'] : array();
	$evidence = array();
	$rows = $wpdb->get_results($wpdb->prepare(
		"SELECT ID, post_type, post_content, post_excerpt
		FROM {$wpdb->posts}
		WHERE ID > %d
			AND ID <> %d
			AND post_type <> 'attachment'
			AND post_status <> 'trash'
		ORDER BY ID ASC
		LIMIT %d",
		$last_id,
		$attachment_id,
		VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE
	));

	if (empty($rows)) {
		return array('last_id' => $last_id, 'evidence' => $evidence, 'complete' => true);
	}

	foreach ($rows as $row) {
		$last_id = (int) $row->ID;

		if (!vfwp_intranet_media_audit_contains_any_needle($row->post_content . "\n" . $row->post_excerpt, $needles)) {
			continue;
		}

		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			sprintf(__('Content URL reference in %1$s #%2$d: ', 'vfwp'), $row->post_type, (int) $row->ID),
			(int) $row->ID
		);
	}

	return array(
		'last_id' => $last_id,
		'evidence' => $evidence,
		'complete' => count($rows) < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE,
	);
}

function vfwp_intranet_media_audit_scan_selected_postmeta_chunk($scan, $match_type) {
	global $wpdb;

	$attachment_id = (int) $scan['attachment_id'];
	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$rows = $wpdb->get_results($wpdb->prepare(
		"SELECT meta_id, post_id, meta_key, meta_value
		FROM {$wpdb->postmeta}
		WHERE meta_id > %d
			AND post_id <> %d
			AND meta_key NOT IN ('_wp_attachment_metadata', '_wp_attached_file')
		ORDER BY meta_id ASC
		LIMIT %d",
		$last_id,
		$attachment_id,
		VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE
	));

	$evidence = array();
	if (empty($rows)) {
		return array('last_id' => $last_id, 'evidence' => $evidence, 'complete' => true);
	}

	foreach ($rows as $row) {
		$last_id = (int) $row->meta_id;

		if (!vfwp_intranet_media_audit_selected_deep_value_matches($row->meta_value, $scan, $match_type)) {
			continue;
		}

		$evidence[] = vfwp_intranet_media_audit_post_evidence(
			$match_type === 'id'
				? sprintf(__('Possible serialized post meta reference %1$s on #%2$d: ', 'vfwp'), $row->meta_key, (int) $row->post_id)
				: sprintf(__('Post meta URL reference %1$s on #%2$d: ', 'vfwp'), $row->meta_key, (int) $row->post_id),
			(int) $row->post_id
		);
	}

	return array(
		'last_id' => $last_id,
		'evidence' => $evidence,
		'complete' => count($rows) < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE,
	);
}

function vfwp_intranet_media_audit_scan_selected_usermeta_chunk($scan, $match_type) {
	global $wpdb;

	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$rows = $wpdb->get_results($wpdb->prepare(
		"SELECT umeta_id, user_id, meta_key, meta_value
		FROM {$wpdb->usermeta}
		WHERE umeta_id > %d
		ORDER BY umeta_id ASC
		LIMIT %d",
		$last_id,
		VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE
	));

	$evidence = array();
	if (empty($rows)) {
		return array('last_id' => $last_id, 'evidence' => $evidence, 'complete' => true);
	}

	foreach ($rows as $row) {
		$last_id = (int) $row->umeta_id;

		if (!vfwp_intranet_media_audit_selected_deep_value_matches($row->meta_value, $scan, $match_type)) {
			continue;
		}

		$user = get_userdata((int) $row->user_id);
		$evidence[] = array(
			'label' => $match_type === 'id'
				? sprintf(__('User meta %1$s on user #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->user_id, $user ? ': ' . $user->display_name : '')
				: sprintf(__('User meta URL reference %1$s on user #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->user_id, $user ? ': ' . $user->display_name : ''),
		);
	}

	return array(
		'last_id' => $last_id,
		'evidence' => $evidence,
		'complete' => count($rows) < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE,
	);
}

function vfwp_intranet_media_audit_scan_selected_termmeta_chunk($scan, $match_type) {
	global $wpdb;

	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$rows = $wpdb->get_results($wpdb->prepare(
		"SELECT meta_id, term_id, meta_key, meta_value
		FROM {$wpdb->termmeta}
		WHERE meta_id > %d
		ORDER BY meta_id ASC
		LIMIT %d",
		$last_id,
		VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE
	));

	$evidence = array();
	if (empty($rows)) {
		return array('last_id' => $last_id, 'evidence' => $evidence, 'complete' => true);
	}

	foreach ($rows as $row) {
		$last_id = (int) $row->meta_id;

		if (!vfwp_intranet_media_audit_selected_deep_value_matches($row->meta_value, $scan, $match_type)) {
			continue;
		}

		$term = get_term((int) $row->term_id);
		$evidence[] = array(
			'label' => $match_type === 'id'
				? sprintf(__('Term meta %1$s on term #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->term_id, $term && !is_wp_error($term) ? ': ' . $term->name : '')
				: sprintf(__('Term meta URL reference %1$s on term #%2$d%3$s', 'vfwp'), $row->meta_key, (int) $row->term_id, $term && !is_wp_error($term) ? ': ' . $term->name : ''),
		);
	}

	return array(
		'last_id' => $last_id,
		'evidence' => $evidence,
		'complete' => count($rows) < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE,
	);
}

function vfwp_intranet_media_audit_scan_selected_options_chunk($scan, $match_type) {
	global $wpdb;

	$last_id = isset($scan['last_id']) ? (int) $scan['last_id'] : 0;
	$rows = $wpdb->get_results($wpdb->prepare(
		"SELECT option_id, option_name, option_value
		FROM {$wpdb->options}
		WHERE option_id > %d
			AND option_name NOT LIKE '\\_transient\\_%%'
			AND option_name NOT LIKE '\\_site\\_transient\\_%%'
			AND option_name NOT IN ('vfwp_people_sync_stats', 'vfwp_teams_sync_stats')
		ORDER BY option_id ASC
		LIMIT %d",
		$last_id,
		VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE
	));

	$evidence = array();
	if (empty($rows)) {
		return array('last_id' => $last_id, 'evidence' => $evidence, 'complete' => true);
	}

	foreach ($rows as $row) {
		$last_id = (int) $row->option_id;

		if (!vfwp_intranet_media_audit_selected_deep_value_matches($row->option_value, $scan, $match_type)) {
			continue;
		}

		$evidence[] = array(
			'label' => $match_type === 'id'
				? sprintf(__('Option reference: %s', 'vfwp'), $row->option_name)
				: sprintf(__('Option URL reference: %s', 'vfwp'), $row->option_name),
		);
	}

	return array(
		'last_id' => $last_id,
		'evidence' => $evidence,
		'complete' => count($rows) < VFWP_INTRANET_MEDIA_AUDIT_SELECTED_DEEP_CHUNK_SIZE,
	);
}

function vfwp_intranet_media_audit_selected_deep_value_matches($value, $scan, $match_type) {
	if ($match_type === 'id') {
		return vfwp_intranet_media_audit_value_matches_attachment_id($value, (int) $scan['attachment_id']);
	}

	return vfwp_intranet_media_audit_contains_any_needle(
		$value,
		isset($scan['needles']) && is_array($scan['needles']) ? $scan['needles'] : array()
	);
}

function vfwp_intranet_media_audit_render_page() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to view this report.', 'vfwp'));
	}

	$filters = vfwp_intranet_media_audit_get_scan_filters_from_request($_GET);
	$paged = $filters['paged'];
	$per_page = $filters['per_page'];
	$safety_days = $filters['safety_days'];
	$mime = $filters['mime'];
	$status_filter = $filters['status_filter'];
	$upload_order = $filters['upload_order'];
	$uploaded_from = $filters['uploaded_from'];
	$uploaded_to = $filters['uploaded_to'];
	$has_scan = isset($_GET['media_audit_scan']);

	$reports = array();
	$query = null;

	if ($has_scan) {
		$scan = vfwp_intranet_media_audit_get_report_page($paged, $per_page, $mime, $safety_days, $upload_order, $uploaded_from, $uploaded_to);
		$query = $scan['query'];
		$reports = $scan['reports'];
	}

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
					<option value="no-evidence" <?php selected($status_filter, 'no-evidence'); ?>><?php echo esc_html__('Evidence not found', 'vfwp'); ?></option>
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
		</form>

		<div class="vfwp-media-audit__loading" data-vfwp-media-audit-loading hidden>
			<div class="vfwp-media-audit__loading-label" data-vfwp-media-audit-loading-label><?php echo esc_html__('Scanning media library...', 'vfwp'); ?></div>
			<div class="vfwp-media-audit__loading-track">
				<div class="vfwp-media-audit__loading-bar" data-vfwp-media-audit-loading-bar></div>
			</div>
			<div class="vfwp-media-audit__loading-progress" data-vfwp-media-audit-full-progress hidden></div>
		</div>

		<div data-vfwp-media-audit-results>
			<?php if ($has_scan) : ?>
				<?php
				vfwp_intranet_media_audit_render_scan_results($reports, $query, $paged, $per_page, $safety_days, $mime, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
				vfwp_intranet_media_audit_render_scan_pagination($query, $paged, $per_page, $safety_days, $mime, $upload_order, $uploaded_from, $uploaded_to, $status_filter);
				?>
			<?php else : ?>
				<div class="notice notice-warning inline is-dismissible">
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
			background: #f8d7da;
			color: #842029;
		}

		.vfwp-media-audit__evidence {
			list-style: disc;
			margin: 0 0 0 18px;
		}

		.vfwp-media-audit__single-action {
			margin-top: 8px;
		}

		.vfwp-media-audit__row--scanning {
			opacity: 0.65;
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

			function setLoadingPercent(percent, message) {
				loading.classList.add('is-full-scan');
				percent = Math.min(100, Math.max(0, Math.round(percent || 0)));

				if (loadingBar) {
					loadingBar.style.left = '0';
					loadingBar.style.width = percent + '%';
				}

				if (fullProgress) {
					fullProgress.hidden = false;
					fullProgress.textContent = (message || '<?php echo esc_js(__('Scanning...', 'vfwp')); ?>') + ' ' + percent + '%';
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
								var row = checkbox.closest('[data-vfwp-media-audit-row]');
								checkbox.checked = selectAll.checked && (!row || !row.hidden);
							});
						});
					}

					bulkForm.addEventListener('submit', function (event) {
						var selectedAction = bulkForm.querySelector('select[name="bulk_action"]');
						var selectedItems = bulkForm.querySelectorAll('[data-vfwp-media-audit-checkbox]:checked');

						if (!selectedAction || selectedAction.value === '') {
							event.preventDefault();
							window.alert('<?php echo esc_js(__('Choose a bulk action before applying.', 'vfwp')); ?>');
							return;
						}

						if (!selectedItems.length) {
							event.preventDefault();
							window.alert('<?php echo esc_js(__('Select at least one media item first.', 'vfwp')); ?>');
							return;
						}

						if (selectedAction.value === 'deep-scan') {
							var selectedRows = {};
							var selectedControls = Array.prototype.slice.call(bulkForm.querySelectorAll('button, select'));

							selectedItems.forEach(function (checkbox) {
								selectedRows[checkbox.value] = checkbox.closest('tr');
							});

							event.preventDefault();
							runSelectedDeepScan(new FormData(bulkForm), {
								targetRows: selectedRows,
								targetControls: selectedControls
							});
							return;
						}

						if (selectedAction.value !== 'delete') {
							event.preventDefault();
							window.alert('<?php echo esc_js(__('Choose a supported bulk action before applying.', 'vfwp')); ?>');
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

				params.set('media_audit_scan', '1');

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

			function makeNoticeDismissible(notice) {
				var button;

				if (!notice || notice.querySelector('.notice-dismiss')) {
					return;
				}

				notice.classList.add('is-dismissible');
				button = document.createElement('button');
				button.type = 'button';
				button.className = 'notice-dismiss';
				button.innerHTML = '<span class="screen-reader-text"><?php echo esc_js(__('Dismiss this notice.', 'vfwp')); ?></span>';
				button.addEventListener('click', function () {
					notice.remove();
				});
				notice.appendChild(button);
			}

			function bindDismissibleNotices() {
				document.querySelectorAll('.vfwp-media-audit .notice.is-dismissible').forEach(makeNoticeDismissible);
			}

			function runAjaxScan(data) {
				var displayData = cloneFormData(data);
				var requestData = cloneFormData(data);

				requestData.set('action', 'vfwp_intranet_media_audit_scan');
				requestData.set('nonce', nonce);
				setLoading(true);

				window.fetch(ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: requestData
				})
					.then(parseAjaxResponse)
					.then(function (payload) {
						if (!payload || !payload.success || !payload.data || typeof payload.data.html !== 'string') {
							throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The media scan did not return usable results.', 'vfwp')); ?>');
						}

						results.innerHTML = payload.data.html;
						updateUrlFromData(displayData);
						bindBulkControls();
						bindDismissibleNotices();
						applyDisplayControls(true);
					})
					.catch(function (error) {
						var notice = document.createElement('div');
						var paragraph = document.createElement('p');

						notice.className = 'notice notice-error inline is-dismissible';
						paragraph.appendChild(document.createTextNode(error.message));
						notice.appendChild(paragraph);
						makeNoticeDismissible(notice);
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
							'<option value="deep-scan"><?php echo esc_js(__('Deep scan selected', 'vfwp')); ?></option>' +
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

				notice.className = 'notice notice-' + (type || 'info') + ' inline is-dismissible';
				paragraph.appendChild(document.createTextNode(message));
				notice.appendChild(paragraph);
				makeNoticeDismissible(notice);
				results.insertBefore(notice, results.firstChild);
			}

			function cloneFormData(data) {
				var clone = new FormData();

				data.forEach(function (value, key) {
					clone.append(key, value);
				});

				return clone;
			}

			function getDisplayData() {
				return new FormData(form);
			}

			function syncBulkFormsWithDisplayControls() {
				var displayData = getDisplayData();

				document.querySelectorAll('[data-vfwp-media-audit-bulk-form]').forEach(function (bulkForm) {
					['status_filter', 'upload_order'].forEach(function (name) {
						var input = bulkForm.querySelector('input[name="' + name + '"]');
						if (input) {
							input.value = displayData.get(name) || '';
						}
					});
				});
			}

			function rowMatchesStatusFilter(row, statusFilter) {
				var status = row.getAttribute('data-vfwp-media-audit-status') || '';

				if (!statusFilter) {
					return true;
				}

				if (statusFilter === 'no-evidence') {
					return status === 'needs-review' || status === 'candidate-unused';
				}

				return status === statusFilter;
			}

			function updateTableEmptyRow(tbody, visibleCount, hasRows, showEmptyRow) {
				var emptyRow = tbody.querySelector('[data-vfwp-media-audit-empty]');

				if (emptyRow) {
					emptyRow.remove();
				}

				if (!showEmptyRow || visibleCount > 0) {
					return;
				}

				emptyRow = document.createElement('tr');
				emptyRow.setAttribute('data-vfwp-media-audit-empty', '');
				emptyRow.innerHTML = '<td colspan="6">' + (hasRows
					? '<?php echo esc_js(__('No media match the current table filter.', 'vfwp')); ?>'
					: '<?php echo esc_js(__('No media found for this filter.', 'vfwp')); ?>') + '</td>';
				tbody.appendChild(emptyRow);
			}

			function applyDisplayControls(showEmptyRow, shouldSort) {
				if (!results) {
					return;
				}

				shouldSort = shouldSort !== false;

				var tbody = results.querySelector('tbody');
				if (!tbody) {
					return;
				}

				var displayData = getDisplayData();
				var statusFilter = displayData.get('status_filter') || '';
				var uploadOrder = displayData.get('upload_order') === 'ASC' ? 'ASC' : 'DESC';
				var rows = Array.prototype.slice.call(tbody.querySelectorAll('[data-vfwp-media-audit-row]'));
				var visibleCount = 0;

				if (shouldSort) {
					rows.sort(function (a, b) {
						var aTime = parseInt(a.getAttribute('data-vfwp-media-audit-uploaded-time') || '0', 10);
						var bTime = parseInt(b.getAttribute('data-vfwp-media-audit-uploaded-time') || '0', 10);
						var aId = parseInt(a.getAttribute('data-vfwp-media-audit-id') || '0', 10);
						var bId = parseInt(b.getAttribute('data-vfwp-media-audit-id') || '0', 10);

						if (aTime === bTime) {
							return uploadOrder === 'ASC' ? aId - bId : bId - aId;
						}

						return uploadOrder === 'ASC' ? aTime - bTime : bTime - aTime;
					});
				}

				rows.forEach(function (row) {
					var visible = rowMatchesStatusFilter(row, statusFilter);

					row.hidden = !visible;
					if (visible) {
						visibleCount++;
					} else {
						var checkbox = row.querySelector('[data-vfwp-media-audit-checkbox]');
						if (checkbox) {
							checkbox.checked = false;
						}
					}

					if (shouldSort) {
						tbody.appendChild(row);
					}
				});

				updateTableEmptyRow(tbody, visibleCount, rows.length > 0, !!showEmptyRow);
				syncBulkFormsWithDisplayControls();
			}

			function runFullScan(data) {
				var tbody = buildFullScanShell(data);
				var requestData = cloneFormData(data);

				requestData.delete('media_audit_scan');
				requestData.set('action', 'vfwp_intranet_media_audit_full_scan_batch');
				requestData.set('nonce', nonce);
				requestData.set('paged', '1');
				setLoading(true, '<?php echo esc_js(__('Scanning media library...', 'vfwp')); ?>');
				setFullScanProgress(0, 0);
				updateUrlFromData(data);

				function scanNextBatch() {
					window.fetch(ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body: requestData
					})
						.then(parseAjaxResponse)
						.then(function (payload) {
							if (!payload || !payload.success || !payload.data) {
								throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The full media scan did not return usable results.', 'vfwp')); ?>');
							}

							if (typeof payload.data.rows_html === 'string' && payload.data.rows_html !== '') {
								tbody.insertAdjacentHTML('beforeend', payload.data.rows_html);
								applyDisplayControls(false, false);
							}

							setFullScanProgress(payload.data.processed || 0, payload.data.total || 0);

							if (payload.data.done) {
								setLoading(false);

								applyDisplayControls(true);
								appendFullScanNotice('<?php echo esc_js(__('Media scan complete.', 'vfwp')); ?>', 'success');
								bindBulkControls();
								return;
							}

							requestData.set('paged', String(payload.data.next_paged || (parseInt(requestData.get('paged'), 10) + 1)));
							window.setTimeout(scanNextBatch, 120);
						})
						.catch(function (error) {
							setLoading(false);
							appendFullScanNotice(error.message, 'error');
						});
				}

				scanNextBatch();
			}

			function runSelectedDeepScan(data, options) {
				options = options || {};
				var total = data.getAll('attachment_ids[]').length;
				var targetRow = options.targetRow || null;
				var targetRows = options.targetRows || null;
				var targetButton = options.targetButton || null;
				var targetControls = options.targetControls || [];
				var renderedAnyRows = false;
				var filteredAnyRows = false;
				var preservesExistingRows = !!(targetRow || targetRows);

				data.delete('bulk_action');
				data.delete('media_audit_scan');
				data.delete('media_audit_full_scan');
				data.set('action', 'vfwp_intranet_media_audit_selected_scan_batch');
				data.set('nonce', nonce);
				data.set('offset', '0');
				data.set('reset_selected_scan', '1');
				data.delete('selected_scan_token');

				var tbody = preservesExistingRows ? null : buildFullScanShell(data);

				if (targetRow) {
					targetRow.classList.add('vfwp-media-audit__row--scanning');
				}

				if (targetRows) {
					Object.keys(targetRows).forEach(function (attachmentId) {
						if (targetRows[attachmentId] && targetRows[attachmentId].classList) {
							targetRows[attachmentId].classList.add('vfwp-media-audit__row--scanning');
						}
					});
				}

				if (targetButton) {
					targetButton.disabled = true;
				}

				targetControls.forEach(function (control) {
					control.disabled = true;
				});

				setLoading(true, '<?php echo esc_js(__('Deep scanning selected media...', 'vfwp')); ?>');
				setFullScanProgress(0, total, '<?php echo esc_js(__('Deep-scanned selected items:', 'vfwp')); ?>');

				function scanNextSelectedBatch() {
					window.fetch(ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body: data
					})
						.then(parseAjaxResponse)
						.then(function (payload) {
							if (!payload || !payload.success || !payload.data) {
								throw new Error(payload && payload.data && payload.data.message ? payload.data.message : '<?php echo esc_js(__('The selected media scan did not return usable results.', 'vfwp')); ?>');
							}

							if (payload.data.token) {
								data.set('selected_scan_token', payload.data.token);
								data.delete('reset_selected_scan');
							}

							if (typeof payload.data.rows_html === 'string' && payload.data.rows_html !== '') {
								renderedAnyRows = true;

								if (targetRow) {
									var temporaryTbody = document.createElement('tbody');
									temporaryTbody.innerHTML = payload.data.rows_html;
									var replacementRow = temporaryTbody.querySelector('tr');

									if (replacementRow) {
										targetRow.replaceWith(replacementRow);
										targetRow = replacementRow;
									}
								} else if (targetRows) {
									var rowsTbody = document.createElement('tbody');
									rowsTbody.innerHTML = payload.data.rows_html;

									rowsTbody.querySelectorAll('tr').forEach(function (replacementRow) {
										var replacementCheckbox = replacementRow.querySelector('[data-vfwp-media-audit-checkbox]');
										var attachmentId = replacementCheckbox ? replacementCheckbox.value : '';
										var existingRow = attachmentId && targetRows[attachmentId] ? targetRows[attachmentId] : null;

										if (!existingRow) {
											return;
										}

										existingRow.replaceWith(replacementRow);
										targetRows[attachmentId] = replacementRow;
									});
								} else {
									tbody.insertAdjacentHTML('beforeend', payload.data.rows_html);
								}
							}

							if (Array.isArray(payload.data.filtered_out_ids) && payload.data.filtered_out_ids.length) {
								filteredAnyRows = true;
								payload.data.filtered_out_ids.forEach(function (attachmentId) {
									var normalizedAttachmentId = String(attachmentId);

									if (targetRows && targetRows[normalizedAttachmentId]) {
										targetRows[normalizedAttachmentId].remove();
										delete targetRows[normalizedAttachmentId];
										return;
									}

									if (targetRow) {
										var targetCheckbox = targetRow.querySelector('[data-vfwp-media-audit-checkbox]');
										if (targetCheckbox && targetCheckbox.value === normalizedAttachmentId) {
											targetRow.remove();
											targetRow = null;
										}
									}
								});
							}

							applyDisplayControls(true);

							if (typeof payload.data.progress_percent === 'number') {
								setLoadingPercent(payload.data.progress_percent, payload.data.message || '<?php echo esc_js(__('Deep scanning selected media...', 'vfwp')); ?>');
							} else {
								setFullScanProgress(payload.data.processed || 0, payload.data.total || total, '<?php echo esc_js(__('Deep-scanned selected items:', 'vfwp')); ?>');
							}

							if (payload.data.done) {
								setLoading(false);

								if (preservesExistingRows) {
									clearSelectedDeepScanUiState(targetRow, targetRows, targetButton, targetControls);

									if (!renderedAnyRows && !filteredAnyRows) {
										appendFullScanNotice('<?php echo esc_js(__('Selected media item could not be scanned.', 'vfwp')); ?>', 'warning');
									} else if (targetRows) {
										appendFullScanNotice('<?php echo esc_js(__('Selected deep scan complete.', 'vfwp')); ?>', 'success');
									}

									bindBulkControls();
									return;
								}

								if (!tbody.children.length) {
									tbody.innerHTML = '<tr><td colspan="6"><?php echo esc_js(__('No selected media items could be scanned.', 'vfwp')); ?></td></tr>';
								}

								appendFullScanNotice('<?php echo esc_js(__('Selected deep scan complete.', 'vfwp')); ?>', 'success');
								bindBulkControls();
								return;
							}

							data.set('offset', String(payload.data.next_offset || (parseInt(data.get('offset'), 10) + 1)));
							window.setTimeout(scanNextSelectedBatch, 120);
						})
						.catch(function (error) {
							setLoading(false);
							clearSelectedDeepScanUiState(targetRow, targetRows, targetButton, targetControls);
							appendFullScanNotice(error.message, 'error');
						});
				}

				scanNextSelectedBatch();
			}

			function clearSelectedDeepScanUiState(targetRow, targetRows, targetButton, targetControls) {
				if (targetRow && targetRow.classList) {
					targetRow.classList.remove('vfwp-media-audit__row--scanning');
				}

				if (targetRows) {
					Object.keys(targetRows).forEach(function (attachmentId) {
						if (targetRows[attachmentId] && targetRows[attachmentId].classList) {
							targetRows[attachmentId].classList.remove('vfwp-media-audit__row--scanning');
						}
					});
				}

				if (targetButton) {
					targetButton.disabled = false;
				}

				targetControls.forEach(function (control) {
					control.disabled = false;
				});
			}

			function buildSingleDeepScanData(trigger) {
				var data = new FormData();
				var bulkForm = trigger.closest('[data-vfwp-media-audit-bulk-form]');
				var attachmentId = trigger.getAttribute('data-vfwp-media-audit-single-deep-scan');

				if (bulkForm) {
					bulkForm.querySelectorAll('input[type="hidden"]').forEach(function (input) {
						if (input.name) {
							data.append(input.name, input.value || '');
						}
					});
				} else if (form) {
					new FormData(form).forEach(function (value, key) {
						data.append(key, value);
					});
				}

				data.delete('attachment_ids[]');
				data.append('attachment_ids[]', attachmentId || '');

				return data;
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

				runAjaxScan(data);
			});

			form.addEventListener('change', function (event) {
				var target = event.target;
				var controlsExistingRows = target && (target.name === 'status_filter' || target.name === 'upload_order');

				if (!controlsExistingRows || !results || !results.querySelector('[data-vfwp-media-audit-row]')) {
					return;
				}

				applyDisplayControls(true);
				updateUrlFromData(getDisplayData());
			});

			document.addEventListener('click', function (event) {
				var pageLink = event.target.closest('[data-vfwp-media-audit-results] .tablenav-pages a');
				var singleDeepScan = event.target.closest('[data-vfwp-media-audit-single-deep-scan]');

				if (!pageLink && !singleDeepScan) {
					return;
				}

				if (!window.fetch || !window.FormData || !results) {
					setLoading(true);
					return;
				}

				event.preventDefault();

				if (singleDeepScan) {
					runSelectedDeepScan(buildSingleDeepScanData(singleDeepScan), {
						targetRow: singleDeepScan.closest('tr'),
						targetButton: singleDeepScan
					});
					return;
				}

				var url = new URL(pageLink.href);
				var data = new FormData();
				url.searchParams.forEach(function (value, key) {
					data.set(key, value);
				});

				data.set('media_audit_scan', '1');
				runAjaxScan(data);
			});

			bindBulkControls();
			bindDismissibleNotices();
			applyDisplayControls(true);
		});
	</script>
	<?php
	wp_reset_postdata();
}

function vfwp_intranet_media_audit_get_scan_filters_from_request($source) {
	return array(
		'paged' => isset($source['paged']) ? max(1, (int) wp_unslash($source['paged'])) : 1,
		'per_page' => vfwp_intranet_media_audit_get_per_page_from_request($source),
		'safety_days' => isset($source['safety_days']) ? min(365, max(1, (int) wp_unslash($source['safety_days']))) : 90,
		'mime' => isset($source['attachment_mime']) ? sanitize_text_field(wp_unslash($source['attachment_mime'])) : '',
		'status_filter' => vfwp_intranet_media_audit_get_status_filter_from_request($source),
		'upload_order' => vfwp_intranet_media_audit_get_upload_order_from_request($source),
		'uploaded_from' => vfwp_intranet_media_audit_get_uploaded_date_from_request($source, 'uploaded_from'),
		'uploaded_to' => vfwp_intranet_media_audit_get_uploaded_date_from_request($source, 'uploaded_to'),
	);
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
	if ($status === 'needs-review') {
		return 'no-evidence';
	}

	return in_array($status, array('used', 'no-evidence', 'candidate-unused'), true) ? $status : '';
}

function vfwp_intranet_media_audit_report_matches_status_filter($report, $status_filter) {
	if ($status_filter === '') {
		return true;
	}

	if (empty($report['status'])) {
		return false;
	}

	if ($status_filter === 'no-evidence') {
		return in_array($report['status'], array('needs-review', 'candidate-unused'), true);
	}

	return $report['status'] === $status_filter;
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
					<option value="deep-scan"><?php echo esc_html__('Deep scan selected', 'vfwp'); ?></option>
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
					<tr data-vfwp-media-audit-empty>
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

function vfwp_intranet_media_audit_render_report_row($report) {
	$edit_url = get_edit_post_link($report['id'], '');
	$edit_url = is_string($edit_url) ? $edit_url : '';
	?>
	<tr data-vfwp-media-audit-row data-vfwp-media-audit-status="<?php echo esc_attr($report['status']); ?>" data-vfwp-media-audit-uploaded-time="<?php echo esc_attr($report['uploaded_time']); ?>" data-vfwp-media-audit-id="<?php echo esc_attr($report['id']); ?>">
		<th scope="row" class="check-column">
			<input type="checkbox" name="attachment_ids[]" value="<?php echo esc_attr($report['id']); ?>" data-vfwp-media-audit-checkbox>
		</th>
		<td>
			<strong>
				<?php if ($edit_url !== '') : ?>
					<a href="<?php echo esc_url($edit_url); ?>">
						<?php echo esc_html($report['title']); ?>
					</a>
				<?php else : ?>
					<?php echo esc_html($report['title']); ?>
				<?php endif; ?>
			</strong>
			<br>
			<code><?php echo esc_html($report['relative_file']); ?></code>
			<br>
			<a href="<?php echo esc_url($report['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('Open file', 'vfwp'); ?></a>
			<?php if ($edit_url !== '') : ?>
				<span aria-hidden="true"> | </span>
				<a href="<?php echo esc_url($edit_url); ?>"><?php echo esc_html__('Edit media', 'vfwp'); ?></a>
			<?php endif; ?>
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
				<?php if (!empty($report['evidence_mode']) && $report['evidence_mode'] === 'fast') : ?>
					<?php echo esc_html__('No fast evidence found. Deep URL, broad meta, term, user, and option checks were skipped.', 'vfwp'); ?>
					<br>
					<button type="button" class="button button-small vfwp-media-audit__single-action" data-vfwp-media-audit-single-deep-scan="<?php echo esc_attr($report['id']); ?>">
						<?php echo esc_html__('Deep scan this file', 'vfwp'); ?>
					</button>
				<?php else : ?>
					<?php echo esc_html__('No database, content, option, user, term, or theme reference found.', 'vfwp'); ?>
				<?php endif; ?>
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
	$filters = vfwp_intranet_media_audit_get_scan_filters_from_request($source);

	return array(
		'page'            => VFWP_INTRANET_MEDIA_AUDIT_PAGE,
		'paged'           => $filters['paged'],
		'per_page'        => $filters['per_page'],
		'safety_days'     => $filters['safety_days'],
		'attachment_mime' => $filters['mime'],
		'status_filter'   => $filters['status_filter'],
		'upload_order'    => $filters['upload_order'],
		'uploaded_from'   => $filters['uploaded_from'],
		'uploaded_to'     => $filters['uploaded_to'],
	);
}

function vfwp_intranet_media_audit_ajax_shutdown_handler() {
	if (!function_exists('wp_doing_ajax') || !wp_doing_ajax()) {
		return;
	}

	$action = isset($_REQUEST['action']) ? sanitize_key(wp_unslash($_REQUEST['action'])) : '';
	if (!in_array($action, array('vfwp_intranet_media_audit_scan', 'vfwp_intranet_media_audit_full_scan_batch', 'vfwp_intranet_media_audit_selected_scan_batch'), true)) {
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
		<p><?php echo esc_html__('Deep scan selected reruns only the checked media items with broader checks, which is useful after the main scan returns Evidence not found rows. Delete permanently removes the selected attachment records and their files from the media library. This cannot be restored from WordPress.', 'vfwp'); ?></p>

		<h3><?php echo esc_html__('Evidence Search', 'vfwp'); ?></h3>
		<dl>
			<dt><?php echo esc_html__('Main scan', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('Uses fast checks such as attachment parent, known media fields, and theme references. It skips broad URL, generic serialized-meta, user, term, and option searches so it is safer for large live sites.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Deep scan this file / Deep scan selected', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('Runs broader URL, serialized-meta, user, term, and option checks only for the media items you choose. The selected scan is chunked and resumable so it can inspect specific unresolved files without deep-scanning the whole library.', 'vfwp'); ?></dd>
		</dl>

		<h3><?php echo esc_html__('Statuses', 'vfwp'); ?></h3>
		<dl>
			<dt><?php echo esc_html__('Evidence found', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('The scanner found at least one signal that the media item is used. Do not delete without reviewing the evidence links.', 'vfwp'); ?></dd>

			<dt><?php echo esc_html__('Evidence not found', 'vfwp'); ?></dt>
			<dd><?php echo esc_html__('No usage evidence was found. The Evidence not found status filter includes both recently uploaded unresolved files and older Candidate unused files.', 'vfwp'); ?></dd>

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
	$evidence = vfwp_intranet_media_audit_find_usage_evidence($attachment_id);

	return vfwp_intranet_media_audit_build_attachment_report_from_evidence($attachment_id, $safety_days, 'fast', $evidence);
}

function vfwp_intranet_media_audit_build_attachment_report_from_evidence($attachment_id, $safety_days, $evidence_mode, $evidence) {
	$post = get_post($attachment_id);
	$file = get_attached_file($attachment_id);
	$url = wp_get_attachment_url($attachment_id);
	$relative_file = get_post_meta($attachment_id, '_wp_attached_file', true);
	$uploaded_time = $post ? strtotime($post->post_date_gmt . ' GMT') : false;
	$is_new = $uploaded_time ? $uploaded_time > strtotime('-' . (int) $safety_days . ' days') : true;

	if (!empty($evidence)) {
		$status = 'used';
		$status_label = __('Evidence found', 'vfwp');
	} elseif ($is_new) {
		$status = 'needs-review';
		$status_label = __('Evidence not found', 'vfwp');
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
		'evidence_mode'   => $evidence_mode,
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

	return $evidence;
}

function vfwp_intranet_media_audit_value_matches_attachment_id($value, $attachment_id) {
	if (!is_scalar($value)) {
		return false;
	}

	$value = (string) $value;
	$id = (string) $attachment_id;

	if (trim($value) === $id) {
		return true;
	}

	$needles = array(
		'i:' . $id . ';',
		':"' . $id . '";',
		'"id":' . $id,
		'"ID":' . $id,
		'"attachment_id":' . $id,
	);

	return vfwp_intranet_media_audit_contains_any_needle($value, $needles);
}

function vfwp_intranet_media_audit_contains_any_needle($value, $needles) {
	if (!is_scalar($value)) {
		return false;
	}

	$value = (string) $value;
	if ($value === '') {
		return false;
	}

	foreach ($needles as $needle) {
		if (!is_string($needle) || $needle === '') {
			continue;
		}

		if (strpos($value, $needle) !== false) {
			return true;
		}
	}

	return false;
}

function vfwp_intranet_media_audit_is_ignored_option_name($option_name) {
	$option_name = (string) $option_name;

	return strpos($option_name, '_transient_') === 0
		|| strpos($option_name, '_site_transient_') === 0
		|| in_array($option_name, array('vfwp_people_sync_stats', 'vfwp_teams_sync_stats'), true);
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
