<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Button') ) :

class VFWP_Button extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-button';
  }

} // VFWP_Button

// Initialize one instance
$vfwp_button = new VFWP_Button();

endif; ?>
