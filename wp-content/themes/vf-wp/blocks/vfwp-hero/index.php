<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Hero') ) :

class VFWP_Hero extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Hero

// Initialize one instance
$vfwp_hero = new VFWP_Hero();

endif; ?>
