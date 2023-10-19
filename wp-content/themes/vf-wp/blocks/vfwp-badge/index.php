<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Badge') ) :

class VFWP_Badge extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Badge

// Initialize one instance
$vfwp_badge = new VFWP_Badge();

endif; ?>
