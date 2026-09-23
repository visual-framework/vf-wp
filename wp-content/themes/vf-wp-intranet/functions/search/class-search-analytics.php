<?php
/**
 * Lightweight search analytics for the theme search system.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Analytics {
	const CLEAR_ACTION = 'vfwp_intranet_search_clear_analytics';
	const CLEANUP_TRANSIENT = 'vfwp_intranet_search_analytics_cleanup';
	const ADMIN_ROWS_PER_PAGE = 20;
	const RECENT_PER_PAGE = 20;

	/**
	 * Analytics row created for the current frontend request.
	 *
	 * @var int
	 */
	private static $last_logged_id = 0;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var string
	 */
	private $table_name;

	/**
	 * @param wpdb|null $db WordPress database object.
	 */
	public function __construct($db = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->table_name = VFWP_Intranet_Search_Schema::analytics_table_name();
	}

	/**
	 * Register admin actions.
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_action('admin_post_' . self::CLEAR_ACTION, array(__CLASS__, 'handle_clear_action'));
	}

	/**
	 * Log one frontend search request.
	 *
	 * @param mixed $query Raw search query.
	 * @param array $filters Search filters.
	 * @param int   $page Page number.
	 * @param int   $per_page Results per page.
	 * @param array $response Search response.
	 * @return bool
	 */
	public function log_search($query, array $filters, $page, $per_page, array $response) {
		static $logged_keys = array();

		$settings = $this->get_settings();

		if (empty($settings['enabled'])) {
			return false;
		}

		if ((int) $page !== 1) {
			return false;
		}

		if (!empty($settings['exclude_admins']) && current_user_can(VFWP_Intranet_Search_Settings::ADMIN_CAPABILITY)) {
			return false;
		}

		$parser = new VFWP_Intranet_Search_Query_Parser();
		$parsed_query = $parser->parse($query);
		$query_text = trim(is_scalar($query) ? (string) $query : '');
		$normalized_query = isset($parsed_query['normalized']) ? trim((string) $parsed_query['normalized']) : '';

		if ($query_text === '' || $normalized_query === '') {
			return false;
		}

		$this->maybe_record_correction_click($normalized_query);

		$result_count = isset($response['pagination']['total']) ? max(0, (int) $response['pagination']['total']) : 0;
		$filters = $this->sanitize_filters($filters);
		$filters_json = wp_json_encode($filters);
		$filters_hash = md5((string) $filters_json);
		$log_key = md5($normalized_query . '|' . $filters_hash . '|' . (string) $page);

		if (isset($logged_keys[$log_key])) {
			return false;
		}

		$logged_keys[$log_key] = true;
		$this->maybe_cleanup();

		$result = $this->wpdb->insert(
			$this->table_name,
			array(
				'query_text'       => $this->limit_string($query_text, 240),
				'normalized_query' => $this->limit_string($normalized_query, 191),
				'result_count'     => $result_count,
				'filters_hash'     => $filters_hash,
				'filters_json'     => is_string($filters_json) ? $filters_json : '{}',
				'page_number'      => 1,
				'per_page'         => max(1, (int) $per_page),
				'searched_at'      => current_time('mysql', true),
				'user_email'       => !empty($settings['track_user_email']) ? $this->get_current_user_email() : '',
				'source'           => 'frontend',
				'is_corrected'     => 0,
				'corrected_to'     => '',
			),
			array('%s', '%s', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d', '%s')
		);

		if (false !== $result) {
			self::$last_logged_id = (int) $this->wpdb->insert_id;
		}

		return false !== $result;
	}

	/**
	 * Return signed URL parameters for tracking a clicked spelling correction.
	 *
	 * @param mixed $corrected_query Corrected search query.
	 * @return array
	 */
	public static function get_correction_tracking_args($corrected_query) {
		$analytics_id = (int) self::$last_logged_id;

		if ($analytics_id <= 0 || !class_exists('VFWP_Intranet_Search_Query_Parser')) {
			return array();
		}

		$parsed = (new VFWP_Intranet_Search_Query_Parser())->parse($corrected_query);
		$normalized_query = isset($parsed['normalized']) ? trim((string) $parsed['normalized']) : '';

		if ($normalized_query === '') {
			return array();
		}

		return array(
			'search_correction_id'  => $analytics_id,
			'search_correction_sig' => wp_hash($analytics_id . '|' . $normalized_query, 'nonce'),
		);
	}

	/**
	 * Handle analytics clearing from Settings > Search.
	 *
	 * @return void
	 */
	public static function handle_clear_action() {
		if (!current_user_can(VFWP_Intranet_Search_Settings::ADMIN_CAPABILITY)) {
			wp_die(esc_html__('You do not have permission to manage search analytics.', 'vfwp'));
		}

		check_admin_referer(self::CLEAR_ACTION);

		$analytics = new self();
		$analytics->clear();

		$redirect_url = add_query_arg(
			array(
				'page'                      => 'vfwp-intranet-search',
				'tab'                       => 'analytics',
				'vfwp_search_index_notice'  => 'started',
				'vfwp_search_index_message' => rawurlencode(__('Search analytics data cleared.', 'vfwp')),
			),
			admin_url('options-general.php')
		);

		wp_safe_redirect($redirect_url);
		exit;
	}

	/**
	 * Return dashboard data for admin rendering.
	 *
	 * @param int   $recent_page Recent-search event-log page.
	 * @param array $report_pages Other analytics report pages.
	 * @return array
	 */
	public function get_dashboard_data($recent_page = 1, array $report_pages = array()) {
		$recent = $this->get_recent_searches($recent_page, self::RECENT_PER_PAGE);
		$top_queries = $this->get_top_queries(isset($report_pages['top']) ? $report_pages['top'] : 1);
		$zero_results = $this->get_zero_result_queries(isset($report_pages['zero']) ? $report_pages['zero'] : 1);

		return array(
			'summary'                    => $this->get_summary(),
			'trends'                     => array(
				'daily'   => $this->get_trend_data('daily', 30),
				'weekly'  => $this->get_trend_data('weekly', 12),
				'monthly' => $this->get_trend_data('monthly', 12),
			),
			'top_queries'                => $top_queries['rows'],
			'top_queries_pagination' => $top_queries['pagination'],
			'zero_results'               => $zero_results['rows'],
			'zero_results_pagination' => $zero_results['pagination'],
			'recent'                     => $recent['rows'],
			'recent_pagination'          => $recent['pagination'],
		);
	}

	/**
	 * Delete all analytics rows.
	 *
	 * @return bool
	 */
	public function clear() {
		return false !== $this->wpdb->query("TRUNCATE TABLE {$this->table_name}");
	}

	/**
	 * Delete analytics rows older than the configured retention window.
	 *
	 * @return int
	 */
	public function cleanup() {
		$settings = $this->get_settings();
		$retention_days = max(1, (int) $settings['retention_days']);

		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->table_name} WHERE searched_at < DATE_SUB(UTC_TIMESTAMP(), INTERVAL %d DAY)",
				$retention_days
			)
		);

		return false === $result ? 0 : (int) $result;
	}

	/**
	 * Return analytics settings from the main search settings option.
	 *
	 * @return array
	 */
	private function get_settings() {
		return class_exists('VFWP_Intranet_Search_Settings')
			? VFWP_Intranet_Search_Settings::get_analytics_settings()
			: array(
				'enabled'        => 1,
				'exclude_admins' => 0,
				'track_user_email' => 0,
				'retention_days' => 180,
			);
	}

	/**
	 * Return headline analytics counts.
	 *
	 * @return array
	 */
	private function get_summary() {
		$row = $this->wpdb->get_row(
			"SELECT
				COUNT(*) AS total_searches,
				SUM(CASE WHEN result_count > 0 OR is_corrected = 1 THEN 1 ELSE 0 END) AS searches_with_results,
				SUM(CASE WHEN result_count = 0 AND is_corrected = 0 THEN 1 ELSE 0 END) AS zero_result_searches,
				SUM(CASE WHEN is_corrected = 1 THEN 1 ELSE 0 END) AS corrected_searches,
				COUNT(DISTINCT normalized_query) AS unique_queries,
				MAX(searched_at) AS last_search_at
			FROM {$this->table_name}",
			ARRAY_A
		);

		if (!is_array($row)) {
			$row = array();
		}

		$total_searches = isset($row['total_searches']) ? (int) $row['total_searches'] : 0;
		$searches_with_results = isset($row['searches_with_results']) ? (int) $row['searches_with_results'] : 0;

		return array(
			'total_searches'      => $total_searches,
			'searches_with_results' => $searches_with_results,
			'results_rate'        => $total_searches > 0 ? round(($searches_with_results / $total_searches) * 100, 1) : 0,
			'zero_result_searches' => isset($row['zero_result_searches']) ? (int) $row['zero_result_searches'] : 0,
			'corrected_searches'  => isset($row['corrected_searches']) ? (int) $row['corrected_searches'] : 0,
			'unique_queries'      => isset($row['unique_queries']) ? (int) $row['unique_queries'] : 0,
			'last_search_at'      => !empty($row['last_search_at']) ? (string) $row['last_search_at'] : '',
		);
	}

	/**
	 * Return most searched queries.
	 *
	 * @return array
	 */
	private function get_top_queries($page) {
		return $this->get_grouped_query_rows('', $page, self::ADMIN_ROWS_PER_PAGE);
	}

	/**
	 * Return most common zero-result queries.
	 *
	 * @return array
	 */
	private function get_zero_result_queries($page) {
		return $this->get_grouped_query_rows('result_count = 0 AND is_corrected = 0', $page, self::ADMIN_ROWS_PER_PAGE);
	}

	/**
	 * Return grouped query analytics.
	 *
	 * @param string $extra_condition Safe additional condition SQL.
	 * @param int    $page Current page.
	 * @param int    $per_page Rows per page.
	 * @return array
	 */
	private function get_grouped_query_rows($extra_condition, $page, $per_page) {
		$settings = $this->get_settings();
		$retention_days = max(1, (int) $settings['retention_days']);
		$per_page = max(1, min(100, (int) $per_page));
		$where_sql = 'WHERE searched_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL %d DAY)';

		if ($extra_condition !== '') {
			$where_sql .= ' AND ' . $extra_condition;
		}

		$total = (int) $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT COUNT(DISTINCT normalized_query) FROM {$this->table_name} {$where_sql}",
				$retention_days
			)
		);
		$total_pages = $total > 0 ? (int) ceil($total / $per_page) : 1;
		$page = min($total_pages, max(1, (int) $page));
		$offset = ($page - 1) * $per_page;
		$sql = "
			SELECT
				normalized_query,
				MIN(query_text) AS display_query,
				COUNT(*) AS searches,
				SUM(CASE WHEN result_count = 0 AND is_corrected = 0 THEN 1 ELSE 0 END) AS zero_result_searches,
				ROUND(AVG(result_count), 1) AS average_results,
				MAX(searched_at) AS last_searched_at
			FROM {$this->table_name}
			{$where_sql}
			GROUP BY normalized_query
			ORDER BY searches DESC, last_searched_at DESC
			LIMIT %d OFFSET %d
		";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $retention_days, $per_page, $offset), ARRAY_A);

		return array(
			'rows'       => is_array($rows) ? $rows : array(),
			'pagination' => array(
				'page'        => $page,
				'per_page'    => $per_page,
				'total'       => $total,
				'total_pages' => $total_pages,
			),
		);
	}

	/**
	 * Return recent searches.
	 *
	 * @param int $page Current page.
	 * @param int $per_page Rows per page.
	 * @return array
	 */
	private function get_recent_searches($page, $per_page) {
		$settings = $this->get_settings();
		$retention_days = max(1, (int) $settings['retention_days']);
		$per_page = max(1, min(100, (int) $per_page));
		$total = (int) $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT COUNT(*)
				FROM {$this->table_name}
				WHERE searched_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL %d DAY)",
				$retention_days
			)
		);
		$total_pages = $total > 0 ? (int) ceil($total / $per_page) : 1;
		$page = min($total_pages, max(1, (int) $page));
		$offset = ($page - 1) * $per_page;
		$sql = "
			SELECT query_text, normalized_query, result_count, searched_at, user_email, is_corrected, corrected_to
			FROM {$this->table_name}
			WHERE searched_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL %d DAY)
			ORDER BY searched_at DESC, id DESC
			LIMIT %d OFFSET %d
		";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $retention_days, $per_page, $offset), ARRAY_A);

		return array(
			'rows'       => is_array($rows) ? $rows : array(),
			'pagination' => array(
				'page'        => $page,
				'per_page'    => $per_page,
				'total'       => $total,
				'total_pages' => $total_pages,
			),
		);
	}

	/**
	 * Return continuous daily, weekly, or monthly analytics buckets.
	 *
	 * @param string $period daily|weekly|monthly.
	 * @param int    $bucket_count Number of buckets.
	 * @return array
	 */
	private function get_trend_data($period, $bucket_count) {
		$period = in_array($period, array('daily', 'weekly', 'monthly'), true) ? $period : 'daily';
		$bucket_count = max(1, min(60, (int) $bucket_count));
		$timezone = new DateTimeZone('UTC');

		if ($period === 'weekly') {
			$current = new DateTimeImmutable('monday this week', $timezone);
			$start = $current->modify('-' . ($bucket_count - 1) . ' weeks');
			$bucket_sql = 'DATE_SUB(DATE(searched_at), INTERVAL WEEKDAY(searched_at) DAY)';
			$step = '+1 week';
			$label_format = 'j M';
		} elseif ($period === 'monthly') {
			$current = new DateTimeImmutable('first day of this month 00:00:00', $timezone);
			$start = $current->modify('-' . ($bucket_count - 1) . ' months');
			$bucket_sql = "DATE_FORMAT(searched_at, '%Y-%m-01')";
			$step = '+1 month';
			$label_format = 'M Y';
		} else {
			$current = new DateTimeImmutable('today', $timezone);
			$start = $current->modify('-' . ($bucket_count - 1) . ' days');
			$bucket_sql = 'DATE(searched_at)';
			$step = '+1 day';
			$label_format = 'j M';
		}

		$sql = "SELECT
				{$bucket_sql} AS bucket_start,
				COUNT(*) AS total_searches,
				SUM(CASE WHEN result_count > 0 OR is_corrected = 1 THEN 1 ELSE 0 END) AS searches_with_results,
				SUM(CASE WHEN result_count = 0 AND is_corrected = 0 THEN 1 ELSE 0 END) AS zero_result_searches
			FROM {$this->table_name}
			WHERE searched_at >= %s
			GROUP BY bucket_start
			ORDER BY bucket_start ASC";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $start->format('Y-m-d H:i:s')), ARRAY_A);
		$rows_by_date = array();

		foreach ((array) $rows as $row) {
			if (!empty($row['bucket_start'])) {
				$rows_by_date[(string) $row['bucket_start']] = $row;
			}
		}

		$buckets = array();
		$cursor = $start;

		for ($index = 0; $index < $bucket_count; $index++) {
			$key = $cursor->format('Y-m-d');
			$row = isset($rows_by_date[$key]) ? $rows_by_date[$key] : array();
			$total = isset($row['total_searches']) ? (int) $row['total_searches'] : 0;
			$with_results = isset($row['searches_with_results']) ? (int) $row['searches_with_results'] : 0;

			$buckets[] = array(
				'key'                 => $key,
				'label'               => wp_date($label_format, $cursor->getTimestamp(), $timezone),
				'total_searches'      => $total,
				'searches_with_results' => $with_results,
				'zero_result_searches' => isset($row['zero_result_searches']) ? (int) $row['zero_result_searches'] : 0,
				'results_rate'        => $total > 0 ? round(($with_results / $total) * 100, 1) : 0,
			);
			$cursor = $cursor->modify($step);
		}

		return $buckets;
	}

	/**
	 * Mark the originating no-result event when its signed correction is clicked.
	 *
	 * @param string $normalized_query Corrected normalized query.
	 * @return void
	 */
	private function maybe_record_correction_click($normalized_query) {
		$analytics_id = isset($_GET['search_correction_id']) ? absint(wp_unslash($_GET['search_correction_id'])) : 0;
		$signature = isset($_GET['search_correction_sig']) ? sanitize_text_field(wp_unslash($_GET['search_correction_sig'])) : '';

		if ($analytics_id <= 0 || $signature === '') {
			return;
		}

		$expected_signature = wp_hash($analytics_id . '|' . (string) $normalized_query, 'nonce');

		if (!hash_equals($expected_signature, $signature)) {
			return;
		}

		$this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE {$this->table_name}
				SET is_corrected = 1, corrected_to = %s
				WHERE id = %d AND result_count = 0 AND is_corrected = 0",
				$this->limit_string($normalized_query, 191),
				$analytics_id
			)
		);
	}

	/**
	 * Run retention cleanup at most once daily.
	 *
	 * @return void
	 */
	private function maybe_cleanup() {
		if (get_transient(self::CLEANUP_TRANSIENT)) {
			return;
		}

		set_transient(self::CLEANUP_TRANSIENT, 1, DAY_IN_SECONDS);
		$this->cleanup();
	}

	/**
	 * Return the current logged-in user's email address when available.
	 *
	 * @return string
	 */
	private function get_current_user_email() {
		$user = wp_get_current_user();

		if (!$user || empty($user->user_email) || !is_email($user->user_email)) {
			return '';
		}

		return sanitize_email($user->user_email);
	}

	/**
	 * Sanitize service filters before storing them.
	 *
	 * @param array $filters Raw filters.
	 * @return array
	 */
	private function sanitize_filters(array $filters) {
		$sanitized = array();

		foreach (array('object_types', 'post_types') as $key) {
			if (empty($filters[$key])) {
				continue;
			}

			$sanitized[$key] = array_values(array_unique(array_filter(array_map('sanitize_key', (array) $filters[$key]))));
		}

		return $sanitized;
	}

	/**
	 * Return a bounded string.
	 *
	 * @param string $text Text.
	 * @param int    $length Max length.
	 * @return string
	 */
	private function limit_string($text, $length) {
		$text = (string) $text;

		if (function_exists('mb_substr')) {
			return (string) mb_substr($text, 0, (int) $length, 'UTF-8');
		}

		return substr($text, 0, (int) $length);
	}
}
