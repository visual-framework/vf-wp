<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Details') ) :

  require_once('widget.php');
class VFWP_Details extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-details';
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => 'Details',
      'category' => 'vf/wp',
      'supports' => array(
        'vf/renderIFrame' => false,
        'vf/innerBlocks'  => true,
        'align'           => false,
        'customClassName' => false,
      )
    );
  }

} // VFWP_Details

// Initialize one instance
$vfwp_details = new VFWP_Details();

endif; ?>
