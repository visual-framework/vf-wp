<?php
/* Adds scripts */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
    wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}


require_once('functions/custom-taxonomies.php');
require_once('functions/cpt-register.php');
require_once('functions/infoboard-news.php');
require_once('functions/people.php');
require_once('functions/relevanssi.php');


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

//ADDING CLASS TO A LINK IN CATEGORY
//
// function add_class_to_category( $thelist, $separator, $parents){
//   $class_to_add = 'vf-link';
//   return str_replace('<a href="',  '<a class="'. $class_to_add. '" href="', $thelist);
// }

// add_filter('the_category', __NAMESPACE__ . '\\add_class_to_category',10,3);

// adds support for feature images

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

/**
 * Load ACF JSON from theme
 */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }

// Search filter
function intranet_search_filter($query) {
	if(!is_admin()) {
		if($query->is_main_query() && $query->is_search()) {
			// Check if $_GET['post_type'] is set
			if(isset($_GET['post_type']) && $_GET['post_type'] != 'any') {
				// Filter it just to be safe
				$post_type = sanitize_text_field($_GET['post_type']);
				// Set the post type
				$query->set('post_type', array('page', 'documents', 'insites', 'events', 'teams'));
      }
      else {
        $query->set('post_type', array('page', 'documents', 'insites', 'events'));
      }
		}
	}
	return $query;
}

add_filter('pre_get_posts', 'intranet_search_filter');


add_action('pre_get_posts', 'remove_my_cpt_from_search_results');

function remove_my_cpt_from_search_results($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return $query;
    }

    // can exclude multiple post types, for ex. array('staticcontent', 'cpt2', 'cpt3')
    $post_types_to_exclude = array('people');

    if ($query->get('post_type')) {
        $query_post_types = $query->get('post_type');

        if (is_string($query_post_types)) {
            $query_post_types = explode(',', $query_post_types);
        }
    } else {
        $query_post_types = get_post_types(array('exclude_from_search' => false));
    }

    if (sizeof(array_intersect($query_post_types, $post_types_to_exclude))) {
        $query->set('post_type', array_diff($query_post_types, $post_types_to_exclude));
    }

    return $query;
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
 * Extend WordPress search to include custom fields
 */

// Join posts and postmeta tables
// http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join

function cf_search_join( $join ) {
  global $wpdb;

  if ( is_search() ) {    
      $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
  }

  return $join;
}
add_filter('posts_join', 'cf_search_join' );


// Modify the search query with posts_where
// http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where

function cf_search_where( $where ) {
  global $pagenow, $wpdb;

  if ( is_search() ) {
      $where = preg_replace(
          "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
          "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
  }

  return $where;
}
add_filter( 'posts_where', 'cf_search_where' );


// Prevent duplicates
// http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct

function cf_search_distinct( $where ) {
  global $wpdb;

  if ( is_search() ) {
      return "DISTINCT";
  }

  return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

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
 * Changes search url and opens the relevant tab on the search results page
 */

// function change_search_url() {
//   if ( is_search() && ! empty( $_GET['s'] ) ) {
//       if (get_query_var( 'post_type' ) == 'people') { 
//       wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '&q=' . urlencode( get_query_var( 's' ) ) . '#vf-tabs__section--people' );
//       }
//       elseif (get_query_var( 'post_type' ) == 'documents') { 
//       wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '&q=' . urlencode( get_query_var( 's' ) ) . '#vf-tabs__section--documents' );
//       }
//       elseif (get_query_var( 'post_type' ) == 'insites') { 
//       wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '&q=' . urlencode( get_query_var( 's' ) ) . '#vf-tabs__section--news' );
//       }
//       elseif (get_query_var( 'post_type' ) == 'events') { 
//       wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '&q=' . urlencode( get_query_var( 's' ) ) . '#vf-tabs__section--events' );
//       }
//       elseif (get_query_var( 'post_type' ) == 'page') { 
//       wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '&q=' . urlencode( get_query_var( 's' ) ) . '#vf-tabs__section--pages' );
//       }
//       elseif (get_query_var( 'post_type' ) == 'any') {
//         wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) );
//       }
//       exit();
//   }   
// }
// add_action( 'template_redirect', 'change_search_url' );

/*
 * Enables search by the “s” parameter and a meta_query
 */

// add_action( 'pre_get_posts', function( $q ) {
// 	if( $title = $q->get( '_meta_or_title' ) )
// 	{
// 		add_filter( 'get_meta_sql', function( $sql ) use ( $title )
// 		{
// 			global $wpdb;
 
// 			// Only run once:
// 			static $nr = 0; 
// 			if( 0 != $nr++ ) return $sql;
 
// 			// Modified WHERE
// 			$sql['where'] = sprintf(
// 				" AND ( %s OR %s ) ",
// 				$wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
// 				mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
// 			);
 
// 			return $sql;
// 		});
// 	}
// });


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










?>
