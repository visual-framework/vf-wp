<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Badge') ) :

class VFWP_Badge extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-badge';
  }

  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Badge',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Badge

// Initialize one instance
$vfwp_badge = new VFWP_Badge();

endif; ?>
