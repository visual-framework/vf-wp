<?php
/**
 * Fast indexed autosuggestions for the intranet search form.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Suggestions {
	const ACTION = 'vfwp_intranet_search_suggestions';
	const MAX_LIMIT = 8;
	const TITLE_LIMIT = 5;
	const PHRASE_SCAN_LIMIT = 60;
	const DID_YOU_MEAN_LIMIT = 5;
	const CACHE_TTL = 120;
	const SUGGESTION_ALGORITHM_VERSION = 4;
	const SPELLING_ALGORITHM_VERSION = 22;

	/** @var bool */
	private static $assets_enqueued = false;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var VFWP_Intranet_Search_Query_Parser
	 */
	private $query_parser;

	/** @var bool */
	private $collect_diagnostics = false;

	/** @var array */
	private $did_you_mean_diagnostics = array();

	/**
	 * @param wpdb|null                              $db Database handle.
	 * @param VFWP_Intranet_Search_Query_Parser|null $query_parser Query parser.
	 */
	public function __construct($db = null, $query_parser = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->query_parser = $query_parser ? $query_parser : new VFWP_Intranet_Search_Query_Parser();
	}

	/**
	 * Register frontend and AJAX hooks.
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
		add_action('vf/plugin/before_render/vf_wp_hero_group', array(__CLASS__, 'enable_for_hero_plugin'));
		add_filter('render_block', array(__CLASS__, 'enable_for_hero_search'), 20, 2);
		add_action('wp_ajax_' . self::ACTION, array(__CLASS__, 'handle_ajax_request'));
		add_action('wp_ajax_nopriv_' . self::ACTION, array(__CLASS__, 'handle_ajax_request'));
	}

	/**
	 * Enqueue autosuggest script where the intranet search form is rendered.
	 *
	 * @return void
	 */
	public static function enqueue_assets() {
		self::enqueue_script();
	}

	/**
	 * Enable autocomplete when a rendered VF Hero contains its optional search form.
	 *
	 * @param string $block_content Rendered block HTML.
	 * @param array  $block Block data.
	 * @return string
	 */
	public static function enable_for_hero_search($block_content, $block) {
		if (
			is_admin()
			|| !is_string($block_content)
			|| strpos($block_content, 'vf-hero') === false
			|| strpos($block_content, 'vf-form--search') === false
			|| !preg_match('/<input\b[^>]*\bname=["\']s["\']/i', $block_content)
		) {
			return $block_content;
		}

		self::enqueue_script();

		return $block_content;
	}

	/**
	 * Enable autocomplete for the legacy VF Hero container renderer.
	 *
	 * VF containers are included directly by VF_Plugin::render(), so their HTML
	 * does not pass through WordPress's render_block filter.
	 *
	 * @param mixed $plugin VF Hero plugin instance.
	 * @return void
	 */
	public static function enable_for_hero_plugin($plugin) {
		if (is_admin() || !is_object($plugin) || !method_exists($plugin, 'post') || !function_exists('get_field')) {
			return;
		}

		$post = $plugin->post();

		if (!$post instanceof WP_Post || !get_field('vf_hero_search', $post->ID)) {
			return;
		}

		self::enqueue_script();
	}

	/**
	 * Enqueue and configure the shared autocomplete script once per request.
	 *
	 * @return void
	 */
	private static function enqueue_script() {
		if (self::$assets_enqueued) {
			return;
		}

		self::$assets_enqueued = true;

		$handle = 'vfwp-intranet-search-suggestions';
		$script_path = get_stylesheet_directory() . '/scripts/search-suggestions.js';
		$script_version = file_exists($script_path) ? filemtime($script_path) : wp_get_theme()->get('Version');
		wp_enqueue_script(
			$handle,
			get_stylesheet_directory_uri() . '/scripts/search-suggestions.js',
			array(),
			$script_version,
			true
		);
		wp_localize_script(
			$handle,
			'vfwpSearchSuggestions',
			array(
				'ajaxUrl'         => admin_url('admin-ajax.php'),
				'action'          => self::ACTION,
				'nonce'           => wp_create_nonce(self::ACTION),
				'analyticsAction' => VFWP_Intranet_Search_Analytics::AUTOCOMPLETE_ACTION,
				'analyticsNonce'  => wp_create_nonce(VFWP_Intranet_Search_Analytics::AUTOCOMPLETE_ACTION),
				'minLength'       => 2,
				'lookupMinLength' => 3,
				'debounceMs'      => 120,
				'cacheTtlMs'      => self::CACHE_TTL * 1000,
				'searchForLabel'  => __('Search for "%s"', 'vfwp'),
				'searchInputLabel' => __('Search', 'vfwp'),
			)
		);
	}

	/**
	 * Return autosuggestions to the frontend search box.
	 *
	 * @return void
	 */
	public static function handle_ajax_request() {
		$nonce = isset($_GET['nonce']) ? sanitize_text_field(wp_unslash($_GET['nonce'])) : '';

		if ($nonce === '' || !wp_verify_nonce($nonce, self::ACTION)) {
			wp_send_json_error(array('message' => __('Invalid search suggestion request.', 'vfwp')), 403);
		}

		$query = isset($_GET['q']) ? wp_unslash($_GET['q']) : '';
		$limit = isset($_GET['limit']) ? absint($_GET['limit']) : self::MAX_LIMIT;
		$selected_filters = isset($_GET[VFWP_Intranet_Search_Frontend::FILTER_PARAM])
			? (array) wp_unslash($_GET[VFWP_Intranet_Search_Frontend::FILTER_PARAM])
			: array();
		$filters = VFWP_Intranet_Search_Frontend::get_filters_for_raw_filter_values($selected_filters);
		$service = new self();

		wp_send_json_success(array(
			'suggestions' => $service->suggest($query, $filters, $limit),
		));
	}

	/**
	 * Suggest indexed titles and curated phrase searches.
	 *
	 * @param mixed $query Raw visitor query.
	 * @param array $filters Search filters.
	 * @param int   $limit Maximum suggestions.
	 * @return array
	 */
	public function suggest($query, array $filters = array(), $limit = self::MAX_LIMIT) {
		$limit = min(self::MAX_LIMIT, max(1, (int) $limit));
		$parsed_query = $this->query_parser->parse($query);
		$normalized_query = isset($parsed_query['normalized']) ? trim((string) $parsed_query['normalized']) : '';

		if ($normalized_query === '' || $this->length($normalized_query) < 2) {
			return array();
		}

		$filters = $this->normalize_filters($filters);
		$cache_key = 'suggest_' . md5(wp_json_encode(array(
			'version' => self::SUGGESTION_ALGORITHM_VERSION,
			'query'   => $normalized_query,
			'filters' => $filters,
			'limit'   => $limit,
			'schema'  => VFWP_Intranet_Search_Schema::VERSION,
		)));
		$cached = $this->get_cached_suggestions($cache_key);

		if (is_array($cached)) {
			return $cached;
		}

		$suggestions = array();
		$seen = array();
		$is_short_people_name = $this->is_short_people_name_query($normalized_query, $filters);

		$this->append_suggestion($suggestions, $seen, $this->get_search_action_suggestion($parsed_query), $limit);

		if ($this->should_run_indexed_suggestion_lookup($parsed_query)) {
			foreach ($this->get_title_suggestions($parsed_query, $filters, min(self::TITLE_LIMIT, $limit)) as $suggestion) {
				$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
			}
		} elseif ($is_short_people_name) {
			foreach ($this->get_short_people_suggestions($normalized_query, min(self::TITLE_LIMIT, $limit)) as $suggestion) {
				$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
			}
		}

		$remaining_limit = $limit - count($suggestions);

		if (!$is_short_people_name) {
			foreach ($this->get_phrase_suggestions($parsed_query, $filters, $remaining_limit) as $suggestion) {
				$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
			}
		}

		$this->set_cached_suggestions($cache_key, $suggestions, self::CACHE_TTL);

		return $suggestions;
	}

	/**
	 * Return Did-you-mean decisions without using the frontend cache.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Search filters.
	 * @param int   $limit Maximum suggestions.
	 * @return array
	 */
	public function diagnose_did_you_mean($query, array $filters = array(), $limit = self::DID_YOU_MEAN_LIMIT) {
		$this->did_you_mean_diagnostics = array();
		$this->collect_diagnostics = true;
		$suggestions = $this->did_you_mean($query, $filters, $limit);
		$this->collect_diagnostics = false;

		return array(
			'suggestions' => $suggestions,
			'candidates' => $this->did_you_mean_diagnostics,
		);
	}

	/**
	 * Return corrected query suggestions for a no-results search.
	 *
	 * @param mixed $query Raw visitor query.
	 * @param array $filters Search filters.
	 * @param int   $limit Maximum suggestions.
	 * @return array
	 */
	public function did_you_mean($query, array $filters = array(), $limit = self::DID_YOU_MEAN_LIMIT) {
		$limit = min(self::DID_YOU_MEAN_LIMIT, max(1, (int) $limit));
		$parsed_query = $this->query_parser->parse($query);
		$normalized_query = isset($parsed_query['normalized']) ? trim((string) $parsed_query['normalized']) : '';

		if ($normalized_query === '') {
			return array();
		}

		$filters = $this->normalize_filters($filters);
		$is_short_people_name = $this->is_short_people_name_query($normalized_query, $filters);
		$correction_settings = class_exists('VFWP_Intranet_Search_Settings')
			? VFWP_Intranet_Search_Settings::get_did_you_mean_settings()
			: array(
				'minimum_confidence' => 0.68,
				'people_minimum_confidence' => 0.84,
				'winning_margin' => 0.06,
				'preferred_corrections' => array(),
				'blocked_suggestions' => array(),
			);

		if ($this->length($normalized_query) < 3 && !$is_short_people_name) {
			return array();
		}

		$cache_key = 'did_you_mean_' . md5(wp_json_encode(array(
			'query'   => $normalized_query,
			'filters' => $filters,
			'limit'   => $limit,
				'schema'  => VFWP_Intranet_Search_Schema::VERSION,
				'algorithm' => self::SPELLING_ALGORITHM_VERSION,
				'rules' => $correction_settings,
			)));
		$cached = $this->collect_diagnostics ? false : wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		$suggestions = array();
		$seen = array();
		$people_corrections = array();
		$query_term_count = !empty($parsed_query['all_terms']) ? count((array) $parsed_query['all_terms']) : 0;

		$preferred = $this->get_preferred_correction($normalized_query, $correction_settings);

		if ($preferred) {
			$this->append_did_you_mean_suggestion(
				$suggestions,
				$seen,
				array(
					'query' => $preferred['to'],
					'label' => $preferred['to'],
					'confidence' => 1.0,
					'candidate_type' => 'preferred',
					'is_people_match' => false,
				),
				$filters,
				$limit,
				$correction_settings
			);

			if (!empty($suggestions)) {
				wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);
				return $suggestions;
			}
		}

		if (in_array('people', $filters['post_types'], true) && class_exists('VFWP_Intranet_Search_People_Name_Repository')) {
			$people_names = new VFWP_Intranet_Search_People_Name_Repository($this->wpdb, $this->query_parser);

				foreach ($people_names->find_suggestions($normalized_query, $limit) as $correction) {
				$correction['distance'] = $this->damerau_levenshtein($normalized_query, isset($correction['query']) ? (string) $correction['query'] : '');
				$correction['frequency'] = isset($correction['score']) ? (int) round((float) $correction['score'] * 100) : 0;
				$correction['length'] = $this->length(isset($correction['query']) ? (string) $correction['query'] : '');
					$correction['candidate_type'] = 'people';
					$correction['confidence'] = isset($correction['score']) ? (float) $correction['score'] : 0.0;
					$correction['is_people_match'] = true;
				$people_corrections[] = $correction;
			}
		}

		// Two-character corrections are intentionally restricted to indexed
		// People names. General vocabulary is too ambiguous at this length.
		if ($is_short_people_name) {
			foreach ($people_corrections as $correction) {
					$this->append_did_you_mean_suggestion($suggestions, $seen, $correction, $filters, $limit, $correction_settings);
			}

			wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);
			return $suggestions;
		}

		$candidates = $this->get_did_you_mean_candidates($parsed_query);
		$ranked_corrections = array();

		foreach ($this->get_phrase_corrections($normalized_query, $candidates['phrases']) as $correction) {
			$correction['candidate_type'] = 'phrase';
			$correction['is_people_match'] = false;
			$ranked_corrections[] = $correction;
		}

		foreach ($this->get_compound_corrections($parsed_query, $candidates['compounds']) as $correction) {
			$correction['candidate_type'] = 'compound';
			$correction['is_people_match'] = false;
			$ranked_corrections[] = $correction;
		}

		foreach ($this->get_term_corrections($parsed_query, $candidates['terms']) as $correction) {
			$correction['candidate_type'] = 'term';
			$correction['is_people_match'] = false;
			$ranked_corrections[] = $correction;
		}

		$ranked_corrections = array_merge($ranked_corrections, $people_corrections);
		$ranked_corrections = $this->rank_confident_corrections(
			$normalized_query,
			$ranked_corrections,
			$correction_settings,
			$query_term_count
		);

		foreach (array_slice($ranked_corrections, 0, 20) as $correction) {
			$this->append_did_you_mean_suggestion($suggestions, $seen, $correction, $filters, $limit, $correction_settings);
		}

		wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);

		return $suggestions;
	}

	/**
	 * Whether a query may use the conservative two-letter People-name lookup.
	 *
	 * @param string $query Normalized query.
	 * @param array  $filters Normalized filters.
	 * @return bool
	 */
	private function is_short_people_name_query($query, array $filters) {
		return $this->length($query) === 2
			&& preg_match('/^\p{L}{2}$/u', (string) $query) === 1
			&& in_array('people', $filters['post_types'], true);
	}

	/**
	 * Return one validated, less restrictive query for a no-results search.
	 *
	 * The fallback removes one term only and validates that the resulting query
	 * has an indexed result, keeping the broader search useful rather than
	 * silently changing normal AND matching into OR matching.
	 *
	 * @param mixed $query Raw visitor query.
	 * @param array $filters Search filters.
	 * @return array
	 */
	public function get_broader_search($query, array $filters = array()) {
		if (
			!class_exists('VFWP_Intranet_Search_Settings')
			|| !VFWP_Intranet_Search_Settings::is_broader_search_enabled()
		) {
			return array();
		}

		$parsed_query = $this->query_parser->parse($query);
		$terms = !empty($parsed_query['all_terms']) ? array_values((array) $parsed_query['all_terms']) : array();

		if (count($terms) < 2 || !empty($parsed_query['has_protected_phrases'])) {
			return array();
		}

		$filters = $this->normalize_filters($filters);
		$cache_key = 'broader_search_' . md5(wp_json_encode(array(
			'query'   => isset($parsed_query['normalized']) ? (string) $parsed_query['normalized'] : '',
			'filters' => $filters,
			'schema'  => VFWP_Intranet_Search_Schema::VERSION,
			'algorithm' => self::SPELLING_ALGORITHM_VERSION,
		)));
		$cached = wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		$labels = $this->get_original_term_labels($query, $terms);
		$search_service = new VFWP_Intranet_Search_Service($this->wpdb, $this->query_parser);
		$seen = array();
		$result = array();

		foreach ($terms as $index => $term) {
			$candidate_terms = $terms;
			unset($candidate_terms[$index]);
			$candidate_terms = array_values(array_unique($candidate_terms));
			$candidate_query = trim(implode(' ', $candidate_terms));

			if ($candidate_query === '' || isset($seen[$candidate_query])) {
				continue;
			}

			$seen[$candidate_query] = true;

			if (!$search_service->has_results($candidate_query, $filters)) {
				continue;
			}

			$candidate_labels = array();

			foreach ($candidate_terms as $candidate_term) {
				$candidate_labels[] = isset($labels[$candidate_term]) ? $labels[$candidate_term] : $candidate_term;
			}

			$result = array(
				'query' => $candidate_query,
				'label' => trim(implode(' ', $candidate_labels)),
			);

			break;
		}

		wp_cache_set($cache_key, $result, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);

		return $result;
	}

	/**
	 * Return cached autosuggestions from object cache.
	 *
	 * @param string $cache_key Cache key.
	 * @return array|false
	 */
	private function get_cached_suggestions($cache_key) {
		$cache_key = (string) $cache_key;
		$cached = wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		return false;
	}

	/**
	 * Store autosuggestions in object cache.
	 *
	 * @param string $cache_key Cache key.
	 * @param array  $suggestions Suggestions.
	 * @param int    $ttl Time to live in seconds.
	 * @return void
	 */
	private function set_cached_suggestions($cache_key, array $suggestions, $ttl) {
		$ttl = max(30, (int) $ttl);

		wp_cache_set((string) $cache_key, $suggestions, 'vfwp_intranet_search', $ttl);
	}

	/**
	 * Determine whether typed input is selective enough for indexed lookup.
	 *
	 * @param array $parsed_query Parsed query.
	 * @return bool
	 */
	private function should_run_indexed_suggestion_lookup(array $parsed_query) {
		if (!empty($parsed_query['boolean_query'])) {
			return true;
		}

		$normalized_query = isset($parsed_query['normalized']) ? trim((string) $parsed_query['normalized']) : '';

		return $this->length($normalized_query) >= 3;
	}

	/**
	 * Return the free-text search action shown at the top of the suggestion list.
	 *
	 * @param array $parsed_query Parsed query.
	 * @return array
	 */
	private function get_search_action_suggestion(array $parsed_query) {
		$query = trim((string) $parsed_query['normalized']);

		return array(
			'type'        => 'search',
			'label'       => sprintf(
				/* translators: %s: typed search query. */
				__('Search for "%s"', 'vfwp'),
				$query
			),
			'value'       => $query,
			'url'         => '',
			'is_primary'  => true,
		);
	}

	/**
	 * Return matching indexed title suggestions.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $filters Normalized filters.
	 * @param int   $limit Result limit.
	 * @return array
	 */
	private function get_title_suggestions(array $parsed_query, array $filters, $limit) {
		if ($limit < 1) {
			return array();
		}

		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$normalized_query = (string) $parsed_query['normalized'];
		$prefix = $this->wpdb->esc_like(strtolower($normalized_query)) . '%';
		$score_parts = array(
			'IF(LOWER(title) = %s, 500, 0)',
			'IF(LOWER(title) LIKE %s, 50, 0)',
			"IF(post_type = 'page', 25, 0)",
		);
		$score_params = array($normalized_query, $prefix);
		$match_conditions = array();
		$match_params = array();
		$where_params = array('post', 'publish', 'public');

		if (!empty($parsed_query['boolean_query'])) {
			$score_parts[] = 'MATCH(title) AGAINST (%s IN BOOLEAN MODE)';
			$score_params[] = $parsed_query['boolean_query'];
			$match_conditions[] = 'MATCH(title) AGAINST (%s IN BOOLEAN MODE)';
			$match_params[] = $parsed_query['boolean_query'];
		}

		if (empty($match_conditions)) {
			return array();
		}

		$where_sql = $this->build_base_where_sql($filters, $where_params);
		$where_sql .= ' AND (' . implode(' OR ', $match_conditions) . ')';
		$sql = "
			SELECT object_id, object_type, post_type, title, url,
				(" . implode(' + ', $score_parts) . ") AS suggestion_score
			FROM {$table_name}
			{$where_sql}
			ORDER BY suggestion_score DESC, title ASC, object_id ASC
			LIMIT %d
		";
		$params = array_merge($score_params, $where_params, $match_params, array((int) $limit));
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $params), ARRAY_A);

		if (!is_array($rows)) {
			return array();
		}

		$suggestions = array();

		foreach ($rows as $row) {
			$title = isset($row['title']) ? trim((string) $row['title']) : '';
			$url = isset($row['url']) ? esc_url_raw((string) $row['url']) : '';
			$post_type = isset($row['post_type']) ? sanitize_key($row['post_type']) : '';

			if ($title === '') {
				continue;
			}

			$external_domain_label = $post_type === 'teams' ? $this->get_external_domain_label($url) : '';

			$suggestions[] = array(
				'type'        => 'result',
				'label'       => $title,
				'value'       => $title,
				'url'         => $url,
				'object_id'   => isset($row['object_id']) ? (int) $row['object_id'] : 0,
				'object_type' => isset($row['object_type']) ? sanitize_key($row['object_type']) : 'post',
				'post_type'   => $post_type,
				'badge_label' => $this->get_post_type_label($post_type),
				'external_domain_label' => $external_domain_label,
				'opens_in_new_tab'      => $external_domain_label !== '',
			);
		}

		return $suggestions;
	}

	/**
	 * Return direct People results for a conservative two-letter name lookup.
	 *
	 * @param string $query Normalized two-letter query.
	 * @param int    $limit Maximum results.
	 * @return array
	 */
	private function get_short_people_suggestions($query, $limit) {
		if ($limit < 1 || !class_exists('VFWP_Intranet_Search_People_Name_Repository')) {
			return array();
		}

		$people_names = new VFWP_Intranet_Search_People_Name_Repository($this->wpdb, $this->query_parser);
		$name_matches = $people_names->find_suggestions($query, $limit);

		if (empty($name_matches)) {
			return array();
		}

		$object_ids = array_values(array_unique(array_filter(array_map(static function ($match) {
			return isset($match['object_id']) ? (int) $match['object_id'] : 0;
		}, $name_matches))));

		if (empty($object_ids)) {
			return array();
		}

		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$placeholders = implode(', ', array_fill(0, count($object_ids), '%d'));
		$params = array_merge(array('post', 'people', 'publish', 'public'), $object_ids);
		$sql = "SELECT object_id, object_type, post_type, title, url
			FROM {$table_name}
			WHERE object_type = %s
				AND post_type = %s
				AND post_status = %s
				AND visibility = %s
				AND object_id IN ({$placeholders})";
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $params), ARRAY_A);
		$rows_by_id = array();

		foreach ((array) $rows as $row) {
			$rows_by_id[(int) $row['object_id']] = $row;
		}

		$suggestions = array();

		foreach ($name_matches as $name_match) {
			$object_id = isset($name_match['object_id']) ? (int) $name_match['object_id'] : 0;

			if (!isset($rows_by_id[$object_id])) {
				continue;
			}

			$row = $rows_by_id[$object_id];
			$title = trim((string) $row['title']);

			if ($title === '') {
				continue;
			}

			$suggestions[] = array(
				'type'        => 'result',
				'label'       => $title,
				'value'       => $title,
				'url'         => esc_url_raw((string) $row['url']),
				'object_id'   => $object_id,
				'object_type' => 'post',
				'post_type'   => 'people',
				'badge_label' => $this->get_post_type_label('people'),
				'external_domain_label' => '',
				'opens_in_new_tab'      => false,
			);
		}

		return $suggestions;
	}

	/**
	 * Return configured and indexed keyword phrase suggestions.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $filters Normalized filters.
	 * @param int   $limit Result limit.
	 * @return array
	 */
	private function get_phrase_suggestions(array $parsed_query, array $filters, $limit) {
		if ($limit < 1) {
			return array();
		}

		$phrases = array();

		if (class_exists('VFWP_Intranet_Search_Settings')) {
			foreach (VFWP_Intranet_Search_Settings::get_exact_phrases() as $phrase) {
				$this->append_phrase_candidate($phrases, $phrase, $parsed_query);
			}
		}

		if ($this->should_run_indexed_suggestion_lookup($parsed_query)) {
			foreach ($this->get_indexed_keyword_phrase_candidates($parsed_query, $filters) as $phrase) {
				$this->append_phrase_candidate($phrases, $phrase, $parsed_query);
			}
		}

		usort($phrases, array($this, 'sort_phrase_suggestions'));

		$suggestions = array();

		foreach (array_slice($phrases, 0, $limit) as $phrase) {
			$suggestions[] = array(
				'type'        => 'phrase',
				'label'       => sprintf(
					/* translators: %s: suggested search phrase. */
					__('Search for "%s"', 'vfwp'),
					$phrase['label']
				),
				'value'       => $phrase['label'],
				'url'         => '',
				'is_phrase'   => true,
			);
		}

		return $suggestions;
	}

	/**
	 * Return ACF keyword phrase candidates from matching indexed rows.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $filters Normalized filters.
	 * @return array
	 */
	private function get_indexed_keyword_phrase_candidates(array $parsed_query, array $filters) {
		if (empty($parsed_query['boolean_query'])) {
			return array();
		}

		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$where_params = array('post', 'publish', 'public');
		$where_sql = $this->build_base_where_sql($filters, $where_params);
		$sql = "
			SELECT acf_keywords
			FROM {$table_name}
			{$where_sql}
				AND acf_keywords <> ''
				AND MATCH(acf_keywords) AGAINST (%s IN BOOLEAN MODE)
			ORDER BY MATCH(acf_keywords) AGAINST (%s IN BOOLEAN MODE) DESC
			LIMIT %d
		";
		$params = array_merge($where_params, array($parsed_query['boolean_query'], $parsed_query['boolean_query'], (int) self::PHRASE_SCAN_LIMIT));
		$rows = $this->wpdb->get_col($this->wpdb->prepare($sql, $params));

		if (!is_array($rows)) {
			return array();
		}

		$phrases = array();

		foreach ($rows as $keyword_text) {
			foreach (preg_split('/[,;\r\n|]+/u', (string) $keyword_text) as $phrase) {
				$phrases[] = $phrase;
			}
		}

		return $phrases;
	}

	/**
	 * Append a phrase candidate if it matches the typed query.
	 *
	 * @param array $phrases Phrase candidates.
	 * @param mixed $phrase Raw phrase.
	 * @param array $parsed_query Parsed query.
	 * @return void
	 */
	private function append_phrase_candidate(array &$phrases, $phrase, array $parsed_query) {
		if (!is_scalar($phrase)) {
			return;
		}

		$label = trim((string) $phrase);
		$normalized = $this->query_parser->parse($label)['normalized'];

		if ($label === '' || $normalized === '' || !$this->phrase_matches_query($normalized, $parsed_query)) {
			return;
		}

		$key = strtolower($normalized);

		if (isset($phrases[$key])) {
			return;
		}

		$phrases[$key] = array(
			'label'      => $label,
			'normalized' => $normalized,
			'is_prefix'  => strpos($normalized, (string) $parsed_query['normalized']) === 0 ? 1 : 0,
			'length'     => $this->length($normalized),
		);
	}

	/**
	 * Determine if a phrase should be suggested for the typed query.
	 *
	 * @param string $normalized_phrase Normalized phrase.
	 * @param array  $parsed_query Parsed query.
	 * @return bool
	 */
	private function phrase_matches_query($normalized_phrase, array $parsed_query) {
		$normalized_query = (string) $parsed_query['normalized'];

		if (strpos($normalized_phrase, $normalized_query) === 0) {
			return true;
		}

		$terms = !empty($parsed_query['terms']) ? (array) $parsed_query['terms'] : (array) $parsed_query['all_terms'];

		if (empty($terms)) {
			return false;
		}

		foreach ($terms as $term) {
			if (!$this->contains_whole_term($normalized_phrase, $term)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Append a suggestion while avoiding duplicate labels.
	 *
	 * @param array $suggestions Suggestions.
	 * @param array $seen Seen labels.
	 * @param array $suggestion Suggestion.
	 * @param int   $limit Limit.
	 * @return void
	 */
	private function append_suggestion(array &$suggestions, array &$seen, array $suggestion, $limit) {
		if (count($suggestions) >= $limit || empty($suggestion['label'])) {
			return;
		}

		$key = strtolower((string) $this->query_parser->parse($suggestion['value'])['normalized']);

		if ($key === '') {
			return;
		}

		// Keep a direct result beside the primary search action when its title
		// exactly matches the entered query.
		if (isset($suggestion['type']) && $suggestion['type'] === 'result') {
			$key = 'result:' . $key;
		}

		if (isset($seen[$key])) {
			return;
		}

		$seen[$key] = true;
		$suggestions[] = $suggestion;
	}

	/**
	 * Sort phrase suggestions by prefix match and shorter phrase.
	 *
	 * @param array $a First phrase.
	 * @param array $b Second phrase.
	 * @return int
	 */
	private function sort_phrase_suggestions($a, $b) {
		if ($a['is_prefix'] !== $b['is_prefix']) {
			return $b['is_prefix'] - $a['is_prefix'];
		}

		if ($a['length'] !== $b['length']) {
			return $a['length'] - $b['length'];
		}

		return strcasecmp($a['label'], $b['label']);
	}

	/**
	 * Normalize SearchService-style filters for suggestion SQL.
	 *
	 * @param array $filters Raw filters.
	 * @return array
	 */
	private function normalize_filters(array $filters) {
		$object_types = isset($filters['object_types']) ? (array) $filters['object_types'] : array('post');
		$object_types = array_values(array_intersect(array_filter(array_map('sanitize_key', $object_types)), array('post')));

		if (empty($object_types)) {
			$object_types = array('post');
		}

		$post_types = isset($filters['post_types']) ? (array) $filters['post_types'] : array();
		$post_types = array_values(array_filter(array_map('sanitize_key', $post_types)));

		if (empty($post_types) && class_exists('VFWP_Intranet_Search_Settings')) {
			$post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();
		}

		return array(
			'object_types' => $object_types,
			'post_types'   => array_values(array_unique($post_types)),
		);
	}

	/**
	 * Build base indexed object WHERE clause.
	 *
	 * @param array $filters Normalized filters.
	 * @param array $params SQL params.
	 * @return string
	 */
	private function build_base_where_sql(array $filters, array &$params) {
		$conditions = array(
			'object_type = %s',
			'post_status = %s',
			'visibility = %s',
		);

		if (!empty($filters['post_types'])) {
			$conditions[] = 'post_type IN (' . implode(',', array_fill(0, count($filters['post_types']), '%s')) . ')';
			$params = array_merge($params, $filters['post_types']);
		}

		return 'WHERE ' . implode(' AND ', $conditions);
	}

	/**
	 * Whole-token phrase matching for normalized text.
	 *
	 * @param string $text Text.
	 * @param string $term Term.
	 * @return bool
	 */
	private function contains_whole_term($text, $term) {
		return preg_match('/(^|\s)' . preg_quote((string) $term, '/') . '($|\s)/u', (string) $text) === 1;
	}

	/**
	 * Return a translated singular post type label for suggestion badges.
	 *
	 * @param string $post_type Post type key.
	 * @return string
	 */
	private function get_post_type_label($post_type) {
		$post_type = sanitize_key($post_type);
		$labels = array(
			'page'           => __('Page', 'vfwp'),
			'teams'          => __('Team', 'vfwp'),
			'people'         => __('Person', 'vfwp'),
			'documents'      => __('Document', 'vfwp'),
			'community-blog' => __('Announcement', 'vfwp'),
			'insites'        => __('News', 'vfwp'),
			'events'         => __('Event', 'vfwp'),
			'vf_event'       => __('Event', 'vfwp'),
			'training'       => __('Training', 'vfwp'),
		);

		if (isset($labels[$post_type])) {
			return $labels[$post_type];
		}

		$post_type_object = get_post_type_object($post_type);

		if ($post_type_object && !empty($post_type_object->labels->singular_name)) {
			return $post_type_object->labels->singular_name;
		}

		return $post_type;
	}

	/**
	 * Return the external website label used by team result pills.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	private function get_external_domain_label($url) {
		$host = wp_parse_url((string) $url, PHP_URL_HOST);

		if (!$host) {
			return '';
		}

		return __('External website', 'vfwp');
	}

	/**
	 * Return dictionary and configured phrase candidates for no-results suggestions.
	 *
	 * @param array $parsed_query Parsed query.
	 * @return array
	 */
	private function get_did_you_mean_candidates(array $parsed_query) {
		$candidates = array(
			'terms'       => array(),
			'phrases'     => array(),
			'compounds'   => array(),
		);

		if (class_exists('VFWP_Intranet_Search_Settings')) {
			foreach (VFWP_Intranet_Search_Settings::get_exact_phrases() as $phrase) {
				$this->append_did_you_mean_text($candidates, $phrase, true);
			}
		}

		if (class_exists('VFWP_Intranet_Search_Spelling_Repository')) {
			$spelling_repository = new VFWP_Intranet_Search_Spelling_Repository($this->wpdb, $this->query_parser);
			$query_terms = !empty($parsed_query['all_terms']) ? (array) $parsed_query['all_terms'] : array();
			$normalized_query = isset($parsed_query['normalized']) ? (string) $parsed_query['normalized'] : '';

			foreach ($spelling_repository->find_phrase_candidates($normalized_query) as $phrase) {
				$this->append_did_you_mean_text(
					$candidates,
					isset($phrase['display_term']) ? $phrase['display_term'] : '',
					true
				);
			}

			foreach ($query_terms as $query_term) {
				foreach ($spelling_repository->find_candidates((string) $query_term) as $candidate) {
					$frequency = (int) $candidate['document_frequency']
						+ ((int) $candidate['title_frequency'] * 4)
						+ ((int) $candidate['keyword_frequency'] * 3);
					$this->append_did_you_mean_term(
						$candidates['terms'],
						isset($candidate['term']) ? $candidate['term'] : '',
						isset($candidate['display_term']) ? $candidate['display_term'] : '',
						max(1, $frequency),
						$candidate
					);
				}

				$candidates['compounds'][(string) $query_term] = $spelling_repository->find_compound_candidates((string) $query_term);
			}
		}

		return $candidates;
	}

	/**
	 * Add normalized terms and optional phrase candidates.
	 *
	 * @param array $candidates Candidate store.
	 * @param mixed $text Raw text.
	 * @param bool  $include_phrase Whether to include full phrase.
	 * @return void
	 */
	private function append_did_you_mean_text(array &$candidates, $text, $include_phrase) {
		if (!is_scalar($text)) {
			return;
		}

		$label = trim(wp_strip_all_tags((string) $text));

		if ($label === '') {
			return;
		}

		$normalized = $this->query_parser->normalize_search_text($label);

		if ($normalized === '') {
			return;
		}

		if ($include_phrase && strpos($normalized, ' ') !== false && $this->length($normalized) <= 90) {
			$candidates['phrases'][$normalized] = array(
				'label'      => $label,
				'normalized' => $normalized,
			);
		}

		preg_match_all('/[\p{L}\p{N}]+/u', $normalized, $matches);

		foreach ($matches[0] as $term) {
			if ($this->length($term) < 3) {
				continue;
			}

			$this->append_did_you_mean_term($candidates['terms'], $term, $term, 1);
		}
	}

	/**
	 * Add or strengthen one normalized term candidate.
	 *
	 * @param array  $terms Candidate terms.
	 * @param mixed  $term Normalized term.
	 * @param mixed  $label Display label.
	 * @param int    $frequency Frequency score.
	 * @return void
	 */
	private function append_did_you_mean_term(array &$terms, $term, $label, $frequency, array $source = array()) {
		$term = $this->query_parser->normalize_search_text(is_scalar($term) ? (string) $term : '');

		if ($term === '' || strpos($term, ' ') !== false || $this->length($term) < 3) {
			return;
		}

		$label = is_scalar($label) ? trim((string) $label) : '';

		if (!isset($terms[$term])) {
			$terms[$term] = array(
				'frequency' => 0,
				'label'     => $label !== '' ? $label : $term,
				'document_frequency' => 0,
				'title_frequency' => 0,
				'keyword_frequency' => 0,
			);
		}

		$terms[$term]['frequency'] += max(1, (int) $frequency);
		$terms[$term]['document_frequency'] = max((int) $terms[$term]['document_frequency'], isset($source['document_frequency']) ? (int) $source['document_frequency'] : 0);
		$terms[$term]['title_frequency'] = max((int) $terms[$term]['title_frequency'], isset($source['title_frequency']) ? (int) $source['title_frequency'] : 0);
		$terms[$term]['keyword_frequency'] = max((int) $terms[$term]['keyword_frequency'], isset($source['keyword_frequency']) ? (int) $source['keyword_frequency'] : 0);

		if ($terms[$term]['label'] === $term && $label !== '') {
			$terms[$term]['label'] = $label;
		}
	}

	/**
	 * Return phrase-level corrections closest to the entered query.
	 *
	 * @param string $normalized_query Normalized query.
	 * @param array  $phrases Phrase candidates.
	 * @return array
	 */
	private function get_phrase_corrections($normalized_query, array $phrases) {
		$corrections = array();

		foreach ($phrases as $phrase) {
			$normalized_phrase = isset($phrase['normalized']) ? (string) $phrase['normalized'] : '';

			if ($normalized_phrase === '' || $normalized_phrase === $normalized_query) {
				continue;
			}

			$distance = $this->damerau_levenshtein($normalized_query, $normalized_phrase);
			$max_distance = $this->get_max_phrase_distance($normalized_query, $normalized_phrase);

			if ($distance > $max_distance) {
				continue;
			}

			$corrections[] = array(
				'query'    => $normalized_phrase,
				'label'    => isset($phrase['label']) ? (string) $phrase['label'] : $normalized_phrase,
				'distance' => $distance,
				'length'   => $this->length($normalized_phrase),
			);
		}

		usort($corrections, array($this, 'sort_did_you_mean_corrections'));

		return array_slice($corrections, 0, 8);
	}

	/**
	 * Return term-level corrections closest to the entered query.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $terms Candidate terms.
	 * @return array
	 */
	private function get_term_corrections(array $parsed_query, array $terms) {
		$query_terms = !empty($parsed_query['all_terms']) ? (array) $parsed_query['all_terms'] : array();
		$correctable_terms = array_fill_keys(
			array_merge(
				!empty($parsed_query['terms']) ? (array) $parsed_query['terms'] : array(),
				!empty($parsed_query['protected_phrase_terms']) ? (array) $parsed_query['protected_phrase_terms'] : array()
			),
			true
		);

		if (empty($query_terms)) {
			return array();
		}

		$original_labels = $this->get_original_term_labels(
			isset($parsed_query['raw']) ? $parsed_query['raw'] : '',
			$query_terms
		);
		$choices = array();
		$changed_indexes = array();

		foreach ($query_terms as $index => $query_term) {
			$query_term = (string) $query_term;
			$original_label = isset($original_labels[$query_term]) ? $original_labels[$query_term] : $query_term;

			// Preserve stopwords and terms ignored by query parsing. They are not
			// represented reliably in the title/keyword spelling dictionary.
			if ($this->length($query_term) < 3 || !isset($correctable_terms[$query_term])) {
				$choices[$index] = array(array(
					'term'      => $query_term,
					'label'     => $original_label,
					'distance'  => 0,
					'frequency' => 0,
				));
				continue;
			}

			$matches = $this->get_ranked_term_matches($query_term, $terms, 5);

			if (!empty($matches)) {
				$choices[$index] = $matches;

				if ($matches[0]['term'] !== $query_term) {
					$changed_indexes[] = $index;
				}

				continue;
			}

			$choices[$index] = array(array(
				'term'      => $query_term,
				'label'     => $original_label,
				'distance'  => 0,
				'frequency' => 0,
			));
		}

		if (empty($changed_indexes)) {
			return array();
		}

		$selections = array(array_fill(0, count($choices), 0));

		// Keep the best correction as the first candidate, then vary one
		// misspelled term at a time to create closely related alternatives.
		foreach ($changed_indexes as $changed_index) {
			for ($match_index = 1; $match_index < count($choices[$changed_index]); $match_index++) {
				$selection = array_fill(0, count($choices), 0);
				$selection[$changed_index] = $match_index;
				$selections[] = $selection;
			}
		}

		$corrections = array();
		$seen = array();
		$normalized_query = (string) $parsed_query['normalized'];

		foreach ($selections as $selection) {
			$correction = $this->build_term_correction($choices, $selection);

			if (
				empty($correction['query'])
				|| $correction['query'] === $normalized_query
				|| isset($seen[$correction['query']])
			) {
				continue;
			}

			$seen[$correction['query']] = true;
			$corrections[] = $correction;
		}

		usort($corrections, array($this, 'sort_did_you_mean_corrections'));

		return array_slice($corrections, 0, 8);
	}

	/**
	 * Return corrections that split one concatenated word into two indexed terms.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $compounds Compound candidates keyed by original term.
	 * @return array
	 */
	private function get_compound_corrections(array $parsed_query, array $compounds) {
		$query_terms = !empty($parsed_query['all_terms']) ? array_values((array) $parsed_query['all_terms']) : array();

		if (empty($query_terms) || empty($compounds)) {
			return array();
		}

		$corrections = array();
		$seen = array();

		foreach ($query_terms as $index => $query_term) {
			if (empty($compounds[$query_term]) || !is_array($compounds[$query_term])) {
				continue;
			}

			foreach ($compounds[$query_term] as $compound) {
				if (empty($compound['terms']) || !is_array($compound['terms'])) {
					continue;
				}

				$corrected_terms = $query_terms;
				array_splice($corrected_terms, (int) $index, 1, array_values($compound['terms']));
				$corrected_query = trim(implode(' ', $corrected_terms));

				if ($corrected_query === '' || isset($seen[$corrected_query])) {
					continue;
				}

				$seen[$corrected_query] = true;
				$corrections[] = array(
					'query'     => $corrected_query,
					'label'     => $corrected_query,
					'distance'  => 1,
					'frequency' => isset($compound['frequency']) ? (int) $compound['frequency'] : 0,
					'length'    => $this->length($corrected_query),
				);
			}
		}

		usort($corrections, array($this, 'sort_did_you_mean_corrections'));

		return array_slice($corrections, 0, 8);
	}

	/**
	 * Build one full corrected query from per-term candidate selections.
	 *
	 * @param array $choices Per-query-term choices.
	 * @param array $selection Selected choice index for each query term.
	 * @return array
	 */
	private function build_term_correction(array $choices, array $selection) {
		$corrected_terms = array();
		$corrected_labels = array();
		$total_distance = 0;
		$total_frequency = 0;
		$same_character_matches = 0;
		$title_frequency = 0;
		$keyword_frequency = 0;
		$document_frequency = 0;

		foreach ($choices as $index => $term_choices) {
			$choice_index = isset($selection[$index]) ? (int) $selection[$index] : 0;
			$choice = isset($term_choices[$choice_index]) ? $term_choices[$choice_index] : $term_choices[0];
			$corrected_terms[] = (string) $choice['term'];
			$corrected_labels[] = (string) $choice['label'];
			$total_distance += isset($choice['distance']) ? (int) $choice['distance'] : 0;
			$total_frequency += isset($choice['frequency']) ? (int) $choice['frequency'] : 0;
			$same_character_matches += isset($choice['same_characters']) ? (int) $choice['same_characters'] : 0;
			$title_frequency += isset($choice['title_frequency']) ? (int) $choice['title_frequency'] : 0;
			$keyword_frequency += isset($choice['keyword_frequency']) ? (int) $choice['keyword_frequency'] : 0;
			$document_frequency += isset($choice['document_frequency']) ? (int) $choice['document_frequency'] : 0;
		}

		$corrected_query = trim(implode(' ', array_values(array_unique($corrected_terms))));
		$corrected_label = trim(implode(' ', $corrected_labels));

		return array(
			'query'     => $corrected_query,
			'label'     => $corrected_label !== '' ? $corrected_label : $corrected_query,
			'distance'  => $total_distance,
			'frequency' => $total_frequency,
			'same_character_matches' => $same_character_matches,
			'title_frequency' => $title_frequency,
			'keyword_frequency' => $keyword_frequency,
			'document_frequency' => $document_frequency,
			'length'    => $this->length($corrected_query),
		);
	}

	/**
	 * Return ranked indexed matches for one query term.
	 *
	 * @param string $query_term Query term.
	 * @param array  $terms Candidate terms keyed by term.
	 * @param int    $limit Maximum matches.
	 * @return array
	 */
	private function get_ranked_term_matches($query_term, array $terms, $limit = 5) {
		$matches = array();
		$query_length = $this->length($query_term);

		foreach ($terms as $term => $term_data) {
			$frequency = is_array($term_data) && isset($term_data['frequency']) ? (int) $term_data['frequency'] : (int) $term_data;
			$label = is_array($term_data) && !empty($term_data['label']) ? (string) $term_data['label'] : $term;

			if (abs($this->length($term) - $query_length) > 2) {
				continue;
			}

			$distance = $this->damerau_levenshtein($query_term, $term);

			if ($distance > $this->get_max_term_distance($query_term)) {
				continue;
			}

			$matches[] = array(
				'term'      => $term,
				'label'     => $label,
				'distance'  => $distance,
				'same_characters' => $this->has_same_characters($query_term, $term) ? 1 : 0,
				'frequency' => (int) $frequency,
				'title_frequency' => is_array($term_data) && isset($term_data['title_frequency']) ? (int) $term_data['title_frequency'] : 0,
				'keyword_frequency' => is_array($term_data) && isset($term_data['keyword_frequency']) ? (int) $term_data['keyword_frequency'] : 0,
				'document_frequency' => is_array($term_data) && isset($term_data['document_frequency']) ? (int) $term_data['document_frequency'] : 0,
			);
		}

		usort($matches, static function ($a, $b) {
			if ((int) $a['distance'] !== (int) $b['distance']) {
				return (int) $a['distance'] - (int) $b['distance'];
			}

			if ((int) $a['same_characters'] !== (int) $b['same_characters']) {
				return (int) $b['same_characters'] - (int) $a['same_characters'];
			}

			if ((int) $a['frequency'] !== (int) $b['frequency']) {
				return (int) $b['frequency'] - (int) $a['frequency'];
			}

			return strcasecmp((string) $a['term'], (string) $b['term']);
		});

		return array_slice($matches, 0, max(1, (int) $limit));
	}

	/**
	 * Whether two normalized words contain exactly the same characters.
	 *
	 * This promotes likely ordering mistakes such as "soter" to "store"
	 * above equally distant words that also substitute or remove letters.
	 *
	 * @param string $left First word.
	 * @param string $right Second word.
	 * @return bool
	 */
	private function has_same_characters($left, $right) {
		$left_characters = preg_split('//u', (string) $left, -1, PREG_SPLIT_NO_EMPTY);
		$right_characters = preg_split('//u', (string) $right, -1, PREG_SPLIT_NO_EMPTY);

		if (!is_array($left_characters) || !is_array($right_characters) || count($left_characters) !== count($right_characters)) {
			return false;
		}

		sort($left_characters, SORT_STRING);
		sort($right_characters, SORT_STRING);

		return $left_characters === $right_characters;
	}

	/**
	 * Apply confidence, source, ambiguity, and People-intent rules.
	 *
	 * @param string $original_query Normalized visitor query.
	 * @param array  $corrections Raw corrections.
	 * @param array  $settings Correction settings.
	 * @param int    $query_term_count Query term count.
	 * @return array
	 */
	private function rank_confident_corrections($original_query, array $corrections, array $settings, $query_term_count) {
		$people = array();
		$general = array();
		$general_threshold = isset($settings['minimum_confidence']) ? (float) $settings['minimum_confidence'] : 0.68;
		$people_threshold = isset($settings['people_minimum_confidence']) ? (float) $settings['people_minimum_confidence'] : 0.84;

		foreach ($corrections as $correction) {
			if (empty($correction['query'])) {
				continue;
			}

			$correction['confidence'] = isset($correction['confidence'])
				? min(1, max(0, (float) $correction['confidence']))
				: $this->calculate_correction_confidence($original_query, $correction);
			$threshold = !empty($correction['is_people_match']) ? $people_threshold : $general_threshold;

			if ($this->is_blocked_correction($correction['query'], $settings)) {
				$this->record_correction_diagnostic($correction, 'rejected', 'blocked');
				continue;
			}

			if ((float) $correction['confidence'] < $threshold) {
				$this->record_correction_diagnostic($correction, 'rejected', 'below_confidence');
				continue;
			}

			if (!empty($correction['is_people_match'])) {
				$people[] = $correction;
			} else {
				$general[] = $correction;
			}
		}

		$margin = isset($settings['winning_margin']) ? (float) $settings['winning_margin'] : 0.06;
		$people = $this->apply_ambiguity_gate($people, $margin);
		$general = $this->apply_ambiguity_gate($general, $margin);

		if ((int) $query_term_count === 1 && !empty($people) && !empty($general)) {
			$people_lead = (float) $people[0]['confidence'] - (float) $general[0]['confidence'];
			$required_people_lead = max(0.12, max(0, $margin) * 2);

			if ($people_lead >= $required_people_lead) {
				foreach ($general as $correction) {
					$this->record_correction_diagnostic($correction, 'rejected', 'people_clear_winner');
				}
				$general = array();
			} else {
				foreach ($people as $correction) {
					$this->record_correction_diagnostic($correction, 'rejected', 'ordinary_word_preferred');
				}
				$people = array();
			}
		}

		$ranked = array_merge($people, $general);

		foreach ($ranked as &$correction) {
			$is_people = !empty($correction['is_people_match']);
			$intent_boost = (int) $query_term_count > 1
				? ($is_people ? 0.04 : 0.0)
				: ($is_people ? 0.0 : 0.04);
			$correction['display_score'] = min(1.04, (float) $correction['confidence'] + $intent_boost);
		}
		unset($correction);

		usort($ranked, static function ($a, $b) use ($query_term_count) {
			if ((int) $query_term_count > 1 && !empty($a['is_people_match']) !== !empty($b['is_people_match'])) {
				return !empty($a['is_people_match']) ? -1 : 1;
			}

			if ((float) $a['display_score'] !== (float) $b['display_score']) {
				return (float) $a['display_score'] < (float) $b['display_score'] ? 1 : -1;
			}

			if ((float) $a['confidence'] !== (float) $b['confidence']) {
				return (float) $a['confidence'] < (float) $b['confidence'] ? 1 : -1;
			}

			return strcasecmp((string) $a['query'], (string) $b['query']);
		});

		return $ranked;
	}

	/**
	 * Calculate bounded confidence for one general correction.
	 *
	 * @param string $original_query Original normalized query.
	 * @param array  $correction Correction data.
	 * @return float
	 */
	private function calculate_correction_confidence($original_query, array $correction) {
		$type = isset($correction['candidate_type']) ? (string) $correction['candidate_type'] : 'term';
		$target = isset($correction['query']) ? (string) $correction['query'] : '';
		$distance = isset($correction['distance']) ? max(0, (int) $correction['distance']) : $this->damerau_levenshtein($original_query, $target);
		$length = max(1, $this->length($original_query), $this->length($target));
		$similarity = max(0, 1 - ($distance / $length));

		if ('phrase' === $type) {
			return round(min(1, ($similarity * 0.82) + 0.16), 4);
		}

		if ('compound' === $type) {
			// Splitting an accidentally joined word into an indexed exact phrase is
			// stronger evidence than substituting it with a different dictionary
			// word (for example, "scienceday" -> "science day").
			return round(min(1, ($similarity * 0.84) + 0.16), 4);
		}

		$title_frequency = isset($correction['title_frequency']) ? (int) $correction['title_frequency'] : 0;
		$keyword_frequency = isset($correction['keyword_frequency']) ? (int) $correction['keyword_frequency'] : 0;
		$document_frequency = isset($correction['document_frequency']) ? (int) $correction['document_frequency'] : 0;
		$frequency = isset($correction['frequency']) ? max(0, (int) $correction['frequency']) : 0;
		$source_quality = $title_frequency > 0 ? 1.0 : ($keyword_frequency > 0 ? 0.9 : ($document_frequency > 0 ? 0.72 : 0.55));
		$frequency_quality = min(1, log(1 + $frequency) / log(51));
		$same_character_bonus = !empty($correction['same_character_matches']) ? 0.08 : 0.0;

		return round(min(1, ($similarity * 0.72) + ($source_quality * 0.18) + ($frequency_quality * 0.10) + $same_character_bonus), 4);
	}

	/**
	 * Reject a weak group when no candidate is a clear winner.
	 *
	 * @param array $corrections Corrections from one source family.
	 * @param float $margin Required confidence lead.
	 * @return array
	 */
	private function apply_ambiguity_gate(array $corrections, $margin) {
		usort($corrections, static function ($a, $b) {
			return (float) $a['confidence'] < (float) $b['confidence'] ? 1 : ((float) $a['confidence'] > (float) $b['confidence'] ? -1 : 0);
		});

		if (
			count($corrections) > 1
			&& (float) $corrections[0]['confidence'] < 0.82
			&& !$this->has_strong_correction_evidence($corrections[0])
			&& ((float) $corrections[0]['confidence'] - (float) $corrections[1]['confidence']) < max(0, (float) $margin)
		) {
			foreach ($corrections as $correction) {
				$this->record_correction_diagnostic($correction, 'rejected', 'ambiguous');
			}

			return array();
		}

		if (!empty($corrections)) {
			$winner = $corrections[0];
			$minimum_relative_confidence = max(0, (float) $winner['confidence'] - max(0.03, (float) $margin));
			$winner_is_people = !empty($winner['is_people_match']);
			$winner_has_same_characters = !empty($winner['same_character_matches']);

			$corrections = array_values(array_filter($corrections, function ($correction, $index) use ($minimum_relative_confidence, $winner_is_people, $winner_has_same_characters) {
				if ($index === 0) {
					return true;
				}

				$has_comparable_evidence = $winner_is_people
					? (float) $correction['confidence'] >= $minimum_relative_confidence
					: (!$winner_has_same_characters && $this->has_strong_correction_evidence($correction) && (float) $correction['confidence'] >= $minimum_relative_confidence);

				if ($has_comparable_evidence) {
					return true;
				}

				$this->record_correction_diagnostic($correction, 'rejected', 'weaker_than_winner');
				return false;
			}, ARRAY_FILTER_USE_BOTH));
		}

		return $corrections;
	}

	/**
	 * Whether a candidate has enough corpus evidence to survive a close score.
	 *
	 * @param array $correction Correction data.
	 * @return bool
	 */
	private function has_strong_correction_evidence(array $correction) {
		$type = isset($correction['candidate_type']) ? (string) $correction['candidate_type'] : '';

		return !empty($correction['same_character_matches'])
			|| in_array($type, array('preferred', 'phrase', 'compound'), true)
			|| (isset($correction['title_frequency']) && (int) $correction['title_frequency'] >= 2)
			|| (isset($correction['keyword_frequency']) && (int) $correction['keyword_frequency'] >= 2)
			|| (isset($correction['confidence']) && (float) $correction['confidence'] >= 0.90);
	}

	/**
	 * Return an administrator-curated correction for an exact query.
	 *
	 * @param string $query Normalized query.
	 * @param array  $settings Correction settings.
	 * @return array|null
	 */
	private function get_preferred_correction($query, array $settings) {
		foreach (isset($settings['preferred_corrections']) ? (array) $settings['preferred_corrections'] : array() as $correction) {
			if (!empty($correction['from']) && !empty($correction['to']) && (string) $correction['from'] === (string) $query) {
				return $correction;
			}
		}

		return null;
	}

	/**
	 * Whether a complete suggested query is blocked by an administrator.
	 *
	 * @param string $query Suggested query.
	 * @param array  $settings Correction settings.
	 * @return bool
	 */
	private function is_blocked_correction($query, array $settings) {
		$normalized = $this->query_parser->normalize_search_text((string) $query);
		$blocked = isset($settings['blocked_suggestions']) ? (array) $settings['blocked_suggestions'] : array();

		return $normalized !== '' && in_array($normalized, $blocked, true);
	}

	/**
	 * Store one diagnostics row when requested by the admin ranking test.
	 *
	 * @param array  $correction Correction data.
	 * @param string $status Decision status.
	 * @param string $reason Machine-readable reason.
	 * @return void
	 */
	private function record_correction_diagnostic(array $correction, $status, $reason) {
		if (!$this->collect_diagnostics) {
			return;
		}

		$this->did_you_mean_diagnostics[] = array(
			'query' => isset($correction['query']) ? (string) $correction['query'] : '',
			'type' => isset($correction['candidate_type']) ? (string) $correction['candidate_type'] : 'term',
			'confidence' => isset($correction['confidence']) ? (float) $correction['confidence'] : 0.0,
			'distance' => isset($correction['distance']) ? (int) $correction['distance'] : 0,
			'frequency' => isset($correction['frequency']) ? (int) $correction['frequency'] : 0,
			'source' => !empty($correction['title_frequency']) ? 'title' : (!empty($correction['keyword_frequency']) ? 'keyword' : (!empty($correction['document_frequency']) ? 'content' : (isset($correction['candidate_type']) ? (string) $correction['candidate_type'] : 'unknown'))),
			'status' => sanitize_key($status),
			'reason' => sanitize_key($reason),
		);
	}

	/**
	 * Add a correction if it leads to actual indexed results.
	 *
	 * @param array $suggestions Suggestions.
	 * @param array $seen Seen queries.
	 * @param array $correction Correction data.
	 * @param array $filters Search filters.
	 * @param int   $limit Limit.
	 * @param array $settings Correction settings.
	 * @return void
	 */
	private function append_did_you_mean_suggestion(array &$suggestions, array &$seen, array $correction, array $filters, $limit, array $settings) {
		if (count($suggestions) >= $limit || empty($correction['query'])) {
			return;
		}

		$query = trim((string) $correction['query']);
		$key = strtolower((string) $this->query_parser->parse($query)['normalized']);

		if ($key === '' || isset($seen[$key])) {
			return;
		}

		if ($this->is_blocked_correction($query, $settings)) {
			$seen[$key] = true;
			$this->record_correction_diagnostic($correction, 'rejected', 'blocked');
			return;
		}

		if (!empty($correction['is_people_match'])) {
			if (!$this->has_current_people_correction($correction, $filters)) {
				$seen[$key] = true;
				$this->record_correction_diagnostic($correction, 'rejected', 'stale_people_object');
				return;
			}
		} else {
			$search_service = new VFWP_Intranet_Search_Service($this->wpdb, $this->query_parser);
			$has_results = isset($correction['candidate_type']) && $correction['candidate_type'] === 'preferred'
				? $search_service->has_results($query, $filters)
				: (strpos($key, ' ') !== false
				? $search_service->has_exact_phrase_results($query, $filters)
				: $search_service->has_results($query, $filters));

			if (!$has_results) {
				$seen[$key] = true;
				$this->record_correction_diagnostic($correction, 'rejected', 'no_indexed_results');
				return;
			}
		}

		$seen[$key] = true;
		$suggestions[] = array(
			'query' => $query,
			'label' => $this->lowercase(!empty($correction['label']) ? (string) $correction['label'] : $query),
			'confidence' => isset($correction['confidence']) ? round((float) $correction['confidence'], 4) : 0.0,
			'source' => isset($correction['candidate_type']) ? sanitize_key($correction['candidate_type']) : 'term',
		);
		$this->record_correction_diagnostic($correction, 'accepted', 'validated');
	}

	/**
	 * Confirm that a fuzzy People suggestion still represents its source object.
	 *
	 * @param array $correction People correction data.
	 * @param array $filters Active search filters.
	 * @return bool
	 */
	private function has_current_people_correction(array $correction, array $filters) {
		$object_id = isset($correction['object_id']) ? (int) $correction['object_id'] : 0;

		if (
			$object_id <= 0
			|| !in_array('post', $filters['object_types'], true)
			|| !in_array('people', $filters['post_types'], true)
		) {
			return false;
		}

		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$posts_table = $this->wpdb->posts;
		$row = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT i.title AS indexed_title, p.post_title AS current_title
				FROM {$table_name} i
				INNER JOIN {$posts_table} p ON p.ID = i.object_id
				WHERE i.object_id = %d
					AND i.object_type = %s
					AND i.post_type = %s
					AND i.post_status = %s
					AND i.visibility = %s
					AND p.post_type = %s
					AND p.post_status = %s
					AND p.post_password = %s
				LIMIT 1",
				$object_id,
				'post',
				'people',
				'publish',
				'public',
				'people',
				'publish',
				''
			),
			ARRAY_A
		);

		if (!is_array($row) || empty($row['indexed_title']) || empty($row['current_title'])) {
			return false;
		}

		$suggested_name = $this->query_parser->normalize_search_text(isset($correction['query']) ? (string) $correction['query'] : '');
		$indexed_name = $this->query_parser->normalize_search_text((string) $row['indexed_title']);
		$current_name = $this->query_parser->normalize_search_text((string) $row['current_title']);

		return $suggested_name !== '' && $suggested_name === $indexed_name && $suggested_name === $current_name;
	}

	/**
	 * Sort corrections by edit distance, then shorter text.
	 *
	 * @param array $a First correction.
	 * @param array $b Second correction.
	 * @return int
	 */
	private function sort_did_you_mean_corrections($a, $b) {
		if ($a['distance'] !== $b['distance']) {
			return $a['distance'] - $b['distance'];
		}

		$same_characters_a = isset($a['same_character_matches']) ? (int) $a['same_character_matches'] : 0;
		$same_characters_b = isset($b['same_character_matches']) ? (int) $b['same_character_matches'] : 0;

		if ($same_characters_a !== $same_characters_b) {
			return $same_characters_b - $same_characters_a;
		}

		$frequency_a = isset($a['frequency']) ? (int) $a['frequency'] : 0;
		$frequency_b = isset($b['frequency']) ? (int) $b['frequency'] : 0;

		if ($frequency_a !== $frequency_b) {
			return $frequency_b - $frequency_a;
		}

		if ($a['length'] !== $b['length']) {
			return $a['length'] - $b['length'];
		}

		return strcasecmp($a['label'], $b['label']);
	}

	/**
	 * Rank corrections from the general and People dictionaries together.
	 *
	 * This orders candidates within their source groups. The caller displays
	 * validated People matches before general-vocabulary alternatives.
	 *
	 * @param array $a First correction.
	 * @param array $b Second correction.
	 * @return int
	 */
	private function sort_combined_did_you_mean_corrections($a, $b) {
		$distance_a = isset($a['distance']) ? (int) $a['distance'] : PHP_INT_MAX;
		$distance_b = isset($b['distance']) ? (int) $b['distance'] : PHP_INT_MAX;

		if ($distance_a !== $distance_b) {
			return $distance_a - $distance_b;
		}

		$priority_a = isset($a['source_priority']) ? (int) $a['source_priority'] : 0;
		$priority_b = isset($b['source_priority']) ? (int) $b['source_priority'] : 0;

		if ($priority_a !== $priority_b) {
			return $priority_b - $priority_a;
		}

		return $this->sort_did_you_mean_corrections($a, $b);
	}

	/**
	 * Return allowed edit distance for term corrections.
	 *
	 * @param string $term Term.
	 * @return int
	 */
	private function get_max_term_distance($term) {
		$length = $this->length($term);

		if ($length < 5) {
			return 1;
		}

		if ($length < 9) {
			return 2;
		}

		return 3;
	}

	/**
	 * Unicode-aware optimal-string-alignment distance.
	 *
	 * Adjacent transpositions count as one edit, which covers common typing
	 * mistakes such as "soter" for "store" without loosening all thresholds.
	 *
	 * @param string $left First term.
	 * @param string $right Second term.
	 * @return int
	 */
	private function damerau_levenshtein($left, $right) {
		$a = preg_split('//u', (string) $left, -1, PREG_SPLIT_NO_EMPTY);
		$b = preg_split('//u', (string) $right, -1, PREG_SPLIT_NO_EMPTY);

		if (!is_array($a) || !is_array($b)) {
			return levenshtein((string) $left, (string) $right);
		}

		$rows = count($a);
		$columns = count($b);
		$matrix = array();

		for ($i = 0; $i <= $rows; $i++) {
			$matrix[$i] = array_fill(0, $columns + 1, 0);
			$matrix[$i][0] = $i;
		}

		for ($j = 0; $j <= $columns; $j++) {
			$matrix[0][$j] = $j;
		}

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
				$cost = $a[$i - 1] === $b[$j - 1] ? 0 : 1;
				$matrix[$i][$j] = min(
					$matrix[$i - 1][$j] + 1,
					$matrix[$i][$j - 1] + 1,
					$matrix[$i - 1][$j - 1] + $cost
				);

				if ($i > 1 && $j > 1 && $a[$i - 1] === $b[$j - 2] && $a[$i - 2] === $b[$j - 1]) {
					$matrix[$i][$j] = min($matrix[$i][$j], $matrix[$i - 2][$j - 2] + 1);
				}
			}
		}

		return (int) $matrix[$rows][$columns];
	}

	/**
	 * Map normalized query terms to their original display capitalization.
	 *
	 * @param mixed $query Raw query.
	 * @param array $terms Parsed normalized terms.
	 * @return array
	 */
	private function get_original_term_labels($query, array $terms) {
		$labels = array();
		$raw_query = is_scalar($query) ? wp_strip_all_tags((string) $query) : '';
		preg_match_all('/[\p{L}\p{N}]+/u', $raw_query, $matches);

		foreach ((array) $matches[0] as $raw_term) {
			$normalized = $this->query_parser->normalize_search_text($raw_term);

			if ($normalized !== '' && in_array($normalized, $terms, true) && !isset($labels[$normalized])) {
				$labels[$normalized] = $raw_term;
			}
		}

		return $labels;
	}

	/**
	 * Return allowed edit distance for phrase corrections.
	 *
	 * @param string $query Query.
	 * @param string $phrase Candidate phrase.
	 * @return int
	 */
	private function get_max_phrase_distance($query, $phrase) {
		$length = max($this->length($query), $this->length($phrase));

		if ($length < 8) {
			return 1;
		}

		if ($length < 18) {
			return 2;
		}

		return min(4, (int) floor($length * 0.18));
	}

	/**
	 * Unicode-aware length.
	 *
	 * @param string $text Text.
	 * @return int
	 */
	private function length($text) {
		return function_exists('mb_strlen') ? (int) mb_strlen((string) $text, 'UTF-8') : strlen((string) $text);
	}

	/**
	 * Lowercase one suggestion label without removing accents.
	 *
	 * @param string $text Suggestion label.
	 * @return string
	 */
	private function lowercase($text) {
		return function_exists('mb_strtolower')
			? (string) mb_strtolower((string) $text, 'UTF-8')
			: strtolower((string) $text);
	}

}
