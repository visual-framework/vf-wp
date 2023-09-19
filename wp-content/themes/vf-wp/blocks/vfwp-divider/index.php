<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Divider') ) :

class VFWP_Divider extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-divider';
  }

} // VFWP_Divider

// Initialize one instance
$vfwp_divider = new VFWP_Divider();

endif; ?>
