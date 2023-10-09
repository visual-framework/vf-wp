<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_BG_Container') ) :

class VFWP_BG_Container extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-bg-container',
      'title'    => 'Container',
      'category' => 'vf/wp',
      // 'vfwp'     => array(
      //   'iframeRender' => false
      // ),
      'supports' => array(
        'jsx'             => true,
        'align'           => false,
        'customClassName' => false,
      )
    );
  }

} // VFWP_BG_Container

// Initialize one instance
$vfwp_bg_Container = new VFWP_BG_Container();

endif; ?>
