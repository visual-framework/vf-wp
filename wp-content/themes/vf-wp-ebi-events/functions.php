<?php

/* Events post type */
require_once('functions/cpt_events_register.php');

/* Adds jplist script */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
    wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}

/* Load ACF JSON from theme */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }

/* Theme CSS file */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('vfwp'),
		wp_get_theme()->get('Version')
	);
	wp_enqueue_style(
		'vf-events-admin',
		get_stylesheet_directory_uri() . 'assets/admin.css',
		array('vfwp'),
		wp_get_theme()->get('Version')
	);
}


function get_pretty_date($date) {
    return date('l, F j, Y', strtotime($date));
  }

  /*
 * Redirect pages to external links
 */

add_action( 'template_redirect', 'redirect_externally' );
function redirect_externally(){
    $redirect = get_post_meta( get_the_ID(), 'vf_event_redirect', true );
    if (is_single()) {
    if( $redirect ){
        wp_redirect( $redirect );
    } }
}


?>
