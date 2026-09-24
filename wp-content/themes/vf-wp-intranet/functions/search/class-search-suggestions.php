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
	const DID_YOU_MEAN_LIMIT = 3;
	const CACHE_TTL = 120;
	const SPELLING_ALGORITHM_VERSION = 6;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var VFWP_Intranet_Search_Query_Parser
	 */
	private $query_parser;

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
		add_action('wp_ajax_' . self::ACTION, array(__CLASS__, 'handle_ajax_request'));
		add_action('wp_ajax_nopriv_' . self::ACTION, array(__CLASS__, 'handle_ajax_request'));
	}

	/**
	 * Enqueue autosuggest script where the intranet search form is rendered.
	 *
	 * @return void
	 */
	public static function enqueue_assets() {
		if (!is_search() && !is_page_template('searchpage.php')) {
			return;
		}

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

		$this->append_suggestion($suggestions, $seen, $this->get_search_action_suggestion($parsed_query), $limit);

		if ($this->should_run_indexed_suggestion_lookup($parsed_query)) {
			foreach ($this->get_title_suggestions($parsed_query, $filters, min(self::TITLE_LIMIT, $limit)) as $suggestion) {
				$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
			}
		}

		$remaining_limit = $limit - count($suggestions);

		foreach ($this->get_phrase_suggestions($parsed_query, $filters, $remaining_limit) as $suggestion) {
			$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
		}

		$this->set_cached_suggestions($cache_key, $suggestions, self::CACHE_TTL);

		return $suggestions;
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

		if ($normalized_query === '' || $this->length($normalized_query) < 3) {
			return array();
		}

		$filters = $this->normalize_filters($filters);
		$cache_key = 'did_you_mean_' . md5(wp_json_encode(array(
			'query'   => $normalized_query,
			'filters' => $filters,
			'limit'   => $limit,
			'schema'  => VFWP_Intranet_Search_Schema::VERSION,
			'algorithm' => self::SPELLING_ALGORITHM_VERSION,
		)));
		$cached = wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		$candidates = $this->get_did_you_mean_candidates($parsed_query);
		$suggestions = array();
		$seen = array();

		if (in_array('people', $filters['post_types'], true) && class_exists('VFWP_Intranet_Search_People_Name_Repository')) {
			$people_names = new VFWP_Intranet_Search_People_Name_Repository($this->wpdb, $this->query_parser);

			foreach ($people_names->find_suggestions($normalized_query, $limit) as $correction) {
				$this->append_did_you_mean_suggestion($suggestions, $seen, $correction, $filters, $limit);
			}

			if (!empty($suggestions)) {
				wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);
				return $suggestions;
			}
		}

		foreach ($this->get_phrase_corrections($normalized_query, $candidates['phrases']) as $correction) {
			$this->append_did_you_mean_suggestion($suggestions, $seen, $correction, $filters, $limit);
		}

		foreach ($this->get_term_corrections($parsed_query, $candidates['terms']) as $correction) {
			$this->append_did_you_mean_suggestion($suggestions, $seen, $correction, $filters, $limit);
		}

		wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 5 * MINUTE_IN_SECONDS);

		return $suggestions;
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
		$score_parts = array('IF(LOWER(title) LIKE %s, 50, 0)');
		$score_params = array($prefix);
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

		if ($key === '' || isset($seen[$key])) {
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
		);

		if (class_exists('VFWP_Intranet_Search_Settings')) {
			foreach (VFWP_Intranet_Search_Settings::get_exact_phrases() as $phrase) {
				$this->append_did_you_mean_text($candidates, $phrase, true);
			}
		}

		if (class_exists('VFWP_Intranet_Search_Spelling_Repository')) {
			$spelling_repository = new VFWP_Intranet_Search_Spelling_Repository($this->wpdb, $this->query_parser);
			$query_terms = !empty($parsed_query['all_terms']) ? (array) $parsed_query['all_terms'] : array();

			foreach ($query_terms as $query_term) {
				foreach ($spelling_repository->find_candidates((string) $query_term) as $candidate) {
					$frequency = (int) $candidate['document_frequency']
						+ ((int) $candidate['title_frequency'] * 4)
						+ ((int) $candidate['keyword_frequency'] * 3);
					$this->append_did_you_mean_term(
						$candidates['terms'],
						isset($candidate['term']) ? $candidate['term'] : '',
						isset($candidate['display_term']) ? $candidate['display_term'] : '',
						max(1, $frequency)
					);
				}
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
	private function append_did_you_mean_term(array &$terms, $term, $label, $frequency) {
		$term = $this->query_parser->normalize_search_text(is_scalar($term) ? (string) $term : '');

		if ($term === '' || strpos($term, ' ') !== false || $this->length($term) < 3) {
			return;
		}

		$label = is_scalar($label) ? trim((string) $label) : '';

		if (!isset($terms[$term])) {
			$terms[$term] = array(
				'frequency' => 0,
				'label'     => $label !== '' ? $label : $term,
			);
		}

		$terms[$term]['frequency'] += max(1, (int) $frequency);

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

			$distance = levenshtein($normalized_query, $normalized_phrase);
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

		$corrected_terms = array();
		$corrected_labels = array();
		$original_labels = $this->get_original_term_labels(
			isset($parsed_query['raw']) ? $parsed_query['raw'] : '',
			$query_terms
		);
		$changed = false;

		foreach ($query_terms as $query_term) {
			$query_term = (string) $query_term;

			// Preserve stopwords and terms ignored by query parsing. They are not
			// represented reliably in the title/keyword spelling dictionary.
			if ($this->length($query_term) < 3 || !isset($correctable_terms[$query_term])) {
				$corrected_terms[] = $query_term;
				$corrected_labels[] = isset($original_labels[$query_term]) ? $original_labels[$query_term] : $query_term;
				continue;
			}

			$best_match = $this->get_best_term_match($query_term, $terms);

			if ($best_match && $best_match['term'] !== $query_term) {
				$corrected_terms[] = $best_match['term'];
				$corrected_labels[] = $best_match['label'];
				$changed = true;
				continue;
			}

			$corrected_terms[] = $query_term;
			$corrected_labels[] = isset($original_labels[$query_term]) ? $original_labels[$query_term] : $query_term;
		}

		$corrected_query = trim(implode(' ', array_values(array_unique($corrected_terms))));
		$corrected_label = trim(implode(' ', $corrected_labels));

		if (!$changed || $corrected_query === '' || $corrected_query === (string) $parsed_query['normalized']) {
			return array();
		}

		return array(
			array(
				'query'    => $corrected_query,
				'label'    => $corrected_label !== '' ? $corrected_label : $corrected_query,
				'distance' => 0,
				'length'   => $this->length($corrected_query),
			),
		);
	}

	/**
	 * Return the nearest indexed term for one query term.
	 *
	 * @param string $query_term Query term.
	 * @param array  $terms Candidate terms keyed by term.
	 * @return array|null
	 */
	private function get_best_term_match($query_term, array $terms) {
		$best = null;
		$query_length = $this->length($query_term);

		foreach ($terms as $term => $term_data) {
			$frequency = is_array($term_data) && isset($term_data['frequency']) ? (int) $term_data['frequency'] : (int) $term_data;
			$label = is_array($term_data) && !empty($term_data['label']) ? (string) $term_data['label'] : $term;

			if ($term === $query_term) {
				return array(
					'term'      => $term,
					'label'     => $label,
					'distance'  => 0,
					'frequency' => (int) $frequency,
				);
			}

			if (abs($this->length($term) - $query_length) > 2) {
				continue;
			}

			$distance = levenshtein($query_term, $term);

			if ($distance > $this->get_max_term_distance($query_term)) {
				continue;
			}

			if (
				null === $best
				|| $distance < $best['distance']
				|| ($distance === $best['distance'] && (int) $frequency > $best['frequency'])
				|| ($distance === $best['distance'] && (int) $frequency === $best['frequency'] && strcasecmp($term, $best['term']) < 0)
			) {
				$best = array(
					'term'      => $term,
					'label'     => $label,
					'distance'  => $distance,
					'frequency' => (int) $frequency,
				);
			}
		}

		return $best;
	}

	/**
	 * Add a correction if it leads to actual indexed results.
	 *
	 * @param array $suggestions Suggestions.
	 * @param array $seen Seen queries.
	 * @param array $correction Correction data.
	 * @param array $filters Search filters.
	 * @param int   $limit Limit.
	 * @return void
	 */
	private function append_did_you_mean_suggestion(array &$suggestions, array &$seen, array $correction, array $filters, $limit) {
		if (count($suggestions) >= $limit || empty($correction['query'])) {
			return;
		}

		$query = trim((string) $correction['query']);
		$key = strtolower((string) $this->query_parser->parse($query)['normalized']);

		if ($key === '' || isset($seen[$key])) {
			return;
		}

		$search_service = new VFWP_Intranet_Search_Service($this->wpdb, $this->query_parser);

		if (!$search_service->has_results($query, $filters)) {
			$seen[$key] = true;
			return;
		}

		$seen[$key] = true;
		$suggestions[] = array(
			'query' => $query,
			'label' => $this->lowercase(!empty($correction['label']) ? (string) $correction['label'] : $query),
		);
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

		if ($a['length'] !== $b['length']) {
			return $a['length'] - $b['length'];
		}

		return strcasecmp($a['label'], $b['label']);
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
