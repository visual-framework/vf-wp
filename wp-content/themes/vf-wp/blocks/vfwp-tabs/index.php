<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Tabs') ) :

class VFWP_Tabs extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Tabs

// Initialize one instance
$vfwp_tabs = new VFWP_Tabs();

endif; ?>
