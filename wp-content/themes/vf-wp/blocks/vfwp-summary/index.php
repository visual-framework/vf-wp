<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Summary') ) :

class VFWP_Summary extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Summary

// Initialize one instance
$vfwp_summary = new VFWP_Summary();

endif; ?>
