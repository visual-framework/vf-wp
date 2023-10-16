<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Page_Header') ) :

class VFWP_Page_Header extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Page_Header

// Initialize one instance
$vfwp_page_header = new VFWP_Page_Header();

endif; ?>
