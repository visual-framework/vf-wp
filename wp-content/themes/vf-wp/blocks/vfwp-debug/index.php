<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Debug') ) :

class VFWP_Debug extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-debug';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Debug',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false,
        'jsx'             => true
      )
    );
  }

} // VFWP_Debug

// Initialize one instance
$vfwp_example = new VFWP_Debug();

endif;

?>
