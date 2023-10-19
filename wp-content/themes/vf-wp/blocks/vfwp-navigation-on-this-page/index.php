<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_On_This_Page_Navigation') ) :

class VFWP_On_This_Page_Navigation extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_On_This_Page_Navigation

// Initialize one instance
$vfwp_on_this_page_navigation = new VFWP_On_This_Page_Navigation();

endif; ?>
