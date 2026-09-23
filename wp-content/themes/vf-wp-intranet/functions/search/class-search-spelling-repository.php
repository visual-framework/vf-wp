<?php
/**
 * Precomputed spelling dictionary for no-results suggestions.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Spelling_Repository {
	const SOURCE_TITLE = 1;
	const SOURCE_KEYWORD = 2;
	const MIN_TERM_LENGTH = 3;
	const MAX_TERM_LENGTH = 64;
	const MAX_OBJECT_TERMS = 200;
	const MAX_CANDIDATES = 100;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var VFWP_Intranet_Search_Query_Parser
	 */
	private $query_parser;

	/**
	 * @param wpdb|null $db Database handle.
	 * @param VFWP_Intranet_Search_Query_Parser|null $query_parser Query parser.
	 */
	public function __construct($db = null, $query_parser = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->query_parser = $query_parser ? $query_parser : new VFWP_Intranet_Search_Query_Parser();
	}

	/**
	 * Synchronize title and keyword terms for one indexed object.
	 *
	 * @param int    $object_id WordPress object ID.
	 * @param string $object_type Indexed object type.
	 * @param string $title Indexed title.
	 * @param string $acf_keywords Indexed ACF keyword text.
	 * @return bool
	 */
	public function sync_object($object_id, $object_type, $title, $acf_keywords) {
		$object_id = (int) $object_id;
		$object_type = sanitize_key($object_type);

		if ($object_id <= 0 || $object_type === '') {
			return false;
		}

		$terms = array();
		$this->collect_terms($terms, $title, self::SOURCE_TITLE);
		$this->collect_terms($terms, $acf_keywords, self::SOURCE_KEYWORD);

		$old_term_ids = $this->get_object_term_ids($object_id, $object_type);

		if (empty($terms)) {
			$this->delete_object($object_id, $object_type);
			return true;
		}

		$now = current_time('mysql', true);
		$term_rows = array();
		$term_params = array();

		foreach ($terms as $term => $term_data) {
			$term_rows[] = '(%s, %s, %d, %s)';
			$term_params[] = $term;
			$term_params[] = $term_data['label'];
			$term_params[] = ((int) $term_data['source'] & self::SOURCE_TITLE) > 0 ? 2 : 1;
			$term_params[] = $now;
		}

		$terms_table = VFWP_Intranet_Search_Schema::spelling_terms_table_name();
		$insert_terms_sql = "INSERT INTO {$terms_table} (term, display_term, display_priority, updated_at) VALUES " . implode(', ', $term_rows)
			. " ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at), display_term = IF(VALUES(display_priority) >= display_priority, VALUES(display_term), display_term), display_priority = GREATEST(display_priority, VALUES(display_priority))";
		$this->wpdb->query($this->wpdb->prepare($insert_terms_sql, $term_params));

		$stored_terms = $this->get_terms_by_values(array_keys($terms));

		if (empty($stored_terms)) {
			return false;
		}

		$objects_table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$this->wpdb->delete(
			$objects_table,
			array(
				'object_type' => $object_type,
				'object_id'   => $object_id,
			),
			array('%s', '%d')
		);

		$mapping_rows = array();
		$mapping_params = array();
		$new_term_ids = array();

		foreach ($stored_terms as $stored_term) {
			$term = (string) $stored_term['term'];
			$term_id = (int) $stored_term['id'];

			if (!isset($terms[$term]) || $term_id <= 0) {
				continue;
			}

			$new_term_ids[] = $term_id;
			$mapping_rows[] = '(%s, %d, %d, %d)';
			$mapping_params[] = $object_type;
			$mapping_params[] = $object_id;
			$mapping_params[] = $term_id;
			$mapping_params[] = (int) $terms[$term]['source'];
		}

		if (!empty($mapping_rows)) {
			$mapping_sql = "INSERT INTO {$objects_table} (object_type, object_id, term_id, source) VALUES " . implode(', ', $mapping_rows)
				. ' ON DUPLICATE KEY UPDATE source = VALUES(source)';
			$this->wpdb->query($this->wpdb->prepare($mapping_sql, $mapping_params));
		}

		$this->ensure_deletion_keys($stored_terms);
		$this->refresh_term_counts(array_merge($old_term_ids, $new_term_ids));

		return true;
	}

	/**
	 * Delete dictionary mappings for one object and clean orphaned terms.
	 *
	 * @param int    $object_id Object ID.
	 * @param string $object_type Object type.
	 * @return bool
	 */
	public function delete_object($object_id, $object_type) {
		$object_id = (int) $object_id;
		$object_type = sanitize_key($object_type);
		$term_ids = $this->get_object_term_ids($object_id, $object_type);
		$objects_table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$result = $this->wpdb->delete(
			$objects_table,
			array(
				'object_type' => $object_type,
				'object_id'   => $object_id,
			),
			array('%s', '%d')
		);

		$this->refresh_term_counts($term_ids);

		return false !== $result;
	}

	/**
	 * Return typo candidates through exact indexed deletion-key lookup.
	 *
	 * @param string $query_term Normalized query term.
	 * @param int    $limit Maximum candidates.
	 * @return array
	 */
	public function find_candidates($query_term, $limit = self::MAX_CANDIDATES) {
		$query_term = $this->normalize_term($query_term);

		if (!$this->is_allowed_term($query_term)) {
			return array();
		}

		$keys = $this->build_deletion_keys($query_term);
		$placeholders = implode(', ', array_fill(0, count($keys), '%s'));
		$min_length = max(self::MIN_TERM_LENGTH, $this->length($query_term) - 2);
		$max_length = min(self::MAX_TERM_LENGTH, $this->length($query_term) + 2);
		$limit = min(self::MAX_CANDIDATES, max(1, (int) $limit));
		$terms_table = VFWP_Intranet_Search_Schema::spelling_terms_table_name();
		$deletions_table = VFWP_Intranet_Search_Schema::spelling_deletions_table_name();
		$sql = "SELECT DISTINCT t.term, t.display_term, t.document_frequency, t.title_frequency, t.keyword_frequency
			FROM {$deletions_table} d
			INNER JOIN {$terms_table} t ON t.id = d.term_id
			WHERE d.deletion_key IN ({$placeholders})
				AND CHAR_LENGTH(t.term) BETWEEN %d AND %d
			ORDER BY t.title_frequency DESC, t.keyword_frequency DESC, t.document_frequency DESC, t.term ASC
			LIMIT %d";
		$params = array_merge($keys, array($min_length, $max_length, $limit));
		$rows = $this->wpdb->get_results($this->wpdb->prepare($sql, $params), ARRAY_A);
		$rows = is_array($rows) ? $rows : array();

		// Long words can contain several substitutions without sharing a one-deletion key.
		// Add a bounded, index-friendly prefix lookup so those candidates still reach
		// the stricter edit-distance check in the suggestion service.
		if ($this->length($query_term) >= 9 && count($rows) < $limit) {
			$prefix = $this->limit_string($query_term, 4);
			$remaining_limit = $limit - count($rows);
			$prefix_sql = "SELECT t.term, t.display_term, t.document_frequency, t.title_frequency, t.keyword_frequency
				FROM {$terms_table} t
				WHERE t.term LIKE %s
					AND CHAR_LENGTH(t.term) BETWEEN %d AND %d
				ORDER BY t.title_frequency DESC, t.keyword_frequency DESC, t.document_frequency DESC, t.term ASC
				LIMIT %d";
			$prefix_rows = $this->wpdb->get_results(
				$this->wpdb->prepare(
					$prefix_sql,
					$this->wpdb->esc_like($prefix) . '%',
					$min_length,
					$max_length,
					$remaining_limit
				),
				ARRAY_A
			);

			if (is_array($prefix_rows)) {
				$merged_rows = array();

				foreach (array_merge($rows, $prefix_rows) as $row) {
					if (!empty($row['term'])) {
						$merged_rows[(string) $row['term']] = $row;
					}
				}

				$rows = array_slice(array_values($merged_rows), 0, $limit);
			}
		}

		return $rows;
	}

	/**
	 * Return lightweight dictionary counts for index diagnostics.
	 *
	 * @return array
	 */
	public function get_counts() {
		return array(
			'terms'         => (int) $this->wpdb->get_var('SELECT COUNT(*) FROM ' . VFWP_Intranet_Search_Schema::spelling_terms_table_name()),
			'deletion_keys' => (int) $this->wpdb->get_var('SELECT COUNT(*) FROM ' . VFWP_Intranet_Search_Schema::spelling_deletions_table_name()),
			'objects'       => (int) $this->wpdb->get_var('SELECT COUNT(DISTINCT object_type, object_id) FROM ' . VFWP_Intranet_Search_Schema::spelling_objects_table_name()),
		);
	}

	/**
	 * Remove dictionary mappings whose search-index object no longer exists.
	 *
	 * @return int Removed mappings.
	 */
	public function prune_missing_objects() {
		$objects_table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$index_table = VFWP_Intranet_Search_Schema::table_name();
		$result = $this->wpdb->query(
			"DELETE o FROM {$objects_table} o
			LEFT JOIN {$index_table} i ON i.object_type = o.object_type AND i.object_id = o.object_id
			WHERE i.id IS NULL"
		);

		$this->remove_orphaned_terms();

		return false === $result ? 0 : (int) $result;
	}

	/**
	 * Empty all spelling dictionary tables.
	 *
	 * @return bool
	 */
	public function truncate() {
		$deletions = false !== $this->wpdb->query('TRUNCATE TABLE ' . VFWP_Intranet_Search_Schema::spelling_deletions_table_name());
		$objects = false !== $this->wpdb->query('TRUNCATE TABLE ' . VFWP_Intranet_Search_Schema::spelling_objects_table_name());
		$terms = false !== $this->wpdb->query('TRUNCATE TABLE ' . VFWP_Intranet_Search_Schema::spelling_terms_table_name());

		return $deletions && $objects && $terms;
	}

	/**
	 * Collect bounded normalized terms from one source field.
	 *
	 * @param array  $terms Collected terms.
	 * @param mixed  $text Source text.
	 * @param int    $source Source bit.
	 * @return void
	 */
	private function collect_terms(array &$terms, $text, $source) {
		if (!is_scalar($text) || count($terms) >= self::MAX_OBJECT_TERMS) {
			return;
		}

		preg_match_all('/[\p{L}\p{N}]+/u', wp_strip_all_tags((string) $text), $matches);
		$stopwords = class_exists('VFWP_Intranet_Search_Settings') ? VFWP_Intranet_Search_Settings::get_stopwords() : array();

		foreach ($matches[0] as $label) {
			$term = $this->normalize_term($label);

			if (!$this->is_allowed_term($term) || in_array($term, $stopwords, true)) {
				continue;
			}

			if (!isset($terms[$term])) {
				$terms[$term] = array(
					'label'  => $this->limit_string($label, 100),
					'source' => 0,
				);
			}

			$terms[$term]['source'] |= (int) $source;

			if (self::SOURCE_TITLE === (int) $source) {
				$terms[$term]['label'] = $this->limit_string($label, 100);
			}

			if (count($terms) >= self::MAX_OBJECT_TERMS) {
				break;
			}
		}
	}

	/**
	 * Return term rows keyed by normalized values.
	 *
	 * @param array $terms Terms.
	 * @return array
	 */
	private function get_terms_by_values(array $terms) {
		$terms = array_values(array_unique(array_filter($terms)));

		if (empty($terms)) {
			return array();
		}

		$table = VFWP_Intranet_Search_Schema::spelling_terms_table_name();
		$placeholders = implode(', ', array_fill(0, count($terms), '%s'));
		$rows = $this->wpdb->get_results(
			$this->wpdb->prepare("SELECT id, term, display_term FROM {$table} WHERE term IN ({$placeholders})", $terms),
			ARRAY_A
		);

		return is_array($rows) ? $rows : array();
	}

	/**
	 * Return existing term IDs for one object.
	 *
	 * @param int    $object_id Object ID.
	 * @param string $object_type Object type.
	 * @return array
	 */
	private function get_object_term_ids($object_id, $object_type) {
		$table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$ids = $this->wpdb->get_col(
			$this->wpdb->prepare(
				"SELECT term_id FROM {$table} WHERE object_type = %s AND object_id = %d",
				(string) $object_type,
				(int) $object_id
			)
		);

		return array_values(array_unique(array_map('intval', (array) $ids)));
	}

	/**
	 * Ensure exact and one-character deletion keys exist for terms.
	 *
	 * @param array $stored_terms Stored term rows.
	 * @return void
	 */
	private function ensure_deletion_keys(array $stored_terms) {
		$rows = array();
		$params = array();

		foreach ($stored_terms as $stored_term) {
			$term_id = isset($stored_term['id']) ? (int) $stored_term['id'] : 0;
			$term = isset($stored_term['term']) ? (string) $stored_term['term'] : '';

			if ($term_id <= 0 || $term === '') {
				continue;
			}

			foreach ($this->build_deletion_keys($term) as $deletion_key) {
				$rows[] = '(%s, %d)';
				$params[] = $deletion_key;
				$params[] = $term_id;
			}
		}

		if (empty($rows)) {
			return;
		}

		$table = VFWP_Intranet_Search_Schema::spelling_deletions_table_name();
		$sql = "INSERT IGNORE INTO {$table} (deletion_key, term_id) VALUES " . implode(', ', $rows);
		$this->wpdb->query($this->wpdb->prepare($sql, $params));
	}

	/**
	 * Refresh aggregate frequencies and remove terms no longer used by any object.
	 *
	 * @param array $term_ids Term IDs.
	 * @return void
	 */
	private function refresh_term_counts(array $term_ids) {
		$term_ids = array_values(array_unique(array_filter(array_map('intval', $term_ids))));

		if (empty($term_ids)) {
			return;
		}

		$id_sql = implode(', ', $term_ids);
		$terms_table = VFWP_Intranet_Search_Schema::spelling_terms_table_name();
		$objects_table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$this->wpdb->query(
			"UPDATE {$terms_table} t
			LEFT JOIN (
				SELECT term_id,
					COUNT(*) AS document_frequency,
					SUM((source & " . self::SOURCE_TITLE . ") > 0) AS title_frequency,
					SUM((source & " . self::SOURCE_KEYWORD . ") > 0) AS keyword_frequency
				FROM {$objects_table}
				WHERE term_id IN ({$id_sql})
				GROUP BY term_id
			) counts ON counts.term_id = t.id
			SET t.document_frequency = COALESCE(counts.document_frequency, 0),
				t.title_frequency = COALESCE(counts.title_frequency, 0),
				t.keyword_frequency = COALESCE(counts.keyword_frequency, 0)
			WHERE t.id IN ({$id_sql})"
		);

		$this->remove_orphaned_terms($term_ids);
	}

	/**
	 * Remove terms and deletion keys with no object mappings.
	 *
	 * @param array $term_ids Optional bounded term IDs.
	 * @return void
	 */
	private function remove_orphaned_terms(array $term_ids = array()) {
		$terms_table = VFWP_Intranet_Search_Schema::spelling_terms_table_name();
		$deletions_table = VFWP_Intranet_Search_Schema::spelling_deletions_table_name();
		$objects_table = VFWP_Intranet_Search_Schema::spelling_objects_table_name();
		$where_sql = '';

		if (!empty($term_ids)) {
			$where_sql = ' AND t.id IN (' . implode(', ', array_values(array_unique(array_map('intval', $term_ids)))) . ')';
		}

		$this->wpdb->query(
			"DELETE d FROM {$deletions_table} d
			INNER JOIN {$terms_table} t ON t.id = d.term_id
			LEFT JOIN {$objects_table} o ON o.term_id = t.id
			WHERE o.term_id IS NULL{$where_sql}"
		);
		$this->wpdb->query(
			"DELETE t FROM {$terms_table} t
			LEFT JOIN {$objects_table} o ON o.term_id = t.id
			WHERE o.term_id IS NULL{$where_sql}"
		);
	}

	/**
	 * Return an exact term plus each one-character deletion.
	 *
	 * @param string $term Normalized term.
	 * @return array
	 */
	private function build_deletion_keys($term) {
		$characters = preg_split('//u', (string) $term, -1, PREG_SPLIT_NO_EMPTY);
		$keys = array((string) $term => (string) $term);

		if (!is_array($characters)) {
			return array_values($keys);
		}

		foreach ($characters as $index => $character) {
			$deletion = $characters;
			unset($deletion[$index]);
			$deletion = implode('', $deletion);

			if ($deletion !== '') {
				$keys[$deletion] = $deletion;
			}
		}

		return array_values($keys);
	}

	/**
	 * Normalize one dictionary term.
	 *
	 * @param mixed $term Raw term.
	 * @return string
	 */
	private function normalize_term($term) {
		$normalized = $this->query_parser->normalize_search_text(is_scalar($term) ? (string) $term : '');

		return strpos($normalized, ' ') === false ? $normalized : '';
	}

	/**
	 * Determine whether a term belongs in the bounded dictionary.
	 *
	 * @param string $term Normalized term.
	 * @return bool
	 */
	private function is_allowed_term($term) {
		$length = $this->length($term);

		return $length >= self::MIN_TERM_LENGTH
			&& $length <= self::MAX_TERM_LENGTH
			&& preg_match('/\p{L}/u', $term) === 1;
	}

	/**
	 * Unicode-aware string length.
	 *
	 * @param string $text Text.
	 * @return int
	 */
	private function length($text) {
		return function_exists('mb_strlen') ? (int) mb_strlen((string) $text, 'UTF-8') : strlen((string) $text);
	}

	/**
	 * Limit a UTF-8 string.
	 *
	 * @param string $text Text.
	 * @param int    $limit Character limit.
	 * @return string
	 */
	private function limit_string($text, $limit) {
		return function_exists('mb_substr') ? (string) mb_substr((string) $text, 0, (int) $limit, 'UTF-8') : substr((string) $text, 0, (int) $limit);
	}
}
