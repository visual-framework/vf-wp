<?php
require_once('functions/custom-taxonomies.php');
require_once('functions/custom-post-types.php');


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

