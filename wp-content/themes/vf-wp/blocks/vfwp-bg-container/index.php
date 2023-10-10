<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_BG_Container') ) :

class VFWP_BG_Container extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_BG_Container

// Initialize one instance
$vfwp_bg_container = new VFWP_BG_Container();

endif; ?>
