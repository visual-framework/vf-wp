<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Search') ) :

class VFWP_Search extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

}

// Initialize one instance
$vfwp_search = new VFWP_Search();

// VFWP_Search
endif;
 ?>
