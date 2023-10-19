<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Blockquote') ) :

class VFWP_Blockquote extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Blockquote

// Initialize one instance
$vfwp_blockquote = new VFWP_Blockquote();

endif; ?>
