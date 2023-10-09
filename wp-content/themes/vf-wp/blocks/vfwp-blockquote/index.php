<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Blockquote') ) :

class VFWP_Blockquote extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-blockquote',
      'title'    => 'Blockquote',
      'category' => 'vf/wp',
      'supports' => array(
        'align'           => false,
        'customClassName' => false,
      )
    );
  }

} // VFWP_Blockquote

// Initialize one instance
$vfwp_blockquote = new VFWP_Blockquote();

endif; ?>
