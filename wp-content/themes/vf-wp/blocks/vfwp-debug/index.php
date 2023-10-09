<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Debug') ) :

class VFWP_Debug extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => 'vfwp-debug',
      'title'    => 'Debug',
      'category' => 'vf/wp',
      'supports' => array(
        'vf/renderIFrame' => false,
        'vf/innerBlocks'  => true,
        'align'           => false,
        'customClassName' => false
      )
    );
  }

} // VFWP_Debug

// Initialize one instance
$vfwp_example = new VFWP_Debug();

endif;

?>
