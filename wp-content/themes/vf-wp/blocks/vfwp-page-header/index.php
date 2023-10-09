<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Page_Header') ) :

class VFWP_Page_Header extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-page-header',
      'title'    => 'Page Header',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Page_Header

// Initialize one instance
$vfwp_page_header = new VFWP_Page_Header();

endif; ?>
