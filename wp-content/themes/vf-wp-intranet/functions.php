<?php 

require_once('functions/vf-wp-intranet-breadcrumbs.php');
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
 

?>
