<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Search') ) :

class VFWP_Search extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);

  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-search';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Search',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }
}

// Initialize one instance
$vfwp_search = new VFWP_Search();

// VFWP_Search
endif; 
 ?>
