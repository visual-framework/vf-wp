<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Details') ) :

require_once('widget.php');

class VFWP_Details extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Details

// Initialize one instance
$vfwp_details = new VFWP_Details();

endif; ?>
