<?php

if( ! defined( 'ABSPATH' ) ) exit;

include_once('admin-lock.php');

if ( ! class_exists('VF_Admin') ) :

class VF_Admin {

  private $vf_admin_lock;

  public function __construct() {
    // Initialize sub-modules
    if ( ! $this->vf_admin_lock) {
      $this->vf_admin_lock = new VF_Admin_Lock();
    }
    // Add admin hooks
    add_action(
      'admin_head',
      array($this, 'admin_head')
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
    );
    add_action(
      'customize_register',
      array($this, 'customize_register')
    );
  }

  /**
   * Clean up WP Admin for non-admin users
   */
  public function admin_head() {
    if ( ! current_user_can('editor')) {
      return;
    }
    // Give editors more capabilities
    $editor = get_role('editor');
    if ($editor) {
      $editor->add_cap('edit_theme_options');
    }
    // Only show allowed items in the admin menu
    $whitelist = array('nav-menus', 'widgets', 'customize');
    global $submenu;
    if (is_array($submenu) && array_key_exists('themes.php', $submenu)) {
      foreach ($submenu['themes.php'] as $i => $item) {
        $base = explode('.php', $item[2], 2);
        if ( ! in_array($base[0], $whitelist)) {
          unset($submenu['themes.php'][$i]);
        }
      }
    }
  }

  /**
   * Enqueue WP Admin CSS and JavaScript
   */
  public function admin_enqueue_scripts() {
    $theme = wp_get_theme();
    $dir = untrailingslashit(get_template_directory_uri());
    wp_enqueue_style(
      'vf_admin',
      $dir . '/assets/assets/vfwp-admin/vfwp-admin.css',
      array(),
      $theme->version,
      'all'
    );
  }

  /**
   * Add theme customisation
   */
  function customize_register($wp_customize) {

    $wp_customize->add_section('vf_theme' , array(
      'title'    => __('Visual Framework', 'vfwp'),
      'priority' => 30
    ));

    $wp_customize->add_setting('vf_theme_color', array(
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'themeslug_sanitize_select',
      'default' => '009f4d',
    ) );

    $wp_customize->add_control('vf_theme_color', array(
      'type' => 'select',
      'section' => 'vf_theme',
      'label' => __( 'Custom Select Option' ),
      'description' => __( 'This is a custom select option.' ),
      'choices' => array(
        '009f4d' => __( 'EMBL Green' ),
        '007c80' => __( 'EMBL-EBI Petrol' ),
      ),
    ) );

    function themeslug_sanitize_select( $input, $setting ) {
      // Ensure input is a slug.
      $input = sanitize_key( $input );
      // Get list of choices from the control associated with the setting.
      $choices = $setting->manager->get_control( $setting->id )->choices;
      // If the input is a valid key, return it; otherwise, return the default.
      return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }

    // No way!
    $wp_customize->remove_section('custom_css');
  }

} // VF_Admin

endif;

?>
