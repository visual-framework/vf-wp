<?php
/**
 * Search subsystem bootstrap for the vf-wp-intranet theme.
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once get_stylesheet_directory() . '/functions/search/class-search-schema.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-normalizer.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-settings.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-index-repository.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-pdf-extractor.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-indexer.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-query-parser.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-snippet-service.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-service.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-frontend.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-index-manager.php';
require_once get_stylesheet_directory() . '/functions/search/class-search-cli-command.php';

add_action('after_switch_theme', array('VFWP_Intranet_Search_Schema', 'install'));
add_action('init', array('VFWP_Intranet_Search_Schema', 'maybe_install'), 5);
VFWP_Intranet_Search_Frontend::register_hooks();

function vfwp_intranet_search_bootstrap() {
	global $vfwp_intranet_search_indexer;

	if ($vfwp_intranet_search_indexer instanceof VFWP_Intranet_Search_Indexer) {
		return $vfwp_intranet_search_indexer;
	}

	$normalizer = new VFWP_Intranet_Search_Normalizer();
	$repository = new VFWP_Intranet_Search_Index_Repository();
	$vfwp_intranet_search_indexer = new VFWP_Intranet_Search_Indexer($repository, $normalizer, new VFWP_Intranet_Search_PDF_Extractor());
	$vfwp_intranet_search_indexer->register_hooks();

	return $vfwp_intranet_search_indexer;
}
add_action('init', 'vfwp_intranet_search_bootstrap', 10);

$vfwp_intranet_search_index_manager = new VFWP_Intranet_Search_Index_Manager();
$vfwp_intranet_search_index_manager->register_hooks();

if (is_admin()) {
	$vfwp_intranet_search_settings = new VFWP_Intranet_Search_Settings();
	$vfwp_intranet_search_settings->register_hooks();
}

if (defined('WP_CLI') && WP_CLI) {
	WP_CLI::add_command('theme-search index', 'VFWP_Intranet_Search_CLI_Command');
}

/**
 * Index a single post through the theme search subsystem.
 *
 * @param int  $post_id Post ID.
 * @param bool $force Force rebuilding the row.
 * @return string
 */
function vfwp_intranet_search_index_post($post_id, $force = false, $rebuild_token = '') {
	if (!is_numeric($post_id)) {
		return 'ignored';
	}

	$indexer = vfwp_intranet_search_bootstrap();

	return $indexer->index_post((int) $post_id, (bool) $force, (string) $rebuild_token);
}

/**
 * Index a single PDF attachment through the theme search subsystem.
 *
 * @param int  $attachment_id Attachment ID.
 * @param bool $force Force extraction/indexing.
 * @return string
 */
function vfwp_intranet_search_index_pdf($attachment_id, $force = false, $rebuild_token = '') {
	if (!is_numeric($attachment_id)) {
		return 'ignored';
	}

	$indexer = vfwp_intranet_search_bootstrap();
	$indexer->reindex_documents_for_attachment((int) $attachment_id, (bool) $force);

	return 'ignored';
}

/**
 * Query the theme search index without touching frontend templates.
 *
 * @param mixed $query Raw query.
 * @param array $filters Filters.
 * @param int   $page Page number.
 * @param int   $per_page Results per page.
 * @return array
 */
function vfwp_intranet_search($query, array $filters = array(), $page = 1, $per_page = 10) {
	$service = new VFWP_Intranet_Search_Service();

	return $service->search($query, $filters, $page, $per_page);
}

/**
 * Query the theme search index for the current frontend search request.
 *
 * @return array
 */
function vfwp_intranet_search_frontend_current_request() {
	return VFWP_Intranet_Search_Frontend::search_current_request();
}
