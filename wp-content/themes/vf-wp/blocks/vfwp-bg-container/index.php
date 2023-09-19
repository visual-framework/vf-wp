<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_BG_Container') ) :

class VFWP_BG_Container extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-bg-container';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Container',
      'category' => 'vf/wp',
      'supports' => array(
        'vf/innerBlocks'  => true,
        'align'           => false,
        'customClassName' => false,
      )
    );
  }

} // VFWP_BG_Container

// Initialize one instance
$vfwp_bg_Container = new VFWP_BG_Container();

endif; ?>
