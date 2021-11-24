<?php

/* Events post type */
require_once('functions/event_type_taxonomy.php');
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

/* display acf in the menu */

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_filter('acf/settings/show_admin', '__return_true');
function my_acf_save_post( $post_id ) {

    $user = get_field( 'author', $post_id );
	if( $user ) {
		wp_update_post( array( 'ID'=>$post_id, 'post_author'=>$user['ID']) );
	}
}
add_action('acf/save_post', 'my_acf_save_post', 20);

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
		get_stylesheet_directory_uri() . '/assets/admin.css',
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

// Adds year and month to the permalink

function event_custom_acf_field_link( $permalink, $post, $leavename ) {
    if ( stripos( $permalink, '%event_year%' ) == false )
        return $permalink;

    if ( is_object( $post ) && 'events' == $post->post_type ) {

        $default_year = get_the_date('Y');
        $default_month = get_the_date('m');
        // Year
        if( $event_year = get_post_meta( $post->ID, 'vf_event_start_date', true ) ){
            $year_start = strtotime($event_year);
            $YearDate = date('Y', $year_start);
            $permalink = str_replace( '%event_year%', $YearDate, $permalink );
        } else {
            $permalink = str_replace( '%event_year%', $default_year, $permalink );
        }
		

    }

    return $permalink;
}

// Adds event type field value to the permalink

add_filter('post_type_link', 'event_permalink_structure', 10, 3);
function event_permalink_structure($post_link, $post, $leavename) {
    if (false !== strpos($post_link, '%type%')) {
        $type_term = get_post_meta($post->ID, 'vf_event_event_type', true);
		$type_term = str_replace('_', '-', $type_term);
        if (!empty($type_term))
            $post_link = str_replace('%type%', $type_term, $post_link);
        else
            $post_link = str_replace('%type%', 'uncategorized', $post_link);
    } 
    return $post_link;
}


add_filter( 'post_type_link', 'event_custom_acf_field_link', 10, 4 );


?>
