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


add_action('admin_head', 'insert_people_posts_from_json');

// create people directory from the API  
function insert_people_posts_from_json(){
	// https://dev.content.embl.org/api/v1/people-all-info?page=0&items_per_page=25
	// https://dev.content.embl.org/api/v1/people-all-info?full_name=Szymon%20Kasprzyk

$json_feed_1 = "https://dev.content.embl.org/api/v1/people-all-info?page=0&items_per_page=5";
$json_content_1 = file_get_contents($json_feed_1);
$data_1 = json_decode($json_content_1, true);
$people = array_merge_recursive( $data_1);
  
foreach($people as $person){
  $title = $person['full_name'];
  $cpid = $person['cpid'];
  $orcid = $person['orcid'];
  $photo = $person['photo'];
  $email = $person['email'];
  $outstation = $person['outstation'];
  $a = $person['telephones'];
  $b = $person['positions'];

  // var_dump($a[0]);
  if (!empty($a[0])) {
  $telephone = $person['telephones'][0]['telephone'];
  }

  if (!empty($b[0])) {
  $positions_name_1  = $person['positions'][0]['name'];
  $team_name_1  = $person['positions'][0]['team_name'];
  $is_primary_1 = $person['positions'][0]['is_primary'];
  }

  if (!empty($b[1])) {
  $positions_name_2  = $person['positions'][1]['name'];
  $team_name_2  = $person['positions'][1]['team_name'];
  $is_primary_2 = $person['positions'][1]['is_primary'];
  }

  if (!empty($b[2])) {
  $positions_name_3  = $person['positions'][2]['name'];
  $team_name_3  = $person['positions'][2]['team_name'];
  $is_primary_3 = $person['positions'][2]['is_primary'];
  }

   if (!empty($b[3])) {
  $positions_name_4  = $person['positions'][3]['name'];
  $team_name_4  = $person['positions'][3]['team_name'];
  $is_primary_4  = $person['positions'][3]['is_primary'];
}
	  $new_post = array(
		  'post_title' => $title,
		  'post_name' => $cpid,
		  'post_content' => '',
		  'post_status' => 'publish',
		  'post_author' => 1,
		  'post_type' => 'people',
	  );
		// Check if already exists
			// Insert post
			if (!get_page_by_title($title, 'OBJECT', 'people') ){
			// Insert post
			$post_id = wp_insert_post( $new_post );
			add_post_meta($post_id, 'cpid', $cpid);
			add_post_meta($post_id, 'orcid', $orcid);
			add_post_meta($post_id, 'photo', $photo);
			add_post_meta($post_id, 'email', $email);
			add_post_meta($post_id, 'outstation', $outstation);
			if (!empty($b[0])) {
			add_post_meta($post_id, 'positions_name_1', $positions_name_1);
			add_post_meta($post_id, 'team_name_1', $team_name_1);
			add_post_meta($post_id, 'is_primary_1', $is_primary_1);
			}
			if (!empty($b[1])) {
			add_post_meta($post_id, 'positions_name_2', $positions_name_2);
			add_post_meta($post_id, 'team_name_2', $team_name_2);
			add_post_meta($post_id, 'is_primary_2', $is_primary_2);
			}
			if (!empty($b[2])) {
			add_post_meta($post_id, 'positions_name_3', $positions_name_3);
			add_post_meta($post_id, 'team_name_3', $team_name_3);
			add_post_meta($post_id, 'is_primary_3', $is_primary_3);
			}
			if (!empty($b[3])) {
			add_post_meta($post_id, 'positions_name_4', $positions_name_4);
			add_post_meta($post_id, 'team_name_4', $team_name_4);
			add_post_meta($post_id, 'is_primary_4', $is_primary_4);
			}
			if (!empty($a[0])) {
			add_post_meta($post_id, 'telephone', $telephone);	}
}
		  else 
			  $post_id = get_the_ID();			  
			  update_post_meta($post_id, 'cpid', $cpid);
			  update_post_meta($post_id, 'orcid', $orcid);
			  update_post_meta($post_id, 'photo', $photo);
			  update_post_meta($post_id, 'email', $email);
			  update_post_meta($post_id, 'outstation', $outstation);
			  if (!empty($b[0])) {
			  update_post_meta($post_id, 'positions_name_1', $positions_name_1);
			  update_post_meta($post_id, 'team_name_1', $team_name_1);
			  update_post_meta($post_id, 'is_primary_1', $is_primary_1);
			  }
			  if (!empty($b[1])) {
			  update_post_meta($post_id, 'positions_name_2', $positions_name_2);
			  update_post_meta($post_id, 'team_name_2', $team_name_2);
			  update_post_meta($post_id, 'is_primary_2', $is_primary_2);
			  }
			  if (!empty($b[2])) {
			  update_post_meta($post_id, 'positions_name_3', $positions_name_3);
			  update_post_meta($post_id, 'team_name_3', $team_name_3);
			  update_post_meta($post_id, 'is_primary_3', $is_primary_3);
			  }
			  if (!empty($b[3])) {
			  update_post_meta($post_id, 'positions_name_4', $positions_name_4);
			  update_post_meta($post_id, 'team_name_4', $team_name_4);
			  update_post_meta($post_id, 'is_primary_4', $is_primary_4);
			  }
			  if (!empty($a[0])) {
			  update_post_meta($post_id, 'telephone', $telephone);	}


} }

?>
