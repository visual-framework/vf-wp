<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_EMBL_Events') ) :

class VFWP_EMBL_Events extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-embl-events',
      'title'    => 'EMBL Events',
      'category' => 'vf/wp'
    );
  }

} // VFWP_EMBL_Events

// Initialize one instance
$vfwp_embl_events = new VFWP_EMBL_Events();

endif; ?>
