<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Lists') ) :

require_once('widget.php');

class VFWP_Lists extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-links-list',
      'title'    => 'Links List',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Lists

// Initialize one instance
$vfWP_lists = new VFWP_Lists();

endif; ?>
