<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Masthead') ) :

class VFWP_Masthead extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Masthead

// Initialize one instance
$vfwp_masthead = new VFWP_Masthead();

endif; ?>
