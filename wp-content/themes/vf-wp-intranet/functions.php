<?php
/* Adds scripts */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
    wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}


require_once('functions/custom-taxonomies.php');
require_once('functions/cpt-register.php');


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
				$query->set('post_type', $post_type);
      }
      else {
        $query->set('post_type', array('post', 'page', 'documents', 'insites', 'people', 'events'));
      }
		}
	}
	return $query;
}

add_filter('pre_get_posts', 'intranet_search_filter');


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

/*
 * Set cron for people data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval' );
function vfwp_intranet_add_cron_interval( $schedules ) {
  $schedules['every_six_hours'] = array(
    'interval' => 21600, // Every 6 hours
    'display'  => __( 'Every 6 hours' ),
  );
  return $schedules;
}

// Check if cron scheduled or not.
if ( ! wp_next_scheduled( 'vfwp_intranet_cron_process' ) ) {
  wp_schedule_event( time(), 'every_six_hours', 'vfwp_intranet_cron_process' );
}

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_people_data');

// Function to start process people data.
function vfwp_intranet_cron_process_people_data() {

  $current_page = 0;
  $items_per_page = 100;
  $people_json_feed_api_endpoint = "https://content.embl.org/api/v1/people-all-info?items_per_page=$items_per_page";
  // Fetch API to get paging details.
  $people_feed_content = file_get_contents($people_json_feed_api_endpoint);
  $raw_data = json_decode($people_feed_content, true);
  if (!empty($raw_data) && is_array($raw_data)) {
    $pager_data_arr = $raw_data['pager'];
    $total_pages = $pager_data_arr['total_pages'];
    $total_items = $pager_data_arr['total_items'];

    // Loop through pages.
    for($i = $current_page; $i <= $total_pages; $i++) {
      // Call function to add people in db.
      insert_people_posts_from_json($people_json_feed_api_endpoint, $i);
    }
  }
  else {
    echo "There was error fetching paging details for people api.";
  }
}

/*
 * Function to Insert/Update people records in WP.
 */

