<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Tabs') ) :

class VFWP_Tabs extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-tabs',
      'title'    => 'Tabs',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Tabs

// Initialize one instance
$vfwp_tabs = new VFWP_Tabs();

endif; ?>
