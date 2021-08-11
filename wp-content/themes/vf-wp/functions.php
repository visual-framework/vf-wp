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
require_once('blocks/vfwp-embl-events/index.php');
require_once('blocks/vfwp-post-fetch/index.php');

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
echo '<div style="background-color: #fffadc; padding: 3px;"><p>To learn more about WordPress, blocks, page templates, customization and more, check out our <a href="https://stable.visual-framework.dev/wordpress/">training materials</a>.</p></div>';
}

add_filter(
  'acf/settings/load_json',
  'vf_wp_theme__acf_settings_load_json',
  1
);

// Load ACF JSON from theme
 
function vf_wp_theme__acf_settings_load_json($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
}

//Adds vf class to links in category list
function add_class_to_category( $thelist, $separator, $parents){
  $class_to_add = 'vf-link';
  return str_replace('<a href="',  '<a class="'. $class_to_add. '" href="', $thelist);
}

add_filter('the_category', __NAMESPACE__ . '\\add_class_to_category',10,3);

// Search filter
function search_filter($query) {
	if(!is_admin()) {
		if($query->is_main_query() && $query->is_search()) {
			// Check if $_GET['post_type'] is set
			if(isset($_GET['post_type']) && $_GET['post_type'] != 'all') {
				// Filter it just to be safe
				$post_type = sanitize_text_field($_GET['post_type']);
				// Set the post type
				$query->set('post_type', $post_type);
      }
      else {
        $query->set('post_type', array('post', 'page'));
      }
		}
	}
	return $query;
}

add_filter('pre_get_posts', 'search_filter');

// removes the margin added by vf-stack to the body element
function remove_margin_wp_toolbar() {
  echo '
  <style type="text/css">
  #wpadminbar {
  margin-top: 0px !important;
  }
  </style>';
}
add_action( 'admin_head', 'remove_margin_wp_toolbar' );
add_action( 'wp_head', 'remove_margin_wp_toolbar' );

// allow only certain WP Gutenberg blocks
add_filter( 'allowed_block_types', 'allowed_block_types' );
 
function allowed_block_types( $allowed_blocks ) {
 
	return array(
		'core/image',
		'core/paragraph',
		'core/heading',
		'core/list',
    'core/audio',
    'core/video',
    'core/table',
    'core/html',
    'core/preformatted',
    'core/pullquote',
    'vf/tabs',
    'vf/tabs-section',
    'vf/grid',
    'vf/embl-grid',
    'vf/grid-column',
    'vf/breadcrumbs',
    'vf/blockquote',
    'acf/vf-container-embl-global-header',
    'acf/vf-container-global-header',
    'acf/vf-container-navigation',
    'acf/vf-container-breadcrumbs',
    'acf/vf-container-wp-groups-header',
    'acf/vf-container-page-template',
    'acf/vf-container-ebi-global-footer',
    'acf/vf-container-global-footer',
    'acf/vf-container-wp-hero-group',
    'acf/vf-container-banner',
    'acf/vf-container-embl-news',
    'acf/vf-container-wp-breadcrumbs-intranet ',
    'acf/vfwp-latest-posts',
    'acf/vfwp-summary',
    'acf/vfwp-card',
    'acf/vfwp-links-list',
    'acf/vfwp-box',
    'acf/vfwp-intro',
    'acf/vfwp-activity-list',
    'acf/vfwp-page-header',
    'acf/vfwp-section-header',
    'acf/vfwp-figure',
    'acf/vfwp-embed',
    'acf/vfwp-divider',
    'acf/vfwp-tabs',
    'acf/vfwp-banner',
    'acf/vfwp-hero',
    'acf/vfwp-profile',
    'acf/vfwp-details',
    'acf/vfwp-search',
    'acf/vfwp-social-icons',
    'acf/vfwp-lede',
    'acf/vfwp-button',
    'acf/vfwp-badge',
    'acf/vfwp-bg-container',
    'acf/vfwp-embl-events',
    'acf/vfwp-post-fetch',
    'acf/vf-data-resources',
    'acf/vfwp-embl-events',
    'acf/vf-members',
    'acf/vf-person',
    'acf/vf-events-list',
    'acf/vf-group-header',
    'acf/vfwp-hero',
    'acf/vf-jobs',
    'acf/vfwp-latest-posts',
    'acf/vf-publications',
    'acf/vf-publications-group-ebi',
    'acf/vf-embl-news',
    
	);
 
}



/* custom language switcher for the WPML plugin
function languages_links_switcher(){
$languages = icl_get_languages('skip_missing=1');
if(1 < count($languages)){ echo __(' <div class="vf-banner vf-banner--alert vf-banner--info">
  <div class="vf-banner__content">
    <style>
      .vf-banner__content p {
        font-size: 16px !important;
        margin: 0px !important;
      }
    </style>
    <p class="vf-banner__text">This article is also available in ');

      foreach($languages as $l){
      if(!$l['active']) $langs[] = '<a href="'.$l['url'].'">'.$l['translated_name'].'</a>';
      }
      echo join(' and ', array_filter(array_merge(array(join(', ', array_slice($langs, 0, -1))), array_slice($langs,
      -1)), 'strlen'));

      echo __('
    </p>
  </div>
  </div>' );



  }
  }
  */
?>