function insert_people_posts_from_json($people_json_feed_api_endpoint, $page_number) {
  $raw_content = file_get_contents($people_json_feed_api_endpoint . "&page=$page_number");
  $raw_content_decoded = json_decode($raw_content, true);
  $people_data = $raw_content_decoded['rows'];
  $names_array = array_column($people_data, 'full_name');
  
  if (!empty($people_data) && is_array($people_data)) {
  foreach ($people_data as $key => $person) {
  $title = $person['full_name'];
  $cpid = $person['cpid'];
  $orcid = $person['orcid'];
  $photo = $person['photo'];
  $email = $person['email'];
  $biography = $person['biography'];
  $room = $person['room'];
  $bdr_id = $person['bdr_public_id'];
  $outstation = $person['outstation'];
  $telephones = $person['telephones'];
  $positions = $person['positions'];

  if (!empty($telephones[0])) {
    $telephone = $person['telephones'][0]['telephone'];
  }
  if (!empty($positions[0])) {
    $positions_name_1 = $person['positions'][0]['name'];
    $team_name_1 = $person['positions'][0]['team_name'];
    $is_primary_1 = $person['positions'][0]['is_primary'];
  }
  if (!empty($positions[1])) {
    $positions_name_2 = $person['positions'][1]['name'];
    $team_name_2 = $person['positions'][1]['team_name'];
    $is_primary_2 = $person['positions'][1]['is_primary'];
  }
  if (!empty($positions[2])) {
    $positions_name_3 = $person['positions'][2]['name'];
    $team_name_3 = $person['positions'][2]['team_name'];
    $is_primary_3 = $person['positions'][2]['is_primary'];
  }
  if (!empty($positions[3])) {
    $positions_name_4 = $person['positions'][3]['name'];
    $team_name_4 = $person['positions'][3]['team_name'];
    $is_primary_4 = $person['positions'][3]['is_primary'];
  }
  $new_post = [
    'post_title' => $title,
    'post_name' => $bdr_id,
    'post_content' => '',
    'post_status' => 'publish',
    'post_author' => 1,
    'post_type' => 'people',
  ];

  // Insert post
  if (!get_page_by_title($title, 'OBJECT', 'people')) {
    $post_id = wp_insert_post($new_post);
    add_post_meta($post_id, 'cpid', $cpid);
    add_post_meta($post_id, 'orcid', $orcid);
    add_post_meta($post_id, 'photo', $photo);
    add_post_meta($post_id, 'email', $email);
    add_post_meta($post_id, 'biography', $biography);
    add_post_meta($post_id, 'room', $room);
    add_post_meta($post_id, 'outstation', $outstation);
    if (!empty($positions[0])) {
    add_post_meta($post_id, 'positions_name_1', $positions_name_1);
    add_post_meta($post_id, 'team_name_1', $team_name_1);
    add_post_meta($post_id, 'is_primary_1', $is_primary_1);
    }
    if (!empty($positions[1])) {
    add_post_meta($post_id, 'positions_name_2', $positions_name_2);
    add_post_meta($post_id, 'team_name_2', $team_name_2);
    add_post_meta($post_id, 'is_primary_2', $is_primary_2);
    }
    if (!empty($positions[2])) {
    add_post_meta($post_id, 'positions_name_3', $positions_name_3);
    add_post_meta($post_id, 'team_name_3', $team_name_3);
    add_post_meta($post_id, 'is_primary_3', $is_primary_3);
    }
    if (!empty($positions[3])) {
    add_post_meta($post_id, 'positions_name_4', $positions_name_4);
    add_post_meta($post_id, 'team_name_4', $team_name_4);
    add_post_meta($post_id, 'is_primary_4', $is_primary_4);
    }
    if (!empty($telephones[0])) {
    add_post_meta($post_id, 'telephone', $telephone);   }
    }

 // update post if already exists
  else if (post_exists($title)){
    $get_post = get_page_by_title($title, 'OBJECT', 'people');
    $existing_post_id = $get_post->ID;
    update_post_meta($existing_post_id, 'post_title', $title);
    update_post_meta($existing_post_id, 'cpid', $cpid);
    update_post_meta($existing_post_id, 'orcid', $orcid);
    update_post_meta($existing_post_id, 'photo', $photo);
    update_post_meta($existing_post_id, 'email', $email);
    update_post_meta($existing_post_id, 'room', $room);
    update_post_meta($existing_post_id, 'biography', $biography);
    update_post_meta($existing_post_id, 'outstation', $outstation);
    if (!empty($positions[0])) {
    update_post_meta($existing_post_id, 'positions_name_1', $positions_name_1);
    update_post_meta($existing_post_id, 'team_name_1', $team_name_1);
    update_post_meta($existing_post_id, 'is_primary_1', $is_primary_1);
    }
    if (!empty($positions[1])) {
    update_post_meta($existing_post_id, 'positions_name_2', $positions_name_2);
    update_post_meta($existing_post_id, 'team_name_2', $team_name_2);
    update_post_meta($existing_post_id, 'is_primary_2', $is_primary_2);
    }
    if (!empty($positions[2])) {
    update_post_meta($existing_post_id, 'positions_name_3', $positions_name_3);
    update_post_meta($existing_post_id, 'team_name_3', $team_name_3);
    update_post_meta($existing_post_id, 'is_primary_3', $is_primary_3);
    }
    if (!empty($positions[3])) {
    update_post_meta($existing_post_id, 'positions_name_4', $positions_name_4);
    update_post_meta($existing_post_id, 'team_name_4', $team_name_4);
    update_post_meta($existing_post_id, 'is_primary_4', $is_primary_4);
    }
    if (!empty($telephones[0])) {
    update_post_meta($existing_post_id, 'telephone', $telephone);
    }     
    // compare two arrays and trash post if the title doesn't exists in the API
    // $people_array = array();
    // $people_posts = get_posts( array( 'post_type' => 'people', 'nopaging' => true) ); 
    //   foreach ($people_posts as $people_post):
    //     $people_array[] = $people_post->post_title;
    //     $result = array_diff($people_array, $names_array);   
    //       if (in_array($people_post->post_title, $result)) {
    //         wp_trash_post( $people_post->ID );
    //       }
    //   endforeach; 
  }
}

 }
}


