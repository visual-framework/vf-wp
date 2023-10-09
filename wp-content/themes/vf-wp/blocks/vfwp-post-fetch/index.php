<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Post_Fetch') ) :

  require_once('widget.php');

class VFWP_Post_Fetch extends VFWP_Block {

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
      'name'     => 'vfwp-post-fetch',
      'title'    => 'Fetch posts',
      'category' => 'vf/wp',
      'supports' => array(
        'vf/renderIFrame' => false,
        'vf/innerBlocks'  => true,
        'align'           => false,
        'customClassName' => false,
      )
    );
  }

} // VFWP_Post_Fetch

// Initialize one instance
$vfwp_post_fetch = new VFWP_Post_Fetch();

endif; ?>
