<?php
/**
 * Persistence layer for the theme search index.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Index_Repository {
	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var string
	 */
	private $table_name;

	/**
	 * @var VFWP_Intranet_Search_Spelling_Repository|null
	 */
	private $spelling_repository;

	/** @var VFWP_Intranet_Search_People_Name_Repository|null */
	private $people_name_repository;

	/**
	 * @param wpdb|null $db WordPress database object.
	 */
	public function __construct($db = null) {
		global $wpdb;

		$this->wpdb = $db ? $db : $wpdb;
		$this->table_name = VFWP_Intranet_Search_Schema::table_name();
	}

	/**
	 * Insert or update an indexed object, skipping unchanged content.
	 *
	 * @param array $data Index row.
	 * @return string inserted|updated|skipped|failed
	 */
	public function upsert(array $data, $force = false) {
		$existing = $this->find($data['object_id'], $data['object_type']);
		$now = current_time('mysql', true);

		$row = array(
			'object_id'      => (int) $data['object_id'],
			'object_type'    => (string) $data['object_type'],
			'post_type'      => (string) $data['post_type'],
			'post_status'    => (string) $data['post_status'],
			'visibility'     => (string) $data['visibility'],
			'title'          => (string) $data['title'],
			'excerpt'        => (string) $data['excerpt'],
			'content'        => (string) $data['content'],
			'acf_keywords'   => (string) $data['acf_keywords'],
			'url'            => (string) $data['url'],
			'published_at'   => $data['published_at'],
			'updated_at'     => $data['updated_at'],
			'indexed_at'     => $now,
			'schema_version' => (int) $data['schema_version'],
			'content_hash'   => (string) $data['content_hash'],
			'source_hash'    => isset($data['source_hash']) ? (string) $data['source_hash'] : (string) $data['content_hash'],
			'parent_object_id'  => isset($data['parent_object_id']) ? (int) $data['parent_object_id'] : 0,
			'file_name'         => isset($data['file_name']) ? (string) $data['file_name'] : '',
			'extraction_status' => isset($data['extraction_status']) ? (string) $data['extraction_status'] : '',
			'extraction_error'  => isset($data['extraction_error']) ? (string) $data['extraction_error'] : '',
			'rebuild_token'     => isset($data['rebuild_token']) ? (string) $data['rebuild_token'] : (isset($existing['rebuild_token']) ? (string) $existing['rebuild_token'] : ''),
		);

		$formats = array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%s',
			'%s',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
		);

		if ($existing) {
			if (
				!$force
				&&
				hash_equals((string) $existing['content_hash'], $row['content_hash'])
				&& (int) $existing['schema_version'] === $row['schema_version']
				&& hash_equals((string) $existing['rebuild_token'], $row['rebuild_token'])
			) {
				return 'skipped';
			}

			$result = $this->wpdb->update(
				$this->table_name,
				$row,
				array(
					'object_id'   => $row['object_id'],
					'object_type' => $row['object_type'],
				),
				$formats,
				array('%d', '%s')
			);

			if (false === $result) {
				return 'failed';
			}

			$this->sync_spelling_dictionary($row);
			$this->sync_people_name_dictionary($row);

			return 'updated';
		}

		$result = $this->wpdb->insert($this->table_name, $row, $formats);

		if (false === $result) {
			return 'failed';
		}

		$this->sync_spelling_dictionary($row);
		$this->sync_people_name_dictionary($row);

		return 'inserted';
	}

	/**
	 * Delete an indexed object.
	 *
	 * @param int    $object_id Object ID.
	 * @param string $object_type Object type.
	 * @return bool
	 */
	public function delete($object_id, $object_type = 'post') {
		$result = $this->wpdb->delete(
			$this->table_name,
			array(
				'object_id'   => (int) $object_id,
				'object_type' => (string) $object_type,
			),
			array('%d', '%s')
		);

		if (false !== $result) {
			$this->get_spelling_repository()->delete_object((int) $object_id, (string) $object_type);

			if ('post' === (string) $object_type) {
				$this->get_people_name_repository()->delete_object((int) $object_id);
			}
		}

		return false !== $result;
	}

	/**
	 * Fetch an indexed object by object identity.
	 *
	 * @param int    $object_id Object ID.
	 * @param string $object_type Object type.
	 * @return array|null
	 */
	public function find($object_id, $object_type = 'post') {
		$row = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE object_id = %d AND object_type = %s LIMIT 1",
				(int) $object_id,
				(string) $object_type
			),
			ARRAY_A
		);

		return is_array($row) ? $row : null;
	}

	/**
	 * Count indexed rows.
	 *
	 * @return int
	 */
	public function count() {
		return (int) $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
	}

	/**
	 * Empty the custom search index table.
	 *
	 * @return bool
	 */
	public function truncate() {
		$result = false !== $this->wpdb->query("TRUNCATE TABLE {$this->table_name}");

		if ($result) {
			$this->get_spelling_repository()->truncate();
			$this->get_people_name_repository()->truncate();
		}

		return $result;
	}

	/**
	 * Mark an unchanged row as seen by a full rebuild.
	 *
	 * @param int    $object_id Object ID.
	 * @param string $object_type Object type.
	 * @param string $rebuild_token Rebuild token.
	 * @return bool
	 */
	public function mark_rebuild_token($object_id, $object_type, $rebuild_token) {
		if ($rebuild_token === '') {
			return true;
		}

		$result = $this->wpdb->update(
			$this->table_name,
			array(
				'rebuild_token' => (string) $rebuild_token,
				'indexed_at'    => current_time('mysql', true),
			),
			array(
				'object_id'   => (int) $object_id,
				'object_type' => (string) $object_type,
			),
			array('%s', '%s'),
			array('%d', '%s')
		);

		return false !== $result;
	}

	/**
	 * Remove rows not touched by a completed full rebuild.
	 *
	 * @param string $rebuild_token Rebuild token.
	 * @return int
	 */
	public function delete_rows_not_in_rebuild($rebuild_token) {
		if ($rebuild_token === '') {
			return 0;
		}

		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->table_name}
				WHERE object_type IN ('post', 'pdf')
					AND rebuild_token <> %s",
				$rebuild_token
			)
		);

		if (false !== $result) {
			$this->get_spelling_repository()->prune_missing_objects();
			$this->get_people_name_repository()->prune_missing_objects();
		}

		return false === $result ? 0 : (int) $result;
	}

	/**
	 * Synchronize spelling terms after an index row changes.
	 *
	 * @param array $row Stored index row.
	 * @return void
	 */
	private function sync_spelling_dictionary(array $row) {
		$this->get_spelling_repository()->sync_object(
			(int) $row['object_id'],
			(string) $row['object_type'],
			(string) $row['title'],
			(string) $row['acf_keywords']
		);
	}

	/**
	 * Synchronize the dedicated People-name dictionary after an index update.
	 *
	 * @param array $row Stored index row.
	 * @return void
	 */
	private function sync_people_name_dictionary(array $row) {
		$this->get_people_name_repository()->sync_object($row);
	}

	/**
	 * Return the spelling repository lazily to keep repository construction cheap.
	 *
	 * @return VFWP_Intranet_Search_Spelling_Repository
	 */
	private function get_spelling_repository() {
		if (!$this->spelling_repository instanceof VFWP_Intranet_Search_Spelling_Repository) {
			$this->spelling_repository = new VFWP_Intranet_Search_Spelling_Repository($this->wpdb);
		}

		return $this->spelling_repository;
	}

	/**
	 * Return the People-name repository lazily.
	 *
	 * @return VFWP_Intranet_Search_People_Name_Repository
	 */
	private function get_people_name_repository() {
		if (!$this->people_name_repository instanceof VFWP_Intranet_Search_People_Name_Repository) {
			$this->people_name_repository = new VFWP_Intranet_Search_People_Name_Repository($this->wpdb);
		}

		return $this->people_name_repository;
	}

	/**
	 * Return high-level index counts.
	 *
	 * @return array
	 */
	public function get_counts() {
		$rows = $this->wpdb->get_results(
			"SELECT object_type, COUNT(*) AS item_count
			FROM {$this->table_name}
			GROUP BY object_type",
			ARRAY_A
		);
		$counts = array(
			'total' => 0,
			'web'   => 0,
			'pdf'   => 0,
		);

		if (is_array($rows)) {
			foreach ($rows as $row) {
				$count = (int) $row['item_count'];
				$counts['total'] += $count;

				if ($row['object_type'] === 'post') {
					$counts['web'] = $count;
				}

				if ($row['object_type'] === 'pdf') {
					$counts['pdf'] = $count;
				}
			}
		}

		return $counts;
	}

	/**
	 * Count PDF rows with extraction issues.
	 *
	 * @return int
	 */
	public function count_pdf_extraction_issues() {
		return (int) $this->wpdb->get_var(
			"SELECT COUNT(*) FROM {$this->table_name}
			WHERE (object_type = 'pdf' OR (object_type = 'post' AND post_type = 'documents'))
				AND extraction_status NOT IN ('', 'success', 'success_truncated')"
		);
	}

	/**
	 * Return recent PDF extraction issues for administrators.
	 *
	 * @param int $limit Maximum rows.
	 * @param int $offset Row offset.
	 * @return array
	 */
	public function get_pdf_extraction_issues($limit = 20, $offset = 0) {
		$limit = min(100, max(1, (int) $limit));
		$offset = max(0, (int) $offset);
		$rows = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT object_id, title, file_name, extraction_status, extraction_error, indexed_at
				FROM {$this->table_name}
				WHERE (object_type = 'pdf' OR (object_type = 'post' AND post_type = 'documents'))
					AND extraction_status NOT IN ('', 'success', 'success_truncated')
				ORDER BY indexed_at DESC, object_id DESC
				LIMIT %d OFFSET %d",
				$limit,
				$offset
			),
			ARRAY_A
		);

		return is_array($rows) ? $rows : array();
	}

	/**
	 * Clear stored PDF extraction issue statuses without deleting indexed content.
	 *
	 * @return int Number of rows updated.
	 */
	public function clear_pdf_extraction_issues() {
		$result = $this->wpdb->query(
			"UPDATE {$this->table_name}
			SET extraction_status = '', extraction_error = ''
			WHERE (object_type = 'pdf' OR (object_type = 'post' AND post_type = 'documents'))
				AND extraction_status NOT IN ('', 'success', 'success_truncated')"
		);

		return false === $result ? 0 : (int) $result;
	}
}
