<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Button') ) :

class VFWP_Button extends VFWP_Block {

  public function __construct() {

    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-button';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Button',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Button

// Initialize one instance
$vfwp_button = new VFWP_Button();

endif; ?>
