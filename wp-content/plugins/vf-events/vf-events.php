<?php
/*
Plugin Name: VF-WP Events
Description: VF-WP events custom post type and Gutenberg blocks.
Version: 0.1.0
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once(plugin_dir_path(__FILE__) . 'includes/acf.php');
require_once(plugin_dir_path(__FILE__) . 'includes/register.php');
require_once(plugin_dir_path(__FILE__) . 'includes/template.php');

if ( ! class_exists('VF_Events') ) :

class VF_Events {

  private $acf;
  private $register;
  private $template;

  // Return custom post type
  static public function type() {
    return 'vf_event';
  }

  // Return date format from ACF options page
  static public function date_format() {
    $format = 'j F Y';
    if ( ! function_exists('get_field')) {
      return $format;
    }
    $option = get_field('vf_event_date_format', 'option');
    if ($option === 'custom') {
      $option = get_field('vf_event_date_format_custom', 'option');
    }
    $option = trim($option);
    if ( ! empty($option)) {
      $format = $option;
    }
    return $format;
  }

  /**
   * Return true if `$key` is a date meta property
   */
  static public function is_key_date($key) {
    return preg_match(
      '#' . preg_quote(VF_Events::type()) . '_(.*?)_date$#',
      $key
    );
  }

  function __construct() {
    // Do nothing...
  }

  public function initialize() {
    // Initialize sub-class instances
    $this->acf = new VF_Events_ACF(__FILE__);
    $this->register = new VF_Events_Register(__FILE__);
    $this->template = new VF_Events_Template(__FILE__);

    // Add hooks
    register_activation_hook(
      __FILE__,
      array($this, 'activate')
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
    );
  }

  /**
   * Action: `register_activation_hook`
   */
  public function activate() {
    // Ensure custom post type is registered then flush permalinks
    $this->register->init_register();
    flush_rewrite_rules();
  }

  /**
   * Action: `admin_enqueue_scripts`
   */
  public function admin_enqueue_scripts() {
    $plugin = get_plugin_data(__FILE__);
    wp_enqueue_style(
      'vf-events-admin',
      plugin_dir_url(__FILE__) . 'assets/admin.css',
      array(),
      $plugin['Version'],
      'all'
    );
  }

} // VF_Events

// Return the global `VF_Events` instance
function vf_events() {
  global $vf_events;
  if ( ! isset($vf_events)) {
    $vf_events = new VF_Events();
    $vf_events->initialize();
  }
  return $vf_events;
}

// Initialize global instance
vf_events();

endif;

?>
