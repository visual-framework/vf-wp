<?php

if( ! defined( 'ABSPATH' ) ) exit;

require_once('functions/walker-comment.php');

require_once('functions/admin.php');
require_once('functions/theme.php');

// require_once('functions/post-duplicate.php');

// Require Gutenberg block classes

require_once('functions/theme-block.php');
require_once('blocks/vfwp-latest-posts/index.php');
require_once('blocks/vfwp-summary/index.php');
require_once('blocks/vfwp-card/index.php');
require_once('blocks/vfwp-links-list/index.php');
require_once('blocks/vfwp-box/index.php');
require_once('blocks/vfwp-intro/index.php');
require_once('blocks/vfwp-activity-list/index.php');
require_once('blocks/vfwp-page-header/index.php');
require_once('blocks/vfwp-section-header/index.php');
require_once('blocks/vfwp-figure/index.php');
require_once('blocks/vfwp-embed/index.php');
require_once('blocks/vfwp-divider/index.php');
require_once('blocks/vfwp-tabs/index.php');
require_once('blocks/vfwp-banner/index.php');
require_once('blocks/vfwp-hero/index.php');
require_once('blocks/vfwp-profile/index.php');
require_once('blocks/vfwp-details/index.php');
require_once('blocks/vfwp-search/index.php');
require_once('blocks/vfwp-social-icons/index.php');
require_once('blocks/vfwp-lede/index.php');
require_once('blocks/vfwp-button/index.php');
require_once('blocks/vfwp-badge/index.php');
require_once('blocks/vfwp-bg-container/index.php');

/*deprecated
require_once('blocks/vfwp-masthead/index.php');
*/

if (vf_debug()) {
  require_once('blocks/vfwp-debug/index.php');
}

global $vf_admin;
if ( ! isset($vf_admin)) {
  $vf_admin = new VF_Admin();
}

global $vf_theme;
if ( ! isset($vf_theme)) {
  $vf_theme = new VF_Theme();
}

// adds contact box to WP admin dashboard
add_action('wp_dashboard_setup', 'contact_box');

function contact_box() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Share your feedback', 'contact_box_content');
}

function contact_box_content() {
echo '<div style="background-color: #fffadc; padding: 3px;"><p>If you have any questions, comments or suggestions please send your feedback to <a href="mailto:digital-comms@embl.org">digital-comms@embl.org</a>.</p></div>';
}

// adds training box to WP admin dashboard
add_action('wp_dashboard_setup', 'training_box');

function training_box() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_training_widget', 'WordPress Training Materials', 'training_box_content');
}

function training_box_content() {
echo '<div style="background-color: #fffadc; padding: 3px;"><p>To learn more about WordPress, blocks, page templates, customization and more, check out our <a href="https://wwwdev.embl.org/guidelines/design/page/wordpress/">training materials</a>.</p></div>';
}

add_filter(
  'acf/settings/load_json',
  'vf_wp_theme__acf_settings_load_json',
  1
);

/**
 * Load ACF JSON from theme
 */
function vf_wp_theme__acf_settings_load_json($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
}


?>
