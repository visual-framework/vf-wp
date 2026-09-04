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
	const DID_YOU_MEAN_CANDIDATE_ROWS = 1200;
	const DID_YOU_MEAN_MAX_TERMS = 2500;

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
		wp_enqueue_script(
			$handle,
			get_stylesheet_directory_uri() . '/scripts/search-suggestions.js',
			array(),
			wp_get_theme()->get('Version'),
			true
		);
		wp_localize_script(
			$handle,
			'vfwpSearchSuggestions',
			array(
				'ajaxUrl'   => admin_url('admin-ajax.php'),
				'action'    => self::ACTION,
				'nonce'     => wp_create_nonce(self::ACTION),
				'minLength' => 2,
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
		$cached = wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		$suggestions = array();
		$seen = array();

		$this->append_suggestion($suggestions, $seen, $this->get_search_action_suggestion($parsed_query), $limit);

		foreach ($this->get_title_suggestions($parsed_query, $filters, min(self::TITLE_LIMIT, $limit)) as $suggestion) {
			$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
		}

		foreach ($this->get_phrase_suggestions($parsed_query, $filters, $limit) as $suggestion) {
			$this->append_suggestion($suggestions, $seen, $suggestion, $limit);
		}

		wp_cache_set($cache_key, $suggestions, 'vfwp_intranet_search', 2 * MINUTE_IN_SECONDS);

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
		)));
		$cached = wp_cache_get($cache_key, 'vfwp_intranet_search');

		if (is_array($cached)) {
			return $cached;
		}

		$candidates = $this->get_did_you_mean_candidates($filters, $parsed_query);
		$suggestions = array();
		$seen = array();

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
		$match_conditions = array('LOWER(title) LIKE %s');
		$match_params = array($prefix);
		$where_params = array('post', 'publish', 'public');

		if (!empty($parsed_query['boolean_query'])) {
			$score_parts[] = 'MATCH(title) AGAINST (%s IN BOOLEAN MODE)';
			$score_params[] = $parsed_query['boolean_query'];
			$match_conditions[] = 'MATCH(title) AGAINST (%s IN BOOLEAN MODE)';
			$match_params[] = $parsed_query['boolean_query'];
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

			$suggestions[] = array(
				'type'        => 'result',
				'label'       => $title,
				'value'       => $title,
				'url'         => $url,
				'object_id'   => isset($row['object_id']) ? (int) $row['object_id'] : 0,
				'object_type' => isset($row['object_type']) ? sanitize_key($row['object_type']) : 'post',
				'post_type'   => $post_type,
				'badge_label' => $this->get_post_type_label($post_type),
				'external_domain_label' => $post_type === 'teams' ? $this->get_external_domain_label($url) : '',
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
		$phrases = array();

		if (class_exists('VFWP_Intranet_Search_Settings')) {
			foreach (VFWP_Intranet_Search_Settings::get_exact_phrases() as $phrase) {
				$this->append_phrase_candidate($phrases, $phrase, $parsed_query);
			}
		}

		foreach ($this->get_indexed_keyword_phrase_candidates($parsed_query, $filters) as $phrase) {
			$this->append_phrase_candidate($phrases, $phrase, $parsed_query);
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
	 * Return the external domain label used by team result pills.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	private function get_external_domain_label($url) {
		$host = wp_parse_url((string) $url, PHP_URL_HOST);

		if (!$host) {
			return '';
		}

		$host = strtolower(preg_replace('/^www\./', '', (string) $host));

		if (strpos($host, 'embl.org') !== false) {
			return 'embl.org';
		}

		if (strpos($host, 'ebi.ac.uk') !== false) {
			return 'ebi.ac.uk';
		}

		return $host;
	}

	/**
	 * Return bounded title and keyword candidates for no-results spelling suggestions.
	 *
	 * @param array $filters Normalized filters.
	 * @return array
	 */
	private function get_did_you_mean_candidates(array $filters, array $parsed_query) {
		$candidates = array(
			'terms'   => array(),
			'phrases' => array(),
		);

		if (class_exists('VFWP_Intranet_Search_Settings')) {
			foreach (VFWP_Intranet_Search_Settings::get_exact_phrases() as $phrase) {
				$this->append_did_you_mean_text($candidates, $phrase, true);
			}
		}

		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$where_params = array('post', 'publish', 'public');
		$where_sql = $this->build_base_where_sql($filters, $where_params);
		$term_conditions = $this->get_candidate_term_conditions($parsed_query, $where_params);

		if (!empty($term_conditions)) {
			$where_sql .= ' AND (' . implode(' OR ', $term_conditions) . ')';
		}

		$sql = "
			SELECT title, acf_keywords
			FROM {$table_name}
			{$where_sql}
				AND (title <> '' OR acf_keywords <> '')
			ORDER BY updated_at DESC, id DESC
			LIMIT %d
		";
		$params = array_merge($where_params, array((int) self::DID_YOU_MEAN_CANDIDATE_ROWS));
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $params), ARRAY_A);

		if (!is_array($rows)) {
			return $candidates;
		}

		foreach ($rows as $row) {
			$this->append_did_you_mean_text($candidates, isset($row['title']) ? $row['title'] : '', true);

			foreach (preg_split('/[,;\r\n|]+/u', isset($row['acf_keywords']) ? (string) $row['acf_keywords'] : '') as $phrase) {
				$this->append_did_you_mean_text($candidates, $phrase, true);
			}

			if (count($candidates['terms']) >= self::DID_YOU_MEAN_MAX_TERMS) {
				break;
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
			if ($this->length($term) < 3 || count($candidates['terms']) >= self::DID_YOU_MEAN_MAX_TERMS) {
				continue;
			}

			if (!isset($candidates['terms'][$term])) {
				$candidates['terms'][$term] = 0;
			}

			$candidates['terms'][$term]++;
		}
	}

	/**
	 * Build bounded title/keyword candidate conditions from typed term prefixes.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $params SQL params.
	 * @return array
	 */
	private function get_candidate_term_conditions(array $parsed_query, array &$params) {
		$terms = !empty($parsed_query['all_terms']) ? (array) $parsed_query['all_terms'] : array();
		$conditions = array();
		$seen = array();

		foreach ($terms as $term) {
			$term = (string) $term;

			if ($this->length($term) < 3) {
				continue;
			}

			$stem = $this->substring($term, 0, 3);

			if ($stem === '' || isset($seen[$stem])) {
				continue;
			}

			$seen[$stem] = true;
			$like = '%' . $this->wpdb->esc_like($stem) . '%';
			$conditions[] = '(LOWER(title) LIKE %s OR LOWER(acf_keywords) LIKE %s)';
			$params[] = $like;
			$params[] = $like;

			if (count($conditions) >= 4) {
				break;
			}
		}

		return $conditions;
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

		if (empty($query_terms)) {
			return array();
		}

		$corrected_terms = array();
		$changed = false;

		foreach ($query_terms as $query_term) {
			$query_term = (string) $query_term;

			if ($this->length($query_term) < 3) {
				$corrected_terms[] = $query_term;
				continue;
			}

			$best_match = $this->get_best_term_match($query_term, $terms);

			if ($best_match && $best_match['term'] !== $query_term) {
				$corrected_terms[] = $best_match['term'];
				$changed = true;
				continue;
			}

			$corrected_terms[] = $query_term;
		}

		$corrected_query = trim(implode(' ', array_values(array_unique($corrected_terms))));

		if (!$changed || $corrected_query === '' || $corrected_query === (string) $parsed_query['normalized']) {
			return array();
		}

		return array(
			array(
				'query'    => $corrected_query,
				'label'    => $corrected_query,
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

		foreach ($terms as $term => $frequency) {
			if ($term === $query_term) {
				return array(
					'term'      => $term,
					'distance'  => 0,
					'frequency' => (int) $frequency,
				);
			}

			if (abs($this->length($term) - $query_length) > 2) {
				continue;
			}

			if (substr($term, 0, 1) !== substr($query_term, 0, 1)) {
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

		if ($search_service->count($query, $filters) < 1) {
			$seen[$key] = true;
			return;
		}

		$seen[$key] = true;
		$suggestions[] = array(
			'query' => $query,
			'label' => !empty($correction['label']) ? (string) $correction['label'] : $query,
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

		return 2;
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
	 * Unicode-aware substring.
	 *
	 * @param string $text Text.
	 * @param int    $start Start offset.
	 * @param int    $length Length.
	 * @return string
	 */
	private function substring($text, $start, $length) {
		return function_exists('mb_substr') ? (string) mb_substr((string) $text, (int) $start, (int) $length, 'UTF-8') : substr((string) $text, (int) $start, (int) $length);
	}
}
