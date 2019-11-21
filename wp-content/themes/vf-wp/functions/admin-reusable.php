<?php
/**
 * VF Admin Reusable
 * Expand upon core WP reusable block features
 */
if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin_Reusable') ) :

class VF_Admin_Reusable {

  public function __construct() {
    if ( ! is_admin()) {
      return;
    }
    // Add admin hooks
    add_action(
      'registered_post_type',
      array($this, 'registered_post_type'),
      10, 2
    );
  }

  /**
   * Expose the reusable block post type in the admin menu
   */
  public function registered_post_type($post_type, $post_type_object) {
    if ($post_type !== 'wp_block') {
      return;
    }
    $post_type_object->_builtin = false;
    $post_type_object->labels->name = __('Reusable Blocks');
    $post_type_object->labels->menu_name = __('Reusable Blocks');
    $post_type_object->show_in_menu = true;
    $post_type_object->menu_position = 20;
    $post_type_object->menu_icon = 'dashicons-layout';
  }

} // VF_Admin_Reusable

endif;

?>
