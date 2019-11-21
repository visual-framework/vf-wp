<?php
/**
 * VF Admin
 * Theme hooks and functions related to the WordPress admin area.
 */
if( ! defined( 'ABSPATH' ) ) exit;

include_once('admin-customize.php');
include_once('admin-reusable.php');
include_once('admin-lock.php');

if ( ! class_exists('VF_Admin') ) :

class VF_Admin {

  // Sub-class instances
  private $admin_customize;
  private $admin_reusable;
  private $admin_lock;

  public function __construct() {

    // Initialize sub-class instances
    $this->admin_customize = new VF_Admin_Customize();
    $this->admin_reusable  = new VF_Admin_Reusable();
    $this->admin_lock      = new VF_Admin_Lock();

    // Add admin hooks
    add_action(
      'admin_head',
      array($this, 'admin_head')
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
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

} // VF_Admin

endif;

?>
