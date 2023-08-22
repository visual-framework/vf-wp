<?php
/* Adds scripts */

// include custom jQuery
function include_jquery() {
	wp_deregister_script('jquery');
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
require_once('functions/training.php');


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

add_action( 'rest_api_init', 'add_thumbnail_to_JSON' );

function add_thumbnail_to_JSON() {
register_rest_field( 
    array ('insites', 'community-blog', 'events'), 
    'featured_image_src', 
    array(
        'get_callback'    => 'get_image_src',
        'update_callback' => null,
        'schema'          => null,
         )
    );
}

function get_image_src( $object, $field_name, $request ) {
  $feat_img_array = wp_get_attachment_image_src(
    $object['featured_media'], // Image attachment ID
    'full',  // Size.  Ex. "thumbnail", "large", "full", etc..
    true // Whether the image should be treated as an icon.
  );
  return $feat_img_array[0];
}


?>
