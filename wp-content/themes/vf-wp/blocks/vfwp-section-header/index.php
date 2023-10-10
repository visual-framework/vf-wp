<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Section_Header') ) :

class VFWP_Section_Header extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-section-header',
      'title'    => 'Section Header',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Section_Header

// Initialize one instance
$vfWP_section_header = new VFWP_Section_Header();

endif; ?>
