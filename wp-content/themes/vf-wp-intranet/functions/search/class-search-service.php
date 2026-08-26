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
				(
					(
						(scored.exact_title_match * 1000)
						+ (scored.title_phrase_hit * {$score_parts['weights']['title']} * 250)
						+ (IF(scored.title_term_hits = {$score_parts['term_count']}, 1, 0) * {$score_parts['weights']['title']} * 100)
						+ (scored.title_term_hits * {$score_parts['weights']['title']} * 20)
						+ (scored.acf_phrase_hit * {$score_parts['weights']['acf_keywords']} * 140)
						+ (scored.acf_term_hits * {$score_parts['weights']['acf_keywords']} * 12)
						+ (scored.excerpt_phrase_hit * {$score_parts['weights']['excerpt']} * 80)
						+ (scored.excerpt_term_hits * {$score_parts['weights']['excerpt']} * 8)
						+ (scored.content_phrase_hit * {$score_parts['weights']['content']} * 30)
						+ (scored.content_term_hits * {$score_parts['weights']['content']} * 4)
						+ (IF(scored.all_field_term_hits = {$score_parts['term_count']}, 1, 0) * 160)
						+ ((scored.all_field_term_hits / {$score_parts['term_count']}) * 100)
						+ (scored.ft_title * {$score_parts['weights']['title']} * 25)
						+ (scored.ft_acf_keywords * {$score_parts['weights']['acf_keywords']} * 18)
						+ (scored.ft_excerpt * {$score_parts['weights']['excerpt']} * 10)
						+ (scored.ft_content * {$score_parts['weights']['content']} * 4)
					)
					* {$post_type_weight_sql}
				)
				+ IF(scored.published_at >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 30 DAY), 2, 0) AS relevance
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
		$table_name = VFWP_Intranet_Search_Schema::table_name();
		$params = array();
		$conditions = array(
			'visibility = %s',
			'MATCH(title,excerpt,content,acf_keywords) AGAINST (%s IN BOOLEAN MODE)',
		);
		$params[] = 'public';
		$params[] = $parsed_query['boolean_query'];

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
			$this->build_phrase_hit_sql('acf_keywords', $parsed_query['phrases'], $params) . ' AS acf_phrase_hit',
			$this->build_phrase_hit_sql('excerpt', $parsed_query['phrases'], $params) . ' AS excerpt_phrase_hit',
			$this->build_phrase_hit_sql('content', $parsed_query['phrases'], $params) . ' AS content_phrase_hit',
			$this->build_term_hit_sql('title', $parsed_query['fulltext_terms'], $params) . ' AS title_term_hits',
			$this->build_term_hit_sql('acf_keywords', $parsed_query['fulltext_terms'], $params) . ' AS acf_term_hits',
			$this->build_term_hit_sql('excerpt', $parsed_query['fulltext_terms'], $params) . ' AS excerpt_term_hits',
			$this->build_term_hit_sql('content', $parsed_query['fulltext_terms'], $params) . ' AS content_term_hits',
			$this->build_term_hit_sql("CONCAT_WS(' ', title, excerpt, content, acf_keywords)", $parsed_query['fulltext_terms'], $params) . ' AS all_field_term_hits',
			'MATCH(title) AGAINST (%s IN BOOLEAN MODE) AS ft_title',
			'MATCH(acf_keywords) AGAINST (%s IN BOOLEAN MODE) AS ft_acf_keywords',
			'MATCH(excerpt) AGAINST (%s IN BOOLEAN MODE) AS ft_excerpt',
			'MATCH(content) AGAINST (%s IN BOOLEAN MODE) AS ft_content',
		);

		array_unshift($params, $parsed_query['normalized']);
		$params[] = $parsed_query['boolean_query'];
		$params[] = $parsed_query['boolean_query'];
		$params[] = $parsed_query['boolean_query'];
		$params[] = $parsed_query['boolean_query'];

		return array(
			'select_sql'  => implode(",\n\t\t\t\t\t", $select_parts),
			'params'      => $params,
			'term_count'  => $term_count,
			'weights'     => $weights,
		);
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
			$conditions[] = "LOCATE(%s, {$field_sql}) > 0";
			$params[] = $phrase;
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
			$parts[] = "IF(LOCATE(%s, {$field_sql}) > 0, 1, 0)";
			$params[] = $term;
		}

		return '(' . implode(' + ', $parts) . ')';
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

		$object_types = array_filter(array_map('sanitize_key', $object_types));

		if (empty($object_types)) {
			$object_types = array('post', 'pdf');
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
				'all_term_hits'     => (int) $row['all_field_term_hits'],
			),
		);

		return $this->snippet_service->add_display_fields($result, $parsed_query);
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
