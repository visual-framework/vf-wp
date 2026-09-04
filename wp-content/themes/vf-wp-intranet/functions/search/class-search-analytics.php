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
	const MAX_ADMIN_ROWS = 20;

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
			),
			array('%s', '%s', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s')
		);

		return false !== $result;
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
	 * @return array
	 */
	public function get_dashboard_data() {
		return array(
			'summary'      => $this->get_summary(),
			'top_queries'  => $this->get_top_queries(),
			'zero_results' => $this->get_zero_result_queries(),
			'recent'       => $this->get_recent_searches(),
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
				SUM(CASE WHEN result_count = 0 THEN 1 ELSE 0 END) AS zero_result_searches,
				COUNT(DISTINCT normalized_query) AS unique_queries,
				MAX(searched_at) AS last_search_at
			FROM {$this->table_name}",
			ARRAY_A
		);

		if (!is_array($row)) {
			$row = array();
		}

		return array(
			'total_searches'      => isset($row['total_searches']) ? (int) $row['total_searches'] : 0,
			'zero_result_searches' => isset($row['zero_result_searches']) ? (int) $row['zero_result_searches'] : 0,
			'unique_queries'      => isset($row['unique_queries']) ? (int) $row['unique_queries'] : 0,
			'last_search_at'      => !empty($row['last_search_at']) ? (string) $row['last_search_at'] : '',
		);
	}

	/**
	 * Return most searched queries.
	 *
	 * @return array
	 */
	private function get_top_queries() {
		return $this->get_grouped_query_rows('', self::MAX_ADMIN_ROWS);
	}

	/**
	 * Return most common zero-result queries.
	 *
	 * @return array
	 */
	private function get_zero_result_queries() {
		return $this->get_grouped_query_rows('WHERE result_count = 0', self::MAX_ADMIN_ROWS);
	}

	/**
	 * Return grouped query analytics.
	 *
	 * @param string $where_sql Safe WHERE SQL.
	 * @param int    $limit Limit.
	 * @return array
	 */
	private function get_grouped_query_rows($where_sql, $limit) {
		$sql = "
			SELECT
				normalized_query,
				MIN(query_text) AS display_query,
				COUNT(*) AS searches,
				SUM(CASE WHEN result_count = 0 THEN 1 ELSE 0 END) AS zero_result_searches,
				ROUND(AVG(result_count), 1) AS average_results,
				MAX(searched_at) AS last_searched_at
			FROM {$this->table_name}
			{$where_sql}
			GROUP BY normalized_query
			ORDER BY searches DESC, last_searched_at DESC
			LIMIT %d
		";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, max(1, (int) $limit)), ARRAY_A);

		return is_array($rows) ? $rows : array();
	}

	/**
	 * Return recent searches.
	 *
	 * @return array
	 */
	private function get_recent_searches() {
		$sql = "
			SELECT query_text, normalized_query, result_count, searched_at, user_email
			FROM {$this->table_name}
			ORDER BY searched_at DESC, id DESC
			LIMIT %d
		";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, self::MAX_ADMIN_ROWS), ARRAY_A);

		return is_array($rows) ? $rows : array();
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
