<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Lede') ) :

class VFWP_Lede extends VFWP_Block {

  public function __construct() {

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-lede',
      'title'    => 'Lede',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Lede

// Initialize one instance
$vfwp_lede = new VFWP_Lede();

endif; ?>
