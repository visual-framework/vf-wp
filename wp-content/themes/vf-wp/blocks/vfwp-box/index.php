<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Box') ) :

require_once('widget.php');

class VFWP_Box extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Box

// Initialize one instance
$vfwp_box = new VFWP_Box();

endif; ?>
