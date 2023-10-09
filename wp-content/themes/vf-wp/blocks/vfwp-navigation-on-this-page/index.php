<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_On_This_Page_Navigation') ) :

class VFWP_On_This_Page_Navigation extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => 'vfwp-navigation-on-this-page',
      'title'    => 'On this page navigation',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_On_This_Page_Navigation

// Initialize one instance
$vfwp_on_this_page_navigation = new VFWP_On_This_Page_Navigation();

endif; ?>
