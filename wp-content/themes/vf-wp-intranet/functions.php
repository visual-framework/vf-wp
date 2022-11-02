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
// require_once('functions/relevanssi.php');


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



// add_action('pre_get_posts', 'remove_my_cpt_from_search_results');

// function remove_my_cpt_from_search_results($query) {
//     if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
//         return $query;
//     }

//     // can exclude multiple post types, for ex. array('staticcontent', 'cpt2', 'cpt3')
//     $post_types_to_exclude = array('people');

//     if ($query->get('post_type')) {
//         $query_post_types = $query->get('post_type');

//         if (is_string($query_post_types)) {
//             $query_post_types = explode(',', $query_post_types);
//         }
//     } else {
//         $query_post_types = get_post_types(array('exclude_from_search' => false));
//     }

//     if (sizeof(array_intersect($query_post_types, $post_types_to_exclude))) {
//         $query->set('post_type', array_diff($query_post_types, $post_types_to_exclude));
//     }

//     return $query;
// }


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

// function cf_search_join( $join ) {
//   global $wpdb;

//   if ( is_search() ) {    
//       $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
//   }

//   return $join;
// }
// add_filter('posts_join', 'cf_search_join' );


// Modify the search query with posts_where
// http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where

// function cf_search_where( $where ) {
//   global $pagenow, $wpdb;

//   if ( is_search() ) {
//       $where = preg_replace(
//           "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
//           "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
//   }

//   return $where;
// }
// add_filter( 'posts_where', 'cf_search_where' );


// Prevent duplicates
// http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct

// function cf_search_distinct( $where ) {
//   global $wpdb;

//   if ( is_search() ) {
//       return "DISTINCT";
//   }

//   return $where;
// }
// add_filter( 'posts_distinct', 'cf_search_distinct' );

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





// add_action( 'pre_get_posts', function( $q )
// {
//     if( $title = $q->get( '_meta_or_title' ) )
//     {
//         add_filter( 'get_meta_sql', function( $sql ) use ( $title )
//         {
//             global $wpdb;

//             // Only run once:
//             static $nr = 0; 
//             if( 0 != $nr++ ) return $sql;

//             // Modified WHERE
//             $sql['where'] = sprintf(
//                 " AND ( %s OR %s ) ",
//                 $wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
//                 mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
//             );

//             return $sql;
//         });
//     } 
// });



?>
<?php

// /**
//  * [list_searcheable_acf list all the custom fields we want to include in our search query]
//  * @return [array] [list of custom fields]
//  */
// function list_searcheable_acf(){
//   $list_searcheable_acf = array("keyword");
//   return $list_searcheable_acf;
// }


// /**
//  * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
//  * @param  [query-part/string]      $where    [the initial "where" part of the search query]
//  * @param  [object]                 $wp_query []
//  * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
//  * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
//  * credits to Vincent Zurczak for the base query structure/spliting tags section
//  */
// function advanced_custom_search( $where, $wp_query ) {

//     global $wpdb;
 
//     if ( empty( $where ))
//         return $where;
 
//     // get search expression
//     $terms = $wp_query->query_vars[ 's' ];
    
//     // explode search expression to get search terms
//     $exploded = explode( ' ', $terms );
//     if( $exploded === FALSE || count( $exploded ) == 0 )
//         $exploded = array( 0 => $terms );
         
//     // reset search in order to rebuilt it as we whish
//     $where = '';
    
//     // get searcheable_acf, a list of advanced custom fields you want to search content in
//     $list_searcheable_acf = list_searcheable_acf();

//     foreach( $exploded as $tag ) :
//         $where .= " 
//           AND (
//             (wp_posts.post_title LIKE '%$tag%')
//             OR (wp_posts.post_content LIKE '%$tag%')
//             OR EXISTS (
//               SELECT * FROM wp_postmeta
// 	              WHERE post_id = wp_posts.ID
// 	                AND (";

//         foreach ($list_searcheable_acf as $searcheable_acf) :
//           if ($searcheable_acf == $list_searcheable_acf[0]):
//             $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
//           else :
//             $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
//           endif;
//         endforeach;

// 	        $where .= ")
//             )
//             OR EXISTS (
//               SELECT * FROM wp_comments
//               WHERE comment_post_ID = wp_posts.ID
//                 AND comment_content LIKE '%$tag%'
//             )
//             OR EXISTS (
//               SELECT * FROM wp_terms
//               INNER JOIN wp_term_taxonomy
//                 ON wp_term_taxonomy.term_id = wp_terms.term_id
//               INNER JOIN wp_term_relationships
//                 ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
//               WHERE (
//           		taxonomy = 'post_tag'
//             		OR taxonomy = 'category'          		
//             		OR taxonomy = 'myCustomTax'
//           		)
//               	AND object_id = wp_posts.ID
//               	AND wp_terms.name LIKE '%$tag%'
//             )
//         )";
//     endforeach;
//     return $where;
// }
 
// add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );