<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Section_Header') ) :

class VFWP_Section_Header extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Section_Header

// Initialize one instance
$vfwp_section_header = new VFWP_Section_Header();

endif; ?>
