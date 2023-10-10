<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_On_This_Page_Navigation') ) :

class VFWP_On_This_Page_Navigation extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-navigation-on-this-page',
      'title'    => 'On this page navigation',
      'category' => 'vf/wp'
    );
  }

} // VFWP_On_This_Page_Navigation

// Initialize one instance
$vfwp_on_this_page_navigation = new VFWP_On_This_Page_Navigation();

endif; ?>
