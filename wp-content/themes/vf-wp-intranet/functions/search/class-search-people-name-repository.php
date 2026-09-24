<?php
/**
 * Precomputed fuzzy-name index for People search suggestions.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_People_Name_Repository {
	const MAX_VARIANTS = 40;
	const MAX_NGRAMS = 400;
	const MAX_CANDIDATES = 50;

	/** @var wpdb */
	private $wpdb;

	/** @var VFWP_Intranet_Search_Query_Parser */
	private $query_parser;

	public function __construct($db = null, $query_parser = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->query_parser = $query_parser ? $query_parser : new VFWP_Intranet_Search_Query_Parser();
	}

	/**
	 * Synchronize one indexed object with the People-name dictionary.
	 *
	 * @param array $row Search-index row.
	 * @return bool
	 */
	public function sync_object(array $row) {
		$object_id = isset($row['object_id']) ? (int) $row['object_id'] : 0;
		$object_type = isset($row['object_type']) ? (string) $row['object_type'] : '';

		if ($object_id <= 0 || $object_type !== 'post') {
			return false;
		}

		if (
			!isset($row['post_type'], $row['post_status'], $row['visibility'])
			|| $row['post_type'] !== 'people'
			|| $row['post_status'] !== 'publish'
			|| $row['visibility'] !== 'public'
		) {
			return $this->delete_object($object_id);
		}

		$display_name = trim(wp_strip_all_tags(isset($row['title']) ? (string) $row['title'] : ''));
		$normalized_name = $this->normalize_name($display_name);

		if ($normalized_name === '') {
			return $this->delete_object($object_id);
		}

		$variants = array($normalized_name => $normalized_name);
		$this->add_name_tokens($variants, $normalized_name);

		$keyword_text = isset($row['acf_keywords']) ? (string) $row['acf_keywords'] : '';
		$aliases = preg_split('/[,;\r\n|]+/u', $keyword_text);

		foreach (is_array($aliases) ? $aliases : array() as $alias) {
			$normalized_alias = $this->normalize_name($alias);

			if ($normalized_alias === '' || count($variants) >= self::MAX_VARIANTS) {
				continue;
			}

			$variants[$normalized_alias] = $normalized_alias;
			$this->add_name_tokens($variants, $normalized_alias);
		}

		$variants = array_slice(array_values($variants), 0, self::MAX_VARIANTS);
		$encoded_variants = wp_json_encode($variants);

		if (!is_string($encoded_variants)) {
			return false;
		}

		$names_table = VFWP_Intranet_Search_Schema::people_names_table_name();
		$result = $this->wpdb->replace(
			$names_table,
			array(
				'object_id'       => $object_id,
				'display_name'    => $this->limit_string($display_name, 240),
				'normalized_name' => $this->limit_string($normalized_name, 191),
				'variants_json'   => $encoded_variants,
				'updated_at'      => current_time('mysql', true),
			),
			array('%d', '%s', '%s', '%s', '%s')
		);

		if (false === $result) {
			return false;
		}

		$ngrams = array();

		foreach ($variants as $variant) {
			foreach ($this->build_trigrams($variant) as $ngram) {
				$ngrams[$ngram] = $ngram;

				if (count($ngrams) >= self::MAX_NGRAMS) {
					break 2;
				}
			}
		}

		$ngrams_table = VFWP_Intranet_Search_Schema::people_name_ngrams_table_name();
		$this->wpdb->delete($ngrams_table, array('object_id' => $object_id), array('%d'));

		if (empty($ngrams)) {
			return true;
		}

		$values = array();
		$params = array();

		foreach ($ngrams as $ngram) {
			$values[] = '(%s, %d)';
			$params[] = $ngram;
			$params[] = $object_id;
		}

		$sql = "INSERT IGNORE INTO {$ngrams_table} (ngram, object_id) VALUES " . implode(', ', $values);

		return false !== $this->wpdb->query($this->wpdb->prepare($sql, $params));
	}

	/**
	 * Remove one Person from the fuzzy dictionary.
	 *
	 * @param int $object_id Post ID.
	 * @return bool
	 */
	public function delete_object($object_id) {
		$object_id = (int) $object_id;

		if ($object_id <= 0) {
			return false;
		}

		$ngrams = $this->wpdb->delete(
			VFWP_Intranet_Search_Schema::people_name_ngrams_table_name(),
			array('object_id' => $object_id),
			array('%d')
		);
		$names = $this->wpdb->delete(
			VFWP_Intranet_Search_Schema::people_names_table_name(),
			array('object_id' => $object_id),
			array('%d')
		);

		return false !== $ngrams && false !== $names;
	}

	/**
	 * Empty both People-name dictionary tables.
	 *
	 * @return bool
	 */
	public function truncate() {
		$ngrams = false !== $this->wpdb->query('TRUNCATE TABLE ' . VFWP_Intranet_Search_Schema::people_name_ngrams_table_name());
		$names = false !== $this->wpdb->query('TRUNCATE TABLE ' . VFWP_Intranet_Search_Schema::people_names_table_name());

		return $ngrams && $names;
	}

	/**
	 * Remove dictionary rows that no longer have a public indexed Person.
	 *
	 * @return int
	 */
	public function prune_missing_objects() {
		$names_table = VFWP_Intranet_Search_Schema::people_names_table_name();
		$ngrams_table = VFWP_Intranet_Search_Schema::people_name_ngrams_table_name();
		$index_table = VFWP_Intranet_Search_Schema::table_name();
		$stale_ids = $this->wpdb->get_col(
			"SELECT p.object_id
			FROM {$names_table} p
			LEFT JOIN {$index_table} i ON i.object_type = 'post' AND i.object_id = p.object_id
				AND i.post_type = 'people' AND i.post_status = 'publish' AND i.visibility = 'public'
			WHERE i.id IS NULL"
		);

		if (empty($stale_ids)) {
			return 0;
		}

		$ids = array_values(array_unique(array_filter(array_map('intval', $stale_ids))));

		if (empty($ids)) {
			return 0;
		}

		$id_sql = implode(', ', $ids);
		$this->wpdb->query("DELETE FROM {$ngrams_table} WHERE object_id IN ({$id_sql})");
		$result = $this->wpdb->query("DELETE FROM {$names_table} WHERE object_id IN ({$id_sql})");

		return false === $result ? 0 : (int) $result;
	}

	/**
	 * Return high-confidence fuzzy Person-name corrections.
	 *
	 * @param mixed $query Visitor query.
	 * @param int   $limit Maximum suggestions.
	 * @return array
	 */
	public function find_suggestions($query, $limit = 3) {
		$normalized_query = $this->normalize_name($query);
		$query_terms = $normalized_query === '' ? array() : preg_split('/\s+/u', $normalized_query);

		if (
			$normalized_query === ''
			|| !is_array($query_terms)
			|| count($query_terms) > 4
			|| preg_match('/[^\p{L}\s]/u', $normalized_query)
		) {
			return array();
		}

		$query_ngrams = $this->build_trigrams($normalized_query);

		if (empty($query_ngrams)) {
			return array();
		}

		$limit = min(3, max(1, (int) $limit));
		$ngrams_table = VFWP_Intranet_Search_Schema::people_name_ngrams_table_name();
		$placeholders = implode(', ', array_fill(0, count($query_ngrams), '%s'));
		$sql = "SELECT object_id, COUNT(DISTINCT ngram) AS overlap_count
			FROM {$ngrams_table}
			WHERE ngram IN ({$placeholders})
			GROUP BY object_id
			ORDER BY overlap_count DESC, object_id ASC
			LIMIT %d";
		$params = array_merge($query_ngrams, array(self::MAX_CANDIDATES));
		$candidate_rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $params), ARRAY_A);

		if (empty($candidate_rows)) {
			return array();
		}

		$overlaps = array();

		foreach ($candidate_rows as $candidate_row) {
			$overlaps[(int) $candidate_row['object_id']] = (int) $candidate_row['overlap_count'];
		}

		$ids = array_keys($overlaps);
		$names_table = VFWP_Intranet_Search_Schema::people_names_table_name();
		$rows = $this->wpdb->get_results(
			"SELECT object_id, display_name, normalized_name, variants_json
			FROM {$names_table}
			WHERE object_id IN (" . implode(', ', array_map('intval', $ids)) . ')',
			ARRAY_A
		);
		$threshold = $this->get_confidence_threshold($normalized_query, count($query_terms));
		$suggestions = array();

		foreach ((array) $rows as $row) {
			$variants = json_decode(isset($row['variants_json']) ? (string) $row['variants_json'] : '', true);

			if (!is_array($variants)) {
				$variants = array();
			}

			$normalized_name = isset($row['normalized_name']) ? (string) $row['normalized_name'] : '';
			$variants[] = $normalized_name;
			$score = $this->score_name_candidate($normalized_query, $query_terms, array_values(array_unique(array_filter($variants))));

			if ($score < $threshold) {
				continue;
			}

			$suggestions[] = array(
				'query'       => $normalized_name,
				'label'       => isset($row['display_name']) ? (string) $row['display_name'] : $normalized_name,
				'score'       => round($score, 4),
				'object_id'   => (int) $row['object_id'],
				'overlap'     => isset($overlaps[(int) $row['object_id']]) ? $overlaps[(int) $row['object_id']] : 0,
			);
		}

		usort($suggestions, static function ($a, $b) {
			if ((float) $a['score'] !== (float) $b['score']) {
				return (float) $a['score'] < (float) $b['score'] ? 1 : -1;
			}

			if ((int) $a['overlap'] !== (int) $b['overlap']) {
				return (int) $b['overlap'] - (int) $a['overlap'];
			}

			return strcasecmp((string) $a['label'], (string) $b['label']);
		});

		return array_slice($suggestions, 0, $limit);
	}

	/**
	 * Add full-name tokens as first/last-name lookup variants.
	 */
	private function add_name_tokens(array &$variants, $name) {
		$tokens = preg_split('/\s+/u', (string) $name);

		foreach (is_array($tokens) ? $tokens : array() as $token) {
			if ($this->length($token) >= 3 && count($variants) < self::MAX_VARIANTS) {
				$variants[$token] = $token;
			}
		}
	}

	/**
	 * Calculate a bounded fuzzy score for one Person.
	 */
	private function score_name_candidate($query, array $query_terms, array $variants) {
		if (count($query_terms) > 1) {
			$best_sequence = 0.0;

			foreach ($variants as $variant) {
				$candidate_terms = preg_split('/\s+/u', (string) $variant);

				if (!is_array($candidate_terms) || count($candidate_terms) < count($query_terms)) {
					continue;
				}

				$best_sequence = max($best_sequence, $this->score_token_sequence($query_terms, $candidate_terms));
			}

			return min(1.0, $best_sequence);
		}

		$best = 0.0;

		foreach ($variants as $variant) {
			$best = max($best, $this->score_strings($query, $variant));
		}

		return min(1.0, $best);
	}

	/**
	 * Score a multi-part name while requiring every entered token to have a
	 * credible, distinct counterpart in the candidate name.
	 */
	private function score_token_sequence(array $query_terms, array $candidate_terms) {
		$used_indexes = array();
		$matched_indexes = array();
		$scores = array();

		foreach ($query_terms as $query_term) {
			$best_score = 0.0;
			$best_index = -1;

			foreach ($candidate_terms as $index => $candidate_term) {
				if (isset($used_indexes[$index])) {
					continue;
				}

				$score = $this->score_strings($query_term, $candidate_term);

				if ($score > $best_score) {
					$best_score = $score;
					$best_index = (int) $index;
				}
			}

			if ($best_index < 0 || $best_score < 0.45) {
				return 0.0;
			}

			$used_indexes[$best_index] = true;
			$matched_indexes[] = $best_index;
			$scores[] = $best_score;
		}

		$ordered = true;

		for ($index = 1; $index < count($matched_indexes); $index++) {
			if ($matched_indexes[$index] <= $matched_indexes[$index - 1]) {
				$ordered = false;
				break;
			}
		}

		$score = array_sum($scores) / max(1, count($scores));

		return min(1.0, ($score * 0.97) + ($ordered ? 0.03 : 0.0));
	}

	/**
	 * Combine trigram overlap, Damerau-Levenshtein similarity, and prefix confidence.
	 */
	private function score_strings($left, $right) {
		$left = (string) $left;
		$right = (string) $right;

		if ($left === '' || $right === '') {
			return 0.0;
		}

		if ($left === $right) {
			return 1.0;
		}

		$left_ngrams = $this->build_trigrams($left);
		$right_ngrams = $this->build_trigrams($right);
		$overlap = count(array_intersect($left_ngrams, $right_ngrams));
		$dice = empty($left_ngrams) || empty($right_ngrams)
			? 0.0
			: (2 * $overlap) / (count($left_ngrams) + count($right_ngrams));
		$max_length = max($this->length($left), $this->length($right));
		$distance = $this->damerau_levenshtein($left, $right);
		$edit_similarity = $max_length > 0 ? max(0, 1 - ($distance / $max_length)) : 0;
		$prefix = 0.0;

		if ($this->substring($left, 0, 1) === $this->substring($right, 0, 1)) {
			$prefix += 0.5;
		}

		if ($this->length($left) >= 2 && $this->length($right) >= 2 && $this->substring($left, 0, 2) === $this->substring($right, 0, 2)) {
			$prefix += 0.5;
		}

		$score = ($dice * 0.50) + ($edit_similarity * 0.40) + ($prefix * 0.10);
		$left_skeleton = $this->build_consonant_skeleton($left);
		$right_skeleton = $this->build_consonant_skeleton($right);
		$left_length = max(1, $this->length(str_replace(' ', '', $left)));
		$consonant_ratio = $this->length($left_skeleton) / $left_length;

		if ($consonant_ratio >= 0.65 && $this->length($left_skeleton) >= 3 && $left_skeleton === $right_skeleton) {
			$score = max($score, 0.82);
		}

		return $score;
	}

	/**
	 * Remove common Latin vowels to recognize severe missing-vowel name typos.
	 */
	private function build_consonant_skeleton($text) {
		$skeleton = preg_replace('/[aeiouy\s]+/u', '', (string) $text);

		return is_string($skeleton) ? $skeleton : '';
	}

	/**
	 * Optimal-string-alignment Damerau-Levenshtein distance for short names.
	 */
	private function damerau_levenshtein($left, $right) {
		$a = preg_split('//u', (string) $left, -1, PREG_SPLIT_NO_EMPTY);
		$b = preg_split('//u', (string) $right, -1, PREG_SPLIT_NO_EMPTY);

		if (!is_array($a) || !is_array($b)) {
			return max(strlen((string) $left), strlen((string) $right));
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

	private function get_confidence_threshold($query, $term_count) {
		$length = $this->length(str_replace(' ', '', (string) $query));

		if ($term_count > 1) {
			return 0.56;
		}

		if ($length <= 4) {
			return 0.76;
		}

		if ($length <= 7) {
			return 0.64;
		}

		return 0.56;
	}

	private function build_trigrams($text) {
		$text = str_replace(' ', '_', trim((string) $text));

		if ($text === '') {
			return array();
		}

		$padded = '^' . $text . '$';
		$length = $this->length($padded);
		$ngrams = array();

		if ($length < 3) {
			return array($padded);
		}

		for ($index = 0; $index <= $length - 3; $index++) {
			$ngram = $this->substring($padded, $index, 3);
			$ngrams[$ngram] = $ngram;
		}

		return array_values($ngrams);
	}

	private function normalize_name($name) {
		$normalized = $this->query_parser->normalize_search_text(is_scalar($name) ? (string) $name : '');

		return $this->limit_string($normalized, 191);
	}

	private function length($text) {
		return function_exists('mb_strlen') ? (int) mb_strlen((string) $text, 'UTF-8') : strlen((string) $text);
	}

	private function substring($text, $start, $length) {
		return function_exists('mb_substr')
			? (string) mb_substr((string) $text, (int) $start, (int) $length, 'UTF-8')
			: substr((string) $text, (int) $start, (int) $length);
	}

	private function limit_string($text, $limit) {
		return $this->substring((string) $text, 0, (int) $limit);
	}
}
