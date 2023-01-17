<?php

require_once('functions/custom-taxonomies.php');
require_once('functions/cpt-register.php');

/* Adds scripts */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
    wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}


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
function vf_wp_training__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }


 // Modify default query to display training post type
 
//  add_action(
//     'pre_get_posts',
//     'vf_wp_training__pre_get_posts'
//   );
  
//   function vf_wp_training__pre_get_posts($query) {
//     if (  !is_admin() && $query->is_main_query() && !$query->is_tax() && $query->is_home()) {
//     $query->set('post_type', array( 'training' ) );
//     $query->set('order', 'ASC' );
//     $query->set('meta_key', 'vf-wp-training-start_date' );
//     $query->set('orderby', 'meta_value_num' );
//     $query->set( 'meta_query', array(         array(               'key' => 'vf-wp-training-start_date',
//     'value' => date('Ymd', strtotime('now')),
//     'compare' => '>=',
//  )   ));

//     }
//     if (is_admin()) {
//       return;
//     }
//     if ( ! $query->is_main_query()) {
//       return;
//     }
//     $post_type = get_query_var('post_type');
//     if ($post_type !== 'training') {
//       return;
//     }
//     if (is_singular()) {
//       return;
//     }
//     $query->set('posts_per_page', -1);
//   }
?>