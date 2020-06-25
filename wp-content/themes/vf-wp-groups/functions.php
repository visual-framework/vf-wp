<?php

if( ! defined( 'ABSPATH' ) ) exit;

// Child theme container plugins
require_once('vf-wp-groups-header/index.php');

// Child theme functions
require_once('functions/groups-theme.php');

/**
 * Wait for parent theme functions to initialise
 * Use `__experimental__` flag for forwards-compatibility
 */
add_action('vf/__experimental__/theme/init', function() {
  global $vf_groups_theme;
  if ( ! isset($vf_groups_theme)) {
    $vf_groups_theme = new VF_Groups_Theme();
  }
});

// Add feature image support

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// display acf in the menu

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_filter('acf/settings/show_admin', '__return_true');
function my_acf_save_post( $post_id ) {
    
    $user = get_field( 'author', $post_id );
	if( $user ) {
		wp_update_post( array( 'ID'=>$post_id, 'post_author'=>$user['ID']) ); 
	}
}
add_action('acf/save_post', 'my_acf_save_post', 20);

?>
