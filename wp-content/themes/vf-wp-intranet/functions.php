<?php
/* Adds scripts */

// include custom jQuery
function include_jquery() {
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'include_jquery');

//inlcude jplist
function add_scripts() {
  wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );


require_once('functions/custom-taxonomies.php');
require_once('functions/cpt-register.php');
require_once('functions/infoboard-news.php');
require_once('functions/people.php');
require_once('functions/relevanssi.php');
require_once('functions/training-ebi-feed.php');
require_once('functions/bioit-feed.php');
require_once('functions/training.php');
require_once('functions/training-on-demand-feed.php');


// CHILD THEME CSS FILE

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

	$parent_style = 'parent-style';

  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style',
	get_stylesheet_directory_uri() . '/style.css',
	array( $parent_style ),
	wp_get_theme()->get('Version')
);
}

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

/**
 * Load ACF JSON from theme
 */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }


// add tag support to pages
function tags_support_all() {
	register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
	if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');

// count all the publishes documents
function get_all_documents_posts() {
	$count_posts = wp_count_posts('documents');
	$published_posts = $count_posts->publish;
	return $published_posts;
  }



// Removes comments from admin menu
add_action( 'admin_menu', 'remove_comments_menu_page' );
function remove_comments_menu_page() {
    remove_menu_page('edit-comments.php' );
}


/*
 * Redirect pages to external links
 */

add_action( 'template_redirect', 'redirect_externally' );
function redirect_externally(){
    $redirect = get_post_meta( get_the_ID(), 'vf_wp_intranet_redirect', true );
    if (is_page() || is_singular('teams')) {
    if( $redirect ){
        wp_redirect( $redirect );
    } }
}


// enables excerpt field for pages
add_post_type_support( 'page', 'excerpt' );



/* 
 * Do not log VF CACHE update activity in Simple Histroy plugin
 */

add_filter('simple_history/log/do_log', function ($do_log = null, $level = null, $message = null, $context = null, $logger = null) {

  $post_types_to_not_log = array(
      'vf_cache',
  );

  if (( isset($logger->slug) && ($logger->slug === 'SimplePostLogger' || $logger->slug === 'SimpleMediaLogger') ) && ( isset($context['post_type']) && in_array($context['post_type'], $post_types_to_not_log) )) {
      $do_log = false;
  }

  return $do_log;
}, 10, 5);



//Add featured image to WP REST API

add_action( 'rest_api_init', 'add_post_thumbnail_to_JSON' );

function add_post_thumbnail_to_JSON() {
    register_rest_field( 
        array ('insites', 'community-blog', 'events'), 
        'featured_image_src', 
        array(
            'get_callback'    => 'thumbnail_src',
            'update_callback' => null,
            'schema'          => null,
         )
    );

}

function thumbnail_src( $object, $field_name, $request ) {
  $feat_img_array = wp_get_attachment_image_src(
    $object['featured_media'], // Image attachment ID
    'full',  // Size.  Ex. "thumbnail", "large", "full", etc..
    true // Whether the image should be treated as an icon.
  );
  return $feat_img_array[0];
}


// Register custom REST API endpoint
add_action('rest_api_init', function () {
  register_rest_route('custom/v1', '/community-blog', array(
      'methods' => 'GET',
      'callback' => 'get_community_blog_posts',
      'permission_callback' => '__return_true',
  ));
});

// Callback function to handle the custom endpoint
function get_community_blog_posts($request) {
  // Get the value of cb_featured, site, and per_page parameters from the request
  $cb_featured = $request->get_param('cb_featured');
  $site = $request->get_param('site');
  $per_page = $request->get_param('per_page');

  // Default number of posts per page if 'per_page' parameter is not provided or invalid
  $posts_per_page = !empty($per_page) && is_numeric($per_page) && $per_page > 0 ? intval($per_page) : -1;

  // Query arguments to retrieve posts from community-blog post type
  $args = array(
      'post_type' => 'community-blog',
      'posts_per_page' => $posts_per_page,
      'meta_key'		=> 'cb_featured',
      'meta_value'	=> $cb_featured,
      'orderby' => 'date', // Sort by date
      'order'   => 'DESC', // Show latest item first
      // Taxonomy query to filter posts by 'location' taxonomy
      'tax_query' => array(
          array(
              'taxonomy' => 'embl-location',
              'field' => 'slug',
              'terms' => $site,
          ),
      ),
  );

  // Perform the query
  $query = new WP_Query($args);

  // Check if there are posts found
  if ($query->have_posts()) {
      // Create an array to store posts data
      $posts_data = array();

      // Loop through each post
      while ($query->have_posts()) {
          $query->the_post();

          // Get post data
          $post_data = array(
              'id' => get_the_ID(),
              'title' => get_the_title(),
              'date' => get_the_time('Y-m-d\TH:i:s'), // Exact date and time format
              'excerpt' => get_the_excerpt(), // Excerpt
              'featured_image_src' => get_the_post_thumbnail_url(get_the_ID(), 'full'), // Featured image source URL
              // Add more fields as needed
          );

          // Add post data to the array
          $posts_data[] = $post_data;
      }

      // Reset post data
      wp_reset_postdata();

      // Return the posts data as JSON response
      return rest_ensure_response($posts_data);
  } else {
      // If no posts found, return empty array
      return rest_ensure_response(array());
  }
}


function update_sticky_status_based_on_field() {
  // Ensure this runs only in the admin dashboard to avoid performance issues on the front end
  if (!is_admin()) return;

  // Get all posts marked as 'red'
  $args_red = array(
      'posts_per_page' => -1, 
      'post_type' => 'community-blog', 
      'meta_query' => array(
          array(
              'key' => 'cb_emergency_notification',
              'value' => 'red',
              'compare' => '='
          )
      )
  );
  $query_red = new WP_Query($args_red);

  // Get currently sticky posts
  $sticky_posts = get_option('sticky_posts', []);

  // Array to hold posts that should be sticky
  $new_sticky_posts = [];

  // Loop through posts marked as 'red'
  if ($query_red->have_posts()) {
      while ($query_red->have_posts()) {
          $query_red->the_post();
          $post_id = get_the_ID();

          // Add to new sticky posts array
          $new_sticky_posts[] = $post_id;

          // Add to sticky posts if not already sticky
          if (!in_array($post_id, $sticky_posts)) {
              $sticky_posts[] = $post_id;
          }
      }
  }

  // Loop through current sticky posts and remove those no longer 'red'
  foreach ($sticky_posts as $key => $sticky_post_id) {
      if (!in_array($sticky_post_id, $new_sticky_posts)) {
          unset($sticky_posts[$key]);
      }
  }

  // Update the sticky posts
  update_option('sticky_posts', array_values($sticky_posts));
  wp_reset_postdata();
}

add_action('wp_loaded', 'update_sticky_status_based_on_field');

