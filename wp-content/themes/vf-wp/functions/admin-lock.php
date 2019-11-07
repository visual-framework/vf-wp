<?php
/**
 * VF Admin Lock
 * Initiated by `VF_Admin`
 * Add post meta field `vf_locked`. When set to `1` edit capabilities
 * for the post are removed unless user has `administrator` role.
 */

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin_Lock') ) :

class VF_Admin_Lock {

  // Post types that have lock option
  private $post_types = array(
    'page',
    'post'
  );

  public function __construct() {
    // Add admin hooks
    add_filter(
      'user_has_cap',
      array($this, 'user_has_cap'),
      10, 3
    );
    add_action(
      'acf/init',
      array($this, 'acf_init')
    );
  }

  /**
   * Check if a non-admin user is trying to edit a locked post or page.
   * Remove the capability if the meta property is set.
   *
   * https://codex.wordpress.org/Plugin_API/Filter_Reference/user_has_cap
   * param array $allcaps All the capabilities of the user
   * param array $cap     [0] Required capability
   * param array $args    [0] Requested capability
   *                      [1] User ID
   *                      [2] Associated object ID
   */
  public function user_has_cap($allcaps, $cap, $args) {
    $user = wp_get_current_user();
    // Allow administrators
    if ($user->ID === 0 || in_array('administrator', $user->roles)) {
      return $allcaps;
    }
    // Ignore if already disallowed
    if ($args[0] !== 'edit_post') {
      return $allcaps;
    }
    // Ignore non-post actions
    $post = get_post($args[2]);
    if ( ! $post instanceof WP_Post) {
      return $allcaps;
    }
    // Ignore unsupported post types
    if ( ! in_array($post->post_type, $this->post_types)) {
      return $allcaps;
    }
    // Finally, remove capability if post is locked
    $locked = get_post_meta($post->ID, 'vf_locked', true);
    if ($locked) {
      $allcaps[$cap[0]] = false;
    }
    return $allcaps;
  }

  /**
   * Add ACF group and fields to manage meta property
   */
  public function acf_init() {
    if ( ! function_exists('acf_add_local_field')) {
      return;
    }
    // Register lock field
    acf_add_local_field(
      array(
        'parent' => 'group_vf_page_settings',
        'key' => 'field_vf_locked',
        'label' => __('Locked', 'vfwp'),
        'name' => 'vf_locked',
        'type' => 'true_false',
        'instructions' => __('Only the administrator role can edit locked posts or pages.', 'vfwp'),
        'default_value' => 0,
        'ui' => 1,
        'ui_on_text' => '🔒',
        'ui_off_text' => '🔓',
      )
    );
    // Setup field group locations
    $locations = array();
    foreach ($this->post_types as $post_type) {
      $locations[] = array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => $post_type,
        ),
        array(
          'param' => 'current_user_role',
          'operator' => '==',
          'value' => 'administrator',
        )
      );
    }
    // Register "Permissions" field group in sidebar
    acf_add_local_field_group(
      array(
        'key' => 'group_vf_page_settings',
        'title' => __('Permissions', 'vfwp'),
        'fields' => null,
        'position' => 'side',
        'location' => $locations,
      )
    );
  }

} // VF_Admin_Lock

endif;

?>
