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

			return false === $result ? 'failed' : 'updated';
		}

		$result = $this->wpdb->insert($this->table_name, $row, $formats);

		return false === $result ? 'failed' : 'inserted';
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
		return false !== $this->wpdb->query("TRUNCATE TABLE {$this->table_name}");
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

		return false === $result ? 0 : (int) $result;
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
			WHERE object_type = 'pdf'
				AND extraction_status NOT IN ('', 'success', 'success_truncated')"
		);
	}

	/**
	 * Return recent PDF extraction issues for administrators.
	 *
	 * @param int $limit Maximum rows.
	 * @return array
	 */
	public function get_pdf_extraction_issues($limit = 5) {
		$limit = min(20, max(1, (int) $limit));
		$rows = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT object_id, title, file_name, extraction_status, extraction_error, indexed_at
				FROM {$this->table_name}
				WHERE object_type = 'pdf'
					AND extraction_status NOT IN ('', 'success', 'success_truncated')
				ORDER BY indexed_at DESC, object_id DESC
				LIMIT %d",
				$limit
			),
			ARRAY_A
		);

		return is_array($rows) ? $rows : array();
	}
}
