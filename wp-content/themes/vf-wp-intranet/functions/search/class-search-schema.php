<?php
/**
 * Database schema management for the theme search index.
 */

if (!defined('ABSPATH')) {
	exit;
}

class VFWP_Intranet_Search_Schema {
	const VERSION = 9;
	const OPTION_NAME = 'vfwp_intranet_search_schema_version';

	/**
	 * Return the fully-qualified search index table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;

		return $wpdb->prefix . 'vf_search_index';
	}

	/**
	 * Return the fully-qualified search analytics table name.
	 *
	 * @return string
	 */
	public static function analytics_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'vf_search_analytics';
	}

	/**
	 * Install or upgrade the schema only when the stored version is stale.
	 *
	 * @return bool
	 */
	public static function maybe_install() {
		$installed_version = (int) get_option(self::OPTION_NAME, 0);

		if ($installed_version >= self::VERSION) {
			return false;
		}

		self::install();

		if ($installed_version > 0 && self::schema_change_requires_rebuild($installed_version) && class_exists('VFWP_Intranet_Search_Settings')) {
			VFWP_Intranet_Search_Settings::mark_rebuild_required(__('Search index schema changed.', 'vfwp'));
		}

		return true;
	}

	/**
	 * Create or update the search index table.
	 *
	 * @return void
	 */
	public static function install() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = self::table_name();
		$analytics_table_name = self::analytics_table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			object_id bigint(20) unsigned NOT NULL,
			object_type varchar(32) NOT NULL DEFAULT 'post',
			post_type varchar(64) NOT NULL,
			post_status varchar(20) NOT NULL,
			visibility varchar(20) NOT NULL DEFAULT 'public',
			title text NOT NULL,
			excerpt text NOT NULL,
			content longtext NOT NULL,
			acf_keywords longtext NOT NULL,
			url text NOT NULL,
			published_at datetime DEFAULT NULL,
			updated_at datetime DEFAULT NULL,
			indexed_at datetime NOT NULL,
			schema_version int(11) unsigned NOT NULL,
			content_hash char(64) NOT NULL,
			source_hash char(64) NOT NULL DEFAULT '',
			parent_object_id bigint(20) unsigned NOT NULL DEFAULT 0,
			file_name varchar(255) NOT NULL DEFAULT '',
			extraction_status varchar(32) NOT NULL DEFAULT '',
			extraction_error text NOT NULL,
			rebuild_token varchar(64) NOT NULL DEFAULT '',
			PRIMARY KEY  (id),
			UNIQUE KEY object_lookup (object_type,object_id),
			KEY post_type_status (post_type,post_status),
			KEY visibility_status (visibility,post_status),
			KEY parent_object_id (parent_object_id),
			KEY published_at (published_at),
			KEY updated_at (updated_at),
			KEY indexed_at (indexed_at),
			KEY content_hash (content_hash),
			KEY source_hash (source_hash),
			KEY extraction_status (extraction_status),
			KEY rebuild_token (rebuild_token),
			FULLTEXT KEY title_fulltext (title),
			FULLTEXT KEY excerpt_fulltext (excerpt),
			FULLTEXT KEY content_fulltext (content),
			FULLTEXT KEY acf_keywords_fulltext (acf_keywords),
			FULLTEXT KEY combined_fulltext (title,excerpt,content,acf_keywords)
		) {$charset_collate};";

		dbDelta($sql);

		$analytics_sql = "CREATE TABLE {$analytics_table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			query_text varchar(240) NOT NULL DEFAULT '',
			normalized_query varchar(191) NOT NULL DEFAULT '',
			result_count bigint(20) unsigned NOT NULL DEFAULT 0,
			filters_hash char(32) NOT NULL DEFAULT '',
			filters_json longtext NOT NULL,
			page_number int(11) unsigned NOT NULL DEFAULT 1,
			per_page int(11) unsigned NOT NULL DEFAULT 10,
			searched_at datetime NOT NULL,
			user_email varchar(191) NOT NULL DEFAULT '',
			source varchar(32) NOT NULL DEFAULT 'frontend',
			PRIMARY KEY  (id),
			KEY searched_at (searched_at),
			KEY normalized_query (normalized_query),
			KEY result_count (result_count),
			KEY filters_hash (filters_hash),
			KEY user_email (user_email),
			KEY source (source)
		) {$charset_collate};";

		dbDelta($analytics_sql);
		self::ensure_fulltext_indexes();

		update_option(self::OPTION_NAME, self::VERSION, false);
	}

	/**
	 * Determine if upgrading from a stored version requires rebuilding indexed content.
	 *
	 * @param int $installed_version Installed schema version.
	 * @return bool
	 */
	private static function schema_change_requires_rebuild($installed_version) {
		return (int) $installed_version > 0 && (int) $installed_version < 7;
	}

	/**
	 * dbDelta can be conservative about FULLTEXT indexes, so verify them explicitly.
	 *
	 * @return void
	 */
	private static function ensure_fulltext_indexes() {
		global $wpdb;

		$table_name = self::table_name();
		$required_indexes = array(
			'title_fulltext'        => array('title'),
			'excerpt_fulltext'      => array('excerpt'),
			'content_fulltext'      => array('content'),
			'acf_keywords_fulltext' => array('acf_keywords'),
			'combined_fulltext'     => array('title', 'excerpt', 'content', 'acf_keywords'),
		);

		$index_rows = $wpdb->get_results("SHOW INDEX FROM `{$table_name}` WHERE Index_type = 'FULLTEXT'", ARRAY_A);
		$existing_indexes = array();

		if (is_array($index_rows)) {
			foreach ($index_rows as $index_row) {
				$index_name = isset($index_row['Key_name']) ? $index_row['Key_name'] : '';
				$column_name = isset($index_row['Column_name']) ? $index_row['Column_name'] : '';
				$sequence = isset($index_row['Seq_in_index']) ? (int) $index_row['Seq_in_index'] : 0;

				if ($index_name === '' || $column_name === '' || $sequence <= 0) {
					continue;
				}

				$existing_indexes[$index_name][$sequence] = $column_name;
			}
		}

		foreach ($required_indexes as $index_name => $columns) {
			$existing_columns = isset($existing_indexes[$index_name]) ? $existing_indexes[$index_name] : array();
			ksort($existing_columns);
			$existing_columns = array_values($existing_columns);

			if ($existing_columns === $columns) {
				continue;
			}

			if (!empty($existing_columns)) {
				$wpdb->query("ALTER TABLE `{$table_name}` DROP INDEX `{$index_name}`");
			}

			$column_sql = implode(',', array_map(array(__CLASS__, 'quote_identifier'), $columns));
			$wpdb->query("ALTER TABLE `{$table_name}` ADD FULLTEXT KEY `{$index_name}` ({$column_sql})");
		}
	}

	/**
	 * Quote a known SQL identifier.
	 *
	 * @param string $identifier Identifier.
	 * @return string
	 */
	private static function quote_identifier($identifier) {
		return '`' . str_replace('`', '``', $identifier) . '`';
	}
}
