<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Intro') ) :

class VFWP_Intro extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Intro

// Initialize one instance
$vfwp_intro = new VFWP_Intro();

endif; ?>
