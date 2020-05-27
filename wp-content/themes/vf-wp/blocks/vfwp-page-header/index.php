<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Page_Header') ) :

class VFWP_Page_Header extends VFWP_Block {

  public function __construct() {

    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-page-header';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Page Header',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Page_Header

// Initialize one instance
$vfwp_page_header = new VFWP_Page_Header();

endif; ?>