// adds settings to run cron and sync people data manually

add_action('admin_menu', 'sync_people_menu');

function sync_people_menu(){
  add_submenu_page('edit.php?post_type=people', 'People settings', 'Settings', 'manage_options', 'sync-people-slug', 'sync_people_admin_page');
}

function sync_people_admin_page() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.')    );
  }
  // Start building the page
  echo '<div class="wrap">';
  echo '<h2>People settings</h2>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['sync_people']) && check_admin_referer('sync_people_clicked')) {
    // the button has been pressed AND we've passed the security check
    vfwp_intranet_cron_process_people_data();
  }
  echo '<form action="edit.php?post_type=people&page=sync-people-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('sync_people_clicked');
  echo '<input type="hidden" value="true" name="sync_people" />';
  submit_button('Sync people directory');
  echo '</form>';
  echo '</div>';

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
    if (is_page()) {
    if( $redirect ){
        wp_redirect( $redirect );
    } }
}

/*
 * Search results post type order
 */

// function order_search_by_posttype($orderby){
//   if (!is_admin() && is_search()) :
//       global $wpdb;
//       $orderby =
//           "
//           CASE WHEN {$wpdb->prefix}posts.post_type = 'page' THEN '1' 
//                WHEN {$wpdb->prefix}posts.post_type = 'documents' THEN '2' 
//                WHEN {$wpdb->prefix}posts.post_type = 'people' THEN '3' 
//                WHEN {$wpdb->prefix}posts.post_type = 'insites' THEN '4' 
//                WHEN {$wpdb->prefix}posts.post_type = 'events' THEN '5' 
//           ELSE {$wpdb->prefix}posts.post_type END ASC, 
//           {$wpdb->prefix}posts.post_title ASC";
//   endif;
//   return $orderby;
// }
// add_filter('posts_orderby', 'order_search_by_posttype');

// function my_search_query( $query ) {
// 	// not an admin page and is the main query
// 	if ( !is_admin() && $query->is_main_query() ) {
// 		if ( is_search() ) {
//       $query->set( 'orderby', 'relevance' );
//       return $query;		}
// 	}
// }
// add_action( 'pre_get_posts', 'my_search_query' );

// enables excerpt field for pages
add_post_type_support( 'page', 'excerpt' );


/*
 * Changes search url and opens the relevant tab on the search results page
 */

function change_search_url() {
  if ( is_search() && ! empty( $_GET['s'] ) ) {
      if (get_query_var( 'post_type' ) == 'people') { 
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '#vf-tabs__section--people' );
      }
      elseif (get_query_var( 'post_type' ) == 'documents') { 
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '#vf-tabs__section--documents' );
      }
      elseif (get_query_var( 'post_type' ) == 'insites') { 
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '#vf-tabs__section--news' );
      }
      elseif (get_query_var( 'post_type' ) == 'events') { 
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '#vf-tabs__section--events' );
      }
      elseif (get_query_var( 'post_type' ) == 'page') { 
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) . '#vf-tabs__section--pages' );
      }
      elseif (get_query_var( 'post_type' ) == 'any') {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) . '?post_type=' . urlencode( get_query_var( 'post_type' ) ) );
      }
      exit();
  }   
}
add_action( 'template_redirect', 'change_search_url' );

/*
 * Enables search by the “s” parameter and a meta_query
 */

add_action( 'pre_get_posts', function( $q ) {
	if( $title = $q->get( '_meta_or_title' ) )
	{
		add_filter( 'get_meta_sql', function( $sql ) use ( $title )
		{
			global $wpdb;
 
			// Only run once:
			static $nr = 0; 
			if( 0 != $nr++ ) return $sql;
 
			// Modified WHERE
			$sql['where'] = sprintf(
				" AND ( %s OR %s ) ",
				$wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
				mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
			);
 
			return $sql;
		});
	}
});

?>
