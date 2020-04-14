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

if ( ! class_exists('VF_Events') ) :

require_once('includes/register.php');

class VF_Events {

  private $register;

  function __construct() {
    // Do nothing...
  }

  /**
   * Setup sub-instances
   */
  public function initialize() {
    $this->register = new VF_Events_Register();
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
