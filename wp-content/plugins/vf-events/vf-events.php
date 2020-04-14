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

require_once('includes/acf.php');
require_once('includes/register.php');

if ( ! class_exists('VF_Events') ) :

class VF_Events {

  private $acf;
  private $register;

  function __construct() {
    // Do nothing...
  }

  public function initialize() {
    // Initialize sub-class instances
    $this->acf = new VF_Events_ACF(__FILE__);
    $this->register = new VF_Events_Register();

    // Add hooks
    register_activation_hook(
      __FILE__,
      array($this, 'activate')
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
