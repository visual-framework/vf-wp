<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Activity_List') ) :

class VFWP_Activity_List extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Activity_List

// Initialize one instance
$vfwp_activity_list = new VFWP_Activity_List();

endif; ?>
