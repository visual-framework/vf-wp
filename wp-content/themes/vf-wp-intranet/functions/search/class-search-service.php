<?php
/**
 * Search service for querying the custom theme search index.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Service {
	const DEFAULT_PER_PAGE = 10;
	const MAX_PER_PAGE = 50;
	const MAX_OFFSET = 5000;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var VFWP_Intranet_Search_Query_Parser
	 */
	private $query_parser;

	/**
	 * @var VFWP_Intranet_Search_Snippet_Service
	 */
	private $snippet_service;

	/**
	 * @param wpdb|null                                $db WordPress database object.
	 * @param VFWP_Intranet_Search_Query_Parser|null   $query_parser Query parser.
	 * @param VFWP_Intranet_Search_Snippet_Service|null $snippet_service Snippet service.
	 */
	public function __construct($db = null, $query_parser = null, $snippet_service = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->query_parser = $query_parser ? $query_parser : new VFWP_Intranet_Search_Query_Parser();
		$this->snippet_service = $snippet_service ? $snippet_service : new VFWP_Intranet_Search_Snippet_Service($this->query_parser);
	}

	/**
	 * Search the custom index.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Filters.
	 * @param int   $page Page number.
	 * @param int   $per_page Results per page.
	 * @return array
	 */
	public function search($query, array $filters = array(), $page = 1, $per_page = self::DEFAULT_PER_PAGE) {
		$parsed_query = $this->query_parser->parse($query);
		$page = max(1, (int) $page);
		$per_page = min(self::MAX_PER_PAGE, max(1, (int) $per_page));
		$offset = ($page - 1) * $per_page;
		$normalized_filters = $this->normalize_filters($filters);

		if ($parsed_query['is_empty']) {
			return $this->empty_response($parsed_query, $normalized_filters, $page, $per_page, 'empty_query');
		}

		if (!$parsed_query['is_searchable']) {
			return $this->empty_response($parsed_query, $normalized_filters, $page, $per_page, 'no_fulltext_terms');
		}

		if (
			empty($normalized_filters['object_types'])
			|| (in_array('post', $normalized_filters['object_types'], true) && empty($normalized_filters['post_types']))
		) {
			return $this->empty_response($parsed_query, $normalized_filters, $page, $per_page, 'filtered_out');
		}

		$count_sql_parts = $this->build_where_sql($parsed_query, $normalized_filters);
		$count_sql = 'SELECT COUNT(*) FROM ' . VFWP_Intranet_Search_Schema::table_name() . ' ' . $count_sql_parts['where_sql'];
		$total = (int) $this->wpdb->get_var($this->prepare_sql($count_sql, $count_sql_parts['params']));
		$total_pages = $total > 0 ? (int) ceil($total / $per_page) : 0;

		if ($offset > self::MAX_OFFSET) {
			return $this->empty_response($parsed_query, $normalized_filters, $page, $per_page, 'offset_limit_exceeded', $total);
		}

		if ($total === 0) {
			return $this->empty_response($parsed_query, $normalized_filters, $page, $per_page, 'ok', 0);
		}

		$rows = $this->get_result_rows($parsed_query, $normalized_filters, $page, $per_page, $offset);

		$results = array();

		foreach ($rows as $row) {
			$results[] = $this->format_result($row, $parsed_query);
		}

		return array(
			'query'      => $parsed_query,
			'filters'    => $normalized_filters,
			'results'    => $results,
			'pagination' => array(
				'page'         => $page,
				'per_page'     => $per_page,
				'total'        => $total,
				'total_pages'  => $total_pages,
				'has_previous' => $page > 1,
				'has_next'     => $page < $total_pages,
				'max_per_page' => self::MAX_PER_PAGE,
				'max_offset'   => self::MAX_OFFSET,
			),
			'status'     => 'ok',
		);
	}

	/**
	 * Run a search and attach an administrator-friendly score explanation.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Filters.
	 * @param int   $page Page number.
	 * @param int   $per_page Results per page.
	 * @return array
	 */
	public function search_with_score_breakdown($query, array $filters = array(), $page = 1, $per_page = self::DEFAULT_PER_PAGE) {
		$response = $this->search($query, $filters, $page, $per_page);

		foreach ($response['results'] as &$result) {
			$result['score_breakdown'] = $this->build_score_breakdown($result);
		}
		unset($result);

		return $response;
	}

	/**
	 * Count indexed matches for a query and filter set without loading rows.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Filters.
	 * @return int
	 */
	public function count($query, array $filters = array()) {
		$parsed_query = $this->query_parser->parse($query);
		$normalized_filters = $this->normalize_filters($filters);

		if ($parsed_query['is_empty'] || !$parsed_query['is_searchable']) {
			return 0;
		}

		if (
			empty($normalized_filters['object_types'])
			|| (in_array('post', $normalized_filters['object_types'], true) && empty($normalized_filters['post_types']))
		) {
			return 0;
		}

		$count_sql_parts = $this->build_where_sql($parsed_query, $normalized_filters);
		$count_sql = 'SELECT COUNT(*) FROM ' . VFWP_Intranet_Search_Schema::table_name() . ' ' . $count_sql_parts['where_sql'];

		return (int) $this->wpdb->get_var($this->prepare_sql($count_sql, $count_sql_parts['params']));
	}

	/**
	 * Determine whether an indexed query has at least one result.
	 *
	 * This avoids an exact COUNT(*) when a caller only needs to validate a
	 * spelling correction or broader-query suggestion.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Filters.
	 * @return bool
	 */
	public function has_results($query, array $filters = array()) {
		$parsed_query = $this->query_parser->parse($query);
		$normalized_filters = $this->normalize_filters($filters);

		if ($parsed_query['is_empty'] || !$parsed_query['is_searchable']) {
			return false;
		}

		if (
			empty($normalized_filters['object_types'])
			|| (in_array('post', $normalized_filters['object_types'], true) && empty($normalized_filters['post_types']))
		) {
			return false;
		}

		$sql_parts = $this->build_where_sql($parsed_query, $normalized_filters);
		$sql = 'SELECT 1 FROM ' . VFWP_Intranet_Search_Schema::table_name() . ' ' . $sql_parts['where_sql'] . ' LIMIT 1';

		return null !== $this->wpdb->get_var($this->prepare_sql($sql, $sql_parts['params']));
	}

	/**
	 * Count indexed matches grouped by object type and post type.
	 *
	 * @param mixed $query Raw query.
	 * @param array $filters Filters.
	 * @return array
	 */
	public function count_groups($query, array $filters = array()) {
		$parsed_query = $this->query_parser->parse($query);
		$normalized_filters = $this->normalize_filters($filters);
		$empty_counts = array(
			'total'        => 0,
			'object_types' => array(),
			'post_types'   => array(),
		);

		if ($parsed_query['is_empty'] || !$parsed_query['is_searchable']) {
			return $empty_counts;
		}

		if (
			empty($normalized_filters['object_types'])
			|| (in_array('post', $normalized_filters['object_types'], true) && empty($normalized_filters['post_types']))
		) {
			return $empty_counts;
		}

		$count_sql_parts = $this->build_where_sql($parsed_query, $normalized_filters);
		$sql = '
			SELECT object_type, post_type, COUNT(*) AS total
			FROM ' . VFWP_Intranet_Search_Schema::table_name() . '
			' . $count_sql_parts['where_sql'] . '
			GROUP BY object_type, post_type
		';
		$rows = $this->wpdb->get_results($this->prepare_sql($sql, $count_sql_parts['params']), ARRAY_A);

		if (!is_array($rows)) {
			return $empty_counts;
		}

		$counts = $empty_counts;

		foreach ($rows as $row) {
			$object_type = sanitize_key($row['object_type']);
			$post_type = sanitize_key($row['post_type']);
			$total = (int) $row['total'];

			$counts['total'] += $total;

			if (!isset($counts['object_types'][$object_type])) {
				$counts['object_types'][$object_type] = 0;
			}

			$counts['object_types'][$object_type] += $total;

			if ('post' === $object_type) {
				if (!isset($counts['post_types'][$post_type])) {
					$counts['post_types'][$post_type] = 0;
				}

				$counts['post_types'][$post_type] += $total;
			}
		}

		return $counts;
	}

	/**
	 * Return SQL rows for one result page.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $filters Normalized filters.
	 * @param int   $page Page number.
	 * @param int   $per_page Results per page.
	 * @param int   $offset SQL offset.
	 * @return array
	 */
	private function get_result_rows(array $parsed_query, array $filters, $page, $per_page, $offset) {
		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$where_parts = $this->build_where_sql($parsed_query, $filters);
		$score_parts = $this->build_score_sql($parsed_query);
		$post_type_weight_sql = $this->build_post_type_weight_sql($filters['post_types']);

		$sql = "
			SELECT scored.*,
				IF(scored.published_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 30 DAY), 1, 0) AS recent_content_hit,
				(
					(
						(scored.exact_title_match * {$score_parts['boosts']['exact_title']})
						+ (scored.title_phrase_hit * {$score_parts['weights']['title']} * {$score_parts['boosts']['title_phrase']})
						+ (IF(scored.title_term_hits = {$score_parts['term_count']}, 1, 0) * {$score_parts['weights']['title']} * {$score_parts['boosts']['title_all_terms']})
						+ (scored.title_term_hits * {$score_parts['weights']['title']} * {$score_parts['boosts']['title_term']})
						+ (scored.acf_phrase_hit * {$score_parts['weights']['acf_keywords']} * {$score_parts['boosts']['acf_phrase']})
						+ (scored.acf_term_hits * {$score_parts['weights']['acf_keywords']} * {$score_parts['boosts']['acf_term']})
						+ (scored.excerpt_phrase_hit * {$score_parts['weights']['excerpt']} * {$score_parts['boosts']['excerpt_phrase']})
						+ (scored.excerpt_term_hits * {$score_parts['weights']['excerpt']} * {$score_parts['boosts']['excerpt_term']})
						+ (scored.content_phrase_hit * {$score_parts['weights']['content']} * {$score_parts['boosts']['content_phrase']})
						+ (scored.content_term_hits * {$score_parts['weights']['content']} * {$score_parts['boosts']['content_term']})
						+ (IF(scored.all_field_term_hits = {$score_parts['term_count']}, 1, 0) * {$score_parts['boosts']['all_terms']})
						+ ((scored.all_field_term_hits / {$score_parts['term_count']}) * {$score_parts['boosts']['term_coverage']})
						+ (scored.ft_title * {$score_parts['weights']['title']} * {$score_parts['boosts']['fulltext_title']})
						+ (scored.ft_acf_keywords * {$score_parts['weights']['acf_keywords']} * {$score_parts['boosts']['fulltext_acf']})
						+ (scored.ft_excerpt * {$score_parts['weights']['excerpt']} * {$score_parts['boosts']['fulltext_excerpt']})
						+ (scored.ft_content * {$score_parts['weights']['content']} * {$score_parts['boosts']['fulltext_content']})
					)
					* {$post_type_weight_sql}
				)
				+ IF(scored.published_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 30 DAY), {$score_parts['boosts']['recency']}, 0) AS relevance
			FROM (
				SELECT
					id,
					object_id,
					object_type,
					post_type,
					title,
					excerpt,
					content,
					acf_keywords,
					url,
					published_at,
					updated_at,
					{$score_parts['select_sql']}
				FROM {$table_name}
				{$where_parts['where_sql']}
			) scored
			ORDER BY
				relevance DESC,
				scored.exact_title_match DESC,
				scored.title_phrase_hit DESC,
				scored.all_field_term_hits DESC,
				scored.ft_title DESC,
				scored.published_at DESC,
				scored.object_id ASC
			LIMIT %d OFFSET %d
		";

		$params = array_merge($score_parts['params'], $where_parts['params'], array($per_page, $offset));
		$prepared_sql = $this->prepare_sql($sql, $params);
		$rows = $this->wpdb->get_results($prepared_sql, ARRAY_A);

		return is_array($rows) ? $rows : array();
	}

	/**
	 * Build the WHERE clause for indexed candidates.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $filters Normalized filters.
	 * @return array
	 */
	private function build_where_sql(array $parsed_query, array $filters) {
		$params = array();
		$conditions = array(
			'visibility = %s',
		);
		$params[] = 'public';

		if (!empty($parsed_query['has_protected_phrases'])) {
			if ($parsed_query['boolean_query'] !== '') {
				$normal_content_conditions = array(
					'MATCH(title) AGAINST (%s IN BOOLEAN MODE)',
					'MATCH(excerpt) AGAINST (%s IN BOOLEAN MODE)',
					'MATCH(content) AGAINST (%s IN BOOLEAN MODE)',
				);
				$params[] = $parsed_query['boolean_query'];
				$params[] = $parsed_query['boolean_query'];
				$params[] = $parsed_query['boolean_query'];

				$protected_phrase_match_sql = $this->build_all_exact_phrases_match_sql(
					array('title', 'excerpt', 'content'),
					isset($parsed_query['protected_phrases']) ? $parsed_query['protected_phrases'] : array(),
					$params
				);

				$normal_content_candidate = '((' . implode(' OR ', $normal_content_conditions) . ') AND ' . $protected_phrase_match_sql . ')';
			} else {
				$protected_phrase_match_sql = $this->build_all_exact_phrases_match_sql(
					array('title', 'excerpt', 'content'),
					isset($parsed_query['protected_phrases']) ? $parsed_query['protected_phrases'] : array(),
					$params
				);

				$normal_content_candidate = $protected_phrase_match_sql;
			}

			$all_query_terms_match_sql = $this->build_all_query_terms_match_sql(
				"CONCAT_WS(' ', title, excerpt, content)",
				isset($parsed_query['terms']) ? $parsed_query['terms'] : array(),
				$params
			);

			if ($all_query_terms_match_sql !== '1') {
				$normal_content_candidate = '(' . $normal_content_candidate . ' AND ' . $all_query_terms_match_sql . ')';
			}
		} else {
			$normal_content_conditions = array(
				'MATCH(title) AGAINST (%s IN BOOLEAN MODE)',
				'MATCH(excerpt) AGAINST (%s IN BOOLEAN MODE)',
				'MATCH(content) AGAINST (%s IN BOOLEAN MODE)',
			);
			$params[] = $parsed_query['boolean_query'];
			$params[] = $parsed_query['boolean_query'];
			$params[] = $parsed_query['boolean_query'];

			$normal_content_candidate = '(' . implode(' OR ', $normal_content_conditions) . ')';
			$all_query_terms_match_sql = $this->build_all_query_terms_match_sql(
				"CONCAT_WS(' ', title, excerpt, content)",
				isset($parsed_query['terms']) ? $parsed_query['terms'] : array(),
				$params
			);

			if ($all_query_terms_match_sql !== '1') {
				$normal_content_candidate = '(' . $normal_content_candidate . ' AND ' . $all_query_terms_match_sql . ')';
			}
		}

		$candidate_conditions = array($normal_content_candidate);
		$acf_exact_keyword_match_sql = $this->build_acf_exact_keyword_match_sql($parsed_query, $params);

		if ($acf_exact_keyword_match_sql !== '0') {
			$candidate_conditions[] = '(' . $acf_exact_keyword_match_sql . ')';
		}

		$conditions[] = '(' . implode(' OR ', $candidate_conditions) . ')';
		$conditions[] = 'object_type IN (' . implode(',', array_fill(0, count($filters['object_types']), '%s')) . ')';
		$params = array_merge($params, $filters['object_types']);

		if (in_array('post', $filters['object_types'], true)) {
			$post_type_condition = 'post_type IN (' . implode(',', array_fill(0, count($filters['post_types']), '%s')) . ')';

			if (count($filters['object_types']) > 1) {
				$conditions[] = "(object_type <> 'post' OR {$post_type_condition})";
			} else {
				$conditions[] = $post_type_condition;
			}

			$params = array_merge($params, $filters['post_types']);
		}

		return array(
			'where_sql' => 'WHERE ' . implode(' AND ', $conditions),
			'params'    => $params,
		);
	}

	/**
	 * Build SQL fragments for ranking signals.
	 *
	 * @param array $parsed_query Parsed query.
	 * @return array
	 */
	private function build_score_sql(array $parsed_query) {
		$params = array();
		$term_count = max(1, count($parsed_query['fulltext_terms']));
		$weights = $this->get_sql_weights();

		$select_parts = array(
			'IF(title = %s, 1, 0) AS exact_title_match',
			$this->build_phrase_hit_sql('title', $parsed_query['phrases'], $params) . ' AS title_phrase_hit',
			$this->build_acf_exact_keyword_match_sql($parsed_query, $params) . ' AS acf_phrase_hit',
			$this->build_phrase_hit_sql('excerpt', $parsed_query['phrases'], $params) . ' AS excerpt_phrase_hit',
			$this->build_phrase_hit_sql('content', $parsed_query['phrases'], $params) . ' AS content_phrase_hit',
			$this->build_term_hit_sql('title', $parsed_query['fulltext_terms'], $params) . ' AS title_term_hits',
			'(' . $this->build_acf_exact_keyword_match_sql($parsed_query, $params) . ' * ' . $term_count . ') AS acf_term_hits',
			$this->build_term_hit_sql('excerpt', $parsed_query['fulltext_terms'], $params) . ' AS excerpt_term_hits',
			$this->build_term_hit_sql('content', $parsed_query['fulltext_terms'], $params) . ' AS content_term_hits',
			$this->build_term_hit_sql("CONCAT_WS(' ', title, excerpt, content)", $parsed_query['fulltext_terms'], $params) . ' AS all_field_term_hits',
		);

		array_unshift($params, $parsed_query['normalized']);

		if ($parsed_query['boolean_query'] !== '') {
			$select_parts[] = 'MATCH(title) AGAINST (%s IN BOOLEAN MODE) AS ft_title';
			$params[] = $parsed_query['boolean_query'];

			$acf_fulltext_gate_sql = $this->build_acf_exact_keyword_match_sql($parsed_query, $params);
			$select_parts[] = 'IF(' . $acf_fulltext_gate_sql . ', MATCH(acf_keywords) AGAINST (%s IN BOOLEAN MODE), 0) AS ft_acf_keywords';
			$params[] = $parsed_query['boolean_query'];

			$select_parts[] = 'MATCH(excerpt) AGAINST (%s IN BOOLEAN MODE) AS ft_excerpt';
			$params[] = $parsed_query['boolean_query'];

			$select_parts[] = 'MATCH(content) AGAINST (%s IN BOOLEAN MODE) AS ft_content';
			$params[] = $parsed_query['boolean_query'];
		} else {
			$select_parts[] = '0 AS ft_title';
			$select_parts[] = '0 AS ft_acf_keywords';
			$select_parts[] = '0 AS ft_excerpt';
			$select_parts[] = '0 AS ft_content';
		}

		return array(
			'select_sql'  => implode(",\n\t\t\t\t\t", $select_parts),
			'params'      => $params,
			'term_count'  => $term_count,
			'weights'     => $weights,
			'boosts'      => $this->get_sql_ranking_boosts(),
		);
	}

	/**
	 * Build exact keyword-entry matching SQL for ACF keyword fields.
	 *
	 * @param array $parsed_query Parsed query.
	 * @param array $params SQL params.
	 * @return string
	 */
	private function build_acf_exact_keyword_match_sql(array $parsed_query, array &$params) {
		$pattern = $this->build_acf_exact_keyword_regexp(isset($parsed_query['normalized']) ? $parsed_query['normalized'] : '');

		if ($pattern === '') {
			return '0';
		}

		$params[] = $pattern;

		return 'LOWER(acf_keywords) REGEXP %s';
	}

	/**
	 * Build a delimiter-aware regexp for an exact ACF keyword entry.
	 *
	 * @param string $keyword Normalized query.
	 * @return string
	 */
	private function build_acf_exact_keyword_regexp($keyword) {
		$keyword = trim((string) $keyword);

		if ($keyword === '') {
			return '';
		}

		$terms = preg_split('/\s+/u', $keyword);

		if (!is_array($terms)) {
			return '';
		}

		$terms = array_values(array_filter(array_map('trim', $terms)));

		if (empty($terms)) {
			return '';
		}

		$escaped_terms = array();

		foreach ($terms as $term) {
			$escaped_terms[] = $this->build_accent_aware_regexp_fragment($term);
		}

		return '(^|[,;\r\n|])[[:space:]]*' . implode('[[:space:]]+', $escaped_terms) . '[[:space:]]*($|[,;\r\n|])';
	}

	/**
	 * Build SQL requiring every parsed query term to appear in normal content.
	 *
	 * @param string $field_sql Field SQL.
	 * @param array  $terms Query terms.
	 * @param array  $params SQL params.
	 * @return string
	 */
	private function build_all_query_terms_match_sql($field_sql, array $terms, array &$params) {
		$conditions = array();

		foreach ($terms as $term) {
			$pattern = $this->build_query_term_regexp($term);

			if ($pattern === '') {
				continue;
			}

			$conditions[] = "LOWER({$field_sql}) REGEXP %s";
			$params[] = $pattern;
		}

		if (empty($conditions)) {
			return '1';
		}

		return '(' . implode(' AND ', $conditions) . ')';
	}

	/**
	 * Build SQL requiring an exact normalized phrase to appear in at least one field.
	 *
	 * @param array  $field_sqls Field SQL fragments.
	 * @param string $phrase Exact phrase.
	 * @param array  $params SQL params.
	 * @return string
	 */
	private function build_exact_phrase_match_sql(array $field_sqls, $phrase, array &$params) {
		$pattern = $this->build_exact_phrase_regexp($phrase);

		if ($pattern === '') {
			return '0';
		}

		$conditions = array();

		foreach ($field_sqls as $field_sql) {
			$conditions[] = "LOWER({$field_sql}) REGEXP %s";
			$params[] = $pattern;
		}

		return '(' . implode(' OR ', $conditions) . ')';
	}

	/**
	 * Build SQL requiring every protected phrase to appear in at least one field.
	 *
	 * @param array $field_sqls Field SQL fragments.
	 * @param array $phrases Exact phrases.
	 * @param array $params SQL params.
	 * @return string
	 */
	private function build_all_exact_phrases_match_sql(array $field_sqls, array $phrases, array &$params) {
		$conditions = array();

		foreach ($phrases as $phrase) {
			$condition = $this->build_exact_phrase_match_sql($field_sqls, $phrase, $params);

			if ($condition !== '0') {
				$conditions[] = $condition;
			}
		}

		if (empty($conditions)) {
			return '0';
		}

		return '(' . implode(' AND ', $conditions) . ')';
	}

	/**
	 * Build a word-aware regexp for a normalized exact phrase.
	 *
	 * @param string $phrase Exact phrase.
	 * @return string
	 */
	private function build_exact_phrase_regexp($phrase) {
		$phrase = trim((string) $phrase);

		if ($phrase === '') {
			return '';
		}

		$terms = preg_split('/\s+/u', $phrase);

		if (!is_array($terms)) {
			return '';
		}

		$terms = array_values(array_filter(array_map('trim', $terms)));

		if (empty($terms)) {
			return '';
		}

		$escaped_terms = array();

		foreach ($terms as $term) {
			$escaped_terms[] = $this->build_accent_aware_regexp_fragment($term);
		}

		return '(^|[^[:alnum:]_])' . implode('[^[:alnum:]_]+', $escaped_terms) . '([^[:alnum:]_]|$)';
	}

	/**
	 * Build a word-aware regexp for one query term.
	 *
	 * @param string $term Query term.
	 * @return string
	 */
	private function build_query_term_regexp($term) {
		$term = trim((string) $term);

		if ($term === '') {
			return '';
		}

		$escaped_term = $this->build_accent_aware_regexp_fragment($term);

		if ($this->string_length($term) < 3) {
			return '(^|[^[:alnum:]_])' . $escaped_term . '([^[:alnum:]_]|$)';
		}

		return '(^|[^[:alnum:]_])' . $escaped_term . '[[:alnum:]_]*';
	}

	/**
	 * Build a safe regexp fragment that treats folded Latin letters and their
	 * accented forms as equivalent. Query parsing removes accents, while index
	 * display fields deliberately retain them.
	 *
	 * @param string $text Normalized query text.
	 * @return string
	 */
	private function build_accent_aware_regexp_fragment($text) {
		$variants = array(
			'a' => 'aàáâãäåāăąǎǟǡǻȁȃạảấầẩẫậắằẳẵặ',
			'c' => 'cçćĉċč',
			'd' => 'dďđð',
			'e' => 'eèéêëēĕėęěȅȇẹẻẽếềểễệ',
			'g' => 'gĝğġģǧ',
			'h' => 'hĥħ',
			'i' => 'iìíîïĩīĭįıǐȉȋịỉ',
			'j' => 'jĵ',
			'k' => 'kķ',
			'l' => 'lĺļľŀł',
			'n' => 'nñńņňŋ',
			'o' => 'oòóôõöøōŏőǒǫǭǿȍȏọỏốồổỗộớờởỡợ',
			'r' => 'rŕŗřȑȓ',
			's' => 'sśŝşšș',
			't' => 'tţťŧț',
			'u' => 'uùúûüũūŭůűųǔǖǘǚǜȕȗụủứừửữự',
			'w' => 'wŵ',
			'y' => 'yýÿŷỳỵỷỹ',
			'z' => 'zźżž',
		);
		$characters = preg_split('//u', (string) $text, -1, PREG_SPLIT_NO_EMPTY);

		if (!is_array($characters)) {
			return preg_quote((string) $text, '/');
		}

		$fragment = '';

		foreach ($characters as $character) {
			$fragment .= isset($variants[$character])
				? '[' . $variants[$character] . ']'
				: preg_quote($character, '/');
		}

		return $fragment;
	}

	/**
	 * Build a phrase hit expression for a field.
	 *
	 * @param string $field_sql Field SQL.
	 * @param array  $phrases Phrases.
	 * @param array  $params SQL params.
	 * @return string
	 */
	private function build_phrase_hit_sql($field_sql, array $phrases, array &$params) {
		if (empty($phrases)) {
			return '0';
		}

		$conditions = array();

		foreach ($phrases as $phrase) {
			$pattern = $this->build_exact_phrase_regexp($phrase);

			if ($pattern === '') {
				continue;
			}

			$conditions[] = "LOWER({$field_sql}) REGEXP %s";
			$params[] = $pattern;
		}

		if (empty($conditions)) {
			return '0';
		}

		return 'IF((' . implode(' OR ', $conditions) . '), 1, 0)';
	}

	/**
	 * Build a unique-term hit count expression for a field.
	 *
	 * @param string $field_sql Field SQL.
	 * @param array  $terms Terms.
	 * @param array  $params SQL params.
	 * @return string
	 */
	private function build_term_hit_sql($field_sql, array $terms, array &$params) {
		if (empty($terms)) {
			return '0';
		}

		$parts = array();

		foreach ($terms as $term) {
			$pattern = $this->build_query_term_regexp($term);

			if ($pattern === '') {
				continue;
			}

			$parts[] = "IF(LOWER({$field_sql}) REGEXP %s, 1, 0)";
			$params[] = $pattern;
		}

		if (empty($parts)) {
			return '0';
		}

		return '(' . implode(' + ', $parts) . ')';
	}

	/**
	 * Return Unicode-aware string length.
	 *
	 * @param string $text Text.
	 * @return int
	 */
	private function string_length($text) {
		if (function_exists('mb_strlen')) {
			return (int) mb_strlen($text, 'UTF-8');
		}

		return strlen($text);
	}

	/**
	 * Normalize frontend/controller filters.
	 *
	 * @param array $filters Raw filters.
	 * @return array
	 */
	private function normalize_filters(array $filters) {
		$enabled_post_types = VFWP_Intranet_Search_Settings::get_enabled_post_types();
		$requested_post_types = array();

		if (isset($filters['post_type'])) {
			$requested_post_types = (array) $filters['post_type'];
		}

		if (isset($filters['post_types'])) {
			$requested_post_types = array_merge($requested_post_types, (array) $filters['post_types']);
		}

		$requested_post_types = array_filter(array_map('sanitize_key', $requested_post_types));
		$post_types = empty($requested_post_types)
			? $enabled_post_types
			: array_values(array_intersect($enabled_post_types, $requested_post_types));

		$object_types = isset($filters['object_type']) ? (array) $filters['object_type'] : array();

		if (isset($filters['object_types'])) {
			$object_types = array_merge($object_types, (array) $filters['object_types']);
		}

		$requested_object_types = !empty($object_types);
		$object_types = array_filter(array_map('sanitize_key', $object_types));
		$object_types = array_values(array_intersect($object_types, array('post')));

		if (empty($object_types)) {
			$object_types = $requested_object_types ? array() : array('post');
		}

		return array(
			'post_types'   => array_values(array_unique($post_types)),
			'object_types' => array_values(array_unique($object_types)),
		);
	}

	/**
	 * Format a database row into a structured result.
	 *
	 * @param array $row SQL row.
	 * @return array
	 */
	private function format_result(array $row, array $parsed_query) {
		$result = array(
			'object_id'     => (int) $row['object_id'],
			'object_type'   => $row['object_type'],
			'post_type'     => $row['post_type'],
			'title'         => $row['title'],
			'url'           => $row['url'],
			'relevance'     => round((float) $row['relevance'], 4),
			'published_at'  => $row['published_at'],
			'updated_at'    => $row['updated_at'],
			'snippet_source' => array(
				'title'        => $row['title'],
				'excerpt'      => $row['excerpt'],
				'content'      => $row['content'],
				'acf_keywords' => $row['acf_keywords'],
			),
			'signals'       => array(
				'exact_title_match' => (int) $row['exact_title_match'],
				'title_phrase_hit'  => (int) $row['title_phrase_hit'],
				'title_term_hits'   => (int) $row['title_term_hits'],
				'acf_phrase_hit'    => (int) $row['acf_phrase_hit'],
				'acf_term_hits'     => (int) $row['acf_term_hits'],
				'excerpt_phrase_hit' => (int) $row['excerpt_phrase_hit'],
				'excerpt_term_hits' => (int) $row['excerpt_term_hits'],
				'content_phrase_hit' => (int) $row['content_phrase_hit'],
					'content_term_hits' => (int) $row['content_term_hits'],
					'all_term_hits'     => (int) $row['all_field_term_hits'],
					'ft_title'         => (float) $row['ft_title'],
					'ft_acf_keywords'  => (float) $row['ft_acf_keywords'],
					'ft_excerpt'       => (float) $row['ft_excerpt'],
					'ft_content'       => (float) $row['ft_content'],
					'recent_content_hit' => isset($row['recent_content_hit']) ? (int) $row['recent_content_hit'] : 0,
					'term_count'        => max(1, count($parsed_query['fulltext_terms'])),
				),
		);

		return $this->snippet_service->add_display_fields($result, $parsed_query);
	}

	/**
	 * Reproduce the SQL relevance formula as readable component rows.
	 *
	 * @param array $result Formatted result.
	 * @return array
	 */
	private function build_score_breakdown(array $result) {
		$signals = isset($result['signals']) && is_array($result['signals']) ? $result['signals'] : array();
		$weights = VFWP_Intranet_Search_Settings::get_field_weights();
		$boosts = VFWP_Intranet_Search_Settings::get_ranking_boosts();
		$term_count = max(1, isset($signals['term_count']) ? (int) $signals['term_count'] : 1);
		$all_term_hits = isset($signals['all_term_hits']) ? (int) $signals['all_term_hits'] : 0;
		$title_term_hits = isset($signals['title_term_hits']) ? (int) $signals['title_term_hits'] : 0;
		$components = array();

		$components[] = $this->score_component('exact_title', __('Exact title match', 'vfwp'), (int) $signals['exact_title_match'], 1, $boosts['exact_title']);
		$components[] = $this->score_component('title_phrase', __('Title phrase match', 'vfwp'), (int) $signals['title_phrase_hit'], $weights['title'], $boosts['title_phrase']);
		$components[] = $this->score_component('title_all_terms', __('All query terms in title', 'vfwp'), $title_term_hits === $term_count ? 1 : 0, $weights['title'], $boosts['title_all_terms']);
		$components[] = $this->score_component('title_terms', __('Individual title terms', 'vfwp'), $title_term_hits, $weights['title'], $boosts['title_term']);
		$components[] = $this->score_component('acf_phrase', __('Exact ACF keyword entry', 'vfwp'), (int) $signals['acf_phrase_hit'], $weights['acf_keywords'], $boosts['acf_phrase']);
		$components[] = $this->score_component('acf_terms', __('ACF keyword terms', 'vfwp'), (int) $signals['acf_term_hits'], $weights['acf_keywords'], $boosts['acf_term']);
		$components[] = $this->score_component('excerpt_phrase', __('Excerpt phrase match', 'vfwp'), (int) $signals['excerpt_phrase_hit'], $weights['excerpt'], $boosts['excerpt_phrase']);
		$components[] = $this->score_component('excerpt_terms', __('Individual excerpt terms', 'vfwp'), (int) $signals['excerpt_term_hits'], $weights['excerpt'], $boosts['excerpt_term']);
		$components[] = $this->score_component('content_phrase', __('Content/document phrase match', 'vfwp'), (int) $signals['content_phrase_hit'], $weights['content'], $boosts['content_phrase']);
		$components[] = $this->score_component('content_terms', __('Individual content/document terms', 'vfwp'), (int) $signals['content_term_hits'], $weights['content'], $boosts['content_term']);
		$components[] = $this->score_component('all_terms', __('All query terms anywhere', 'vfwp'), $all_term_hits === $term_count ? 1 : 0, 1, $boosts['all_terms']);
		$coverage = min(1, $all_term_hits / $term_count);
		$components[] = $this->score_component('term_coverage', __('Query term coverage', 'vfwp'), $coverage, 1, $boosts['term_coverage']);
		$components[] = $this->score_component('fulltext_title', __('FULLTEXT title relevance', 'vfwp'), (float) $signals['ft_title'], $weights['title'], $boosts['fulltext_title']);
		$components[] = $this->score_component('fulltext_acf', __('FULLTEXT ACF relevance', 'vfwp'), (float) $signals['ft_acf_keywords'], $weights['acf_keywords'], $boosts['fulltext_acf']);
		$components[] = $this->score_component('fulltext_excerpt', __('FULLTEXT excerpt relevance', 'vfwp'), (float) $signals['ft_excerpt'], $weights['excerpt'], $boosts['fulltext_excerpt']);
		$components[] = $this->score_component('fulltext_content', __('FULLTEXT content/document relevance', 'vfwp'), (float) $signals['ft_content'], $weights['content'], $boosts['fulltext_content']);

		$base_score = array_sum(array_column($components, 'points'));
		$post_type_weight = VFWP_Intranet_Search_Settings::get_post_type_weight($result['post_type']);
		$weighted_score = $base_score * $post_type_weight;
		$recent_hit = !empty($signals['recent_content_hit']) ? 1 : 0;
		$recency_bonus = $recent_hit * (float) $boosts['recency'];

		return array(
			'components'       => $components,
			'base_score'       => $base_score,
			'post_type_weight' => $post_type_weight,
			'weighted_score'   => $weighted_score,
			'recency_hit'      => $recent_hit,
			'recency_bonus'    => $recency_bonus,
			'calculated_total' => $weighted_score + $recency_bonus,
			'database_total'   => (float) $result['relevance'],
		);
	}

	/**
	 * Build one readable score component.
	 *
	 * @param string $key Component key.
	 * @param string $label Display label.
	 * @param float  $signal Signal value.
	 * @param float  $weight Field weight.
	 * @param float  $boost Ranking boost.
	 * @return array
	 */
	private function score_component($key, $label, $signal, $weight, $boost) {
		$signal = (float) $signal;
		$weight = (float) $weight;
		$boost = (float) $boost;

		return array(
			'key'          => $key,
			'label'        => $label,
			'signal_value' => $signal,
			'field_weight' => $weight,
			'boost'        => $boost,
			'points'       => $signal * $weight * $boost,
		);
	}

	/**
	 * Return a structured empty response.
	 *
	 * @param array  $parsed_query Parsed query.
	 * @param array  $filters Filters.
	 * @param int    $page Page.
	 * @param int    $per_page Per page.
	 * @param string $status Status.
	 * @param int    $total Total count.
	 * @return array
	 */
	private function empty_response(array $parsed_query, array $filters, $page, $per_page, $status, $total = 0) {
		$total_pages = $total > 0 ? (int) ceil($total / $per_page) : 0;

		return array(
			'query'      => $parsed_query,
			'filters'    => $filters,
			'results'    => array(),
			'pagination' => array(
				'page'         => $page,
				'per_page'     => $per_page,
				'total'        => $total,
				'total_pages'  => $total_pages,
				'has_previous' => $page > 1,
				'has_next'     => $page < $total_pages,
				'max_per_page' => self::MAX_PER_PAGE,
				'max_offset'   => self::MAX_OFFSET,
			),
			'status'     => $status,
		);
	}

	/**
	 * Return escaped numeric field weights for SQL interpolation.
	 *
	 * @return array
	 */
	private function get_sql_weights() {
		$weights = VFWP_Intranet_Search_Settings::get_field_weights();

		return array(
			'title'        => $this->sql_float($weights['title']),
			'acf_keywords' => $this->sql_float($weights['acf_keywords']),
			'excerpt'      => $this->sql_float($weights['excerpt']),
			'content'      => $this->sql_float($weights['content']),
		);
	}

	/**
	 * Return escaped numeric ranking boosts for SQL interpolation.
	 *
	 * @return array
	 */
	private function get_sql_ranking_boosts() {
		$boosts = VFWP_Intranet_Search_Settings::get_ranking_boosts();
		$sql_boosts = array();

		foreach (VFWP_Intranet_Search_Settings::default_ranking_boosts() as $boost => $default_value) {
			$sql_boosts[$boost] = $this->sql_float(isset($boosts[$boost]) ? $boosts[$boost] : $default_value);
		}

		return $sql_boosts;
	}

	/**
	 * Build post type weight CASE SQL.
	 *
	 * @param array $post_types Post types.
	 * @return string
	 */
	private function build_post_type_weight_sql(array $post_types) {
		$case_parts = array('CASE scored.post_type');

		foreach ($post_types as $post_type) {
			$case_parts[] = sprintf(
				"WHEN '%s' THEN %s",
				esc_sql($post_type),
				$this->sql_float(VFWP_Intranet_Search_Settings::get_post_type_weight($post_type))
			);
		}

		$case_parts[] = 'ELSE 1 END';

		return implode(' ', $case_parts);
	}

	/**
	 * Prepare SQL with positional parameters.
	 *
	 * @param string $sql SQL.
	 * @param array  $params Params.
	 * @return string
	 */
	private function prepare_sql($sql, array $params) {
		return call_user_func_array(array($this->wpdb, 'prepare'), array_merge(array($sql), $params));
	}

	/**
	 * Format a float for safe SQL interpolation.
	 *
	 * @param mixed $value Numeric value.
	 * @return string
	 */
	private function sql_float($value) {
		return sprintf('%.4F', max(0, (float) $value));
	}
}
