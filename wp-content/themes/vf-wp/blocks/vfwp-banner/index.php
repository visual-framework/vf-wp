<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Banner') ) :

class VFWP_Banner extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-banner',
      'title'    => 'Banner',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Banner

// Initialize one instance
$vfwp_banner = new VFWP_Banner();

endif; ?>
