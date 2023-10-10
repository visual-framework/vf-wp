<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Figure') ) :

require_once('widget.php');

class VFWP_Figure extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-figure',
      'title'    => 'Figure',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Figure

// Initialize one instance
$vfwp_figure = new VFWP_Figure();

endif; ?>
