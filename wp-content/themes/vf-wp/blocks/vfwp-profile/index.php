<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Profile') ) :

class VFWP_Profile extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Profile

// Initialize one instance
$vfwp_profile = new VFWP_Profile();

endif; ?>
