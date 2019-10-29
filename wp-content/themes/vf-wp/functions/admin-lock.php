<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin_Lock') ) :

class VF_Admin_Lock {

  public function __construct() {
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
   * Check if a non-admin user is trying to edit a locked post or page
   * Remove the capability if the meta property is set
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
    if ($user->ID === 0 || in_array('administrator', $user->roles)) {
      return $allcaps;
    }
    if ($args[0] !== 'edit_post') {
      return $allcaps;
    }
    $post = get_post($args[2]);
    if ( ! $post instanceof WP_Post) {
      return $allcaps;
    }
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
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'message' => '',
        'default_value' => 0,
        'ui' => 1,
        'ui_on_text' => '🔒',
        'ui_off_text' => '🔓',
      )
    );

    // Register "Permissions" field group in sidebar
    acf_add_local_field_group(
      array(
        'key' => 'group_vf_page_settings',
        'title' => __('Permissions', 'vfwp'),
        'fields' => null,
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'post',
            ),
            array(
              'param' => 'current_user_role',
              'operator' => '==',
              'value' => 'administrator',
            ),
          ),
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'page',
            ),
            array(
              'param' => 'current_user_role',
              'operator' => '==',
              'value' => 'administrator',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      )
    );
  }

} // VF_Admin_Lock

endif;

?>
