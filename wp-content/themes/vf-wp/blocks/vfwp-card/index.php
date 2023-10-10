<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Card') ) :

class VFWP_Card extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-card',
      'title'    => 'Card',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Card

// Initialize one instance
$vfwp_card = new VFWP_Card();

endif; ?>
