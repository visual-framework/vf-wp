<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Banner') ) :

class VFWP_Banner extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Banner

// Initialize one instance
$vfwp_banner = new VFWP_Banner();

endif; ?>
