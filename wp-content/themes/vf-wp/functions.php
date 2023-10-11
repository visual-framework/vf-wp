<?php
function enqueue_custom_autocomplete_script() {
  wp_enqueue_script('custom-autocomplete', get_template_directory_uri() . '/assets/scripts/custom-autocomplete.js', array('jquery'), '1.0', true);

  // Define the AJAX URL for your script
  wp_localize_script('custom-autocomplete', 'ajax_object', array(
      'ajax_url' => admin_url('admin-ajax.php'),
  ));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_autocomplete_script');
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
require_once('blocks/vfwp-blockquote/index.php');
require_once('blocks/vfwp-navigation-on-this-page/index.php');

/*deprecated
require_once('blocks/vfwp-masthead/index.php');
*/

//evaluate if xml-rpc is required and disable or restrict the access
add_filter('xmlrpc_enabled', '__return_false');


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

function swiftype_metadata_description() {
  $text = '';
  // If text is empty use the Content Hub description
  if (class_exists('VF_Cache')) {
    // Get the global taxonomy term
    $term_id = get_field('embl_taxonomy_term_what', 'option');
    $term_uuid = embl_taxonomy_get_uuid($term_id);
    // Query Content Hub via cache
    if ($term_uuid) {
      $url = VF_Cache::get_api_url();
      $url .= '/pattern.html';
      $url = add_query_arg(array(
        'filter-uuid'         => $term_uuid,
        'filter-content-type' => 'profiles',
        'pattern'             => 'node-teaser',
        'source'              => 'contenthub',
      ), $url);
      $text = VF_Cache::fetch($url);
      $text = strstr($text, '<p>',);
      $text = strstr($text, '</p>', true);
      $text = str_replace('<p>','', $text);
    } }
  return $text;
}


// allow only certain WP Gutenberg blocks
add_filter( 'allowed_block_types_all', 'allowed_block_types' );

function allowed_block_types($allowed_blocks) {

	return array(
    'core/block',
		'core/image',
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
    'core/audio',
    'core/video',
    'core/table',
    'core/html',
    'core/spacer',
    'core/search',
    'core/shortcode',
    'core/embed',
    'core/preformatted',
    'core-embed/twitter',
    'vf/tabs',
    'vf/tabs-section',
    'vf/grid',
    'vf/embl-grid',
    'vf/grid-column',
    'vf/breadcrumbs',
    'acf/vf-container-embl-global-header',
    'acf/vf-container-global-header',
    'acf/vf-container-navigation',
    'acf/vf-container-breadcrumbs',
    'acf/vf-container-wp-groups-header',
    'acf/vf-container-page-template',
    'acf/vf-container-ebi-global-footer',
    'acf/vf-container-ebi-global-header',
    'acf/vf-container-global-footer',
    'acf/vf-container-wp-hero-group',
    'acf/vf-container-wp-hero-secondary',
    'acf/vf-container-banner',
    'acf/vf-container-embl-news',
    'acf/vf-container-wp-breadcrumbs-intranet',
    'acf/vf-container-wp-hero-blog',
    'acf/vf-container-wp-hero-secondary',
    'acf/vf-members',
    'acf/vf-members-internal',
    'acf/vf-person',
    'acf/vf-person-internal',
    'acf/vf-events-list',
    'acf/vf-group-header',
    'acf/vf-jobs',
    'acf/vf-publications',
    'acf/vf-publications-group-ebi',
    'acf/vf-related-courses',
    'acf/vf-tree',
    'acf/vf-embl-news',
    'acf/vfwp-latest-posts',
    'acf/vfwp-summary',
    'acf/vfwp-card',
    'acf/vfwp-links-list',
    'acf/vfwp-box',
    'acf/vfwp-intro',
    'acf/vfwp-section-header',
    'acf/vfwp-figure',
    'acf/vfwp-embed',
    'acf/vfwp-divider',
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
    'acf/vfwp-blockquote',
    'acf/vfwp-navigation-on-this-page',
    'acf/vf-data-resources',
    'acf/vfwp-embl-events',
    'acf/vf-team-profile',

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



/**
 * Function to write logs using debug flag of wordpress, it will log messages in
 * debug.log file.
 *
 * usage: write_log("your message here");
 */
if (!function_exists('write_log')) {
  function write_log($log) {
    if (true === WP_DEBUG) {
      if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
      } else {
        error_log($log);
      }
    }
  }
}

/**
 * Setup cron to schedule check on stale api cache request and process them as
 * per the rate limit defined for the cache in vf-cache plugin.
 *
 * Multiple issues reported in Gitlab & Snow
 * - https://embl.service-now.com/nav_to.do?uri=sc_task.do?sys_id=c381121e1bce9510b7405fc4464bcb1a
 * - https://gitlab.ebi.ac.uk/emblorg/backlog/-/issues/724
 * - https://github.com/visual-framework/vf-wp/issues/975
 *
 * Cron documentation
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
 */
add_filter( 'cron_schedules', 'vfwp_add_cron_interval' );
function vfwp_add_cron_interval( $schedules ) {
  $schedules['every_five_minutes'] = array(
    'interval'  => 60 * 5,
    'display'   => __( 'Every 5 Minutes', 'textdomain' )
  );
  return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'vfwp_add_every_five_minutes' ) ) {
  wp_schedule_event( time(), 'every_five_minutes', 'vfwp_add_every_five_minutes' );
}

// Hook into that action that'll fire every five minutes
// Call the $vf_cache class object to clear the cache of expired cache tokens
add_action( 'vfwp_add_every_five_minutes', 'vf_every_five_minutes_event_cache_func' );
function vf_every_five_minutes_event_cache_func() {
  global $vf_cache;
  // Check if valid vf_Cache object
  if ($vf_cache instanceof VF_Cache) {
    // Invoke function to process the stale cache from vf-cache class
    $vf_cache->process_api_cache_cron();
  }
  else {
    // log error for debug
    write_log("Failed to process 'vf_every_five_minutes_event_cache_func' cron for clearing api cache");
  }
}


// function remove_blocks_type($allowed_blocks){
//   unset($allowed_blocks['core/pullquote']);
//   return $allowed_blocks;
// }
// add_filter('allowed_block_types_all', 'remove_blocks_type');
add_action('wp_ajax_custom_autocomplete', 'custom_autocomplete');
add_action('wp_ajax_nopriv_custom_autocomplete', 'custom_autocomplete');

function custom_autocomplete() {
    $term = sanitize_text_field($_GET['term']);
    $results = array();

    $args = array(
        's' => $term,
        'post_status' => 'publish',
        'post_type' => array('page', 'training', 'people', 'documents', 'insites', 'community-blog', 'events'), // Adjust the post type as needed
        'posts_per_page' => 5, // Retrieve all matching posts
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = get_the_title();
        }
    }

    wp_reset_postdata();

    wp_send_json($results);
}

?>
