<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Embed') ) :

require_once('widget.php');

class VFWP_Embed extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Embed

// Initialize one instance
$vfwp_embed = new VFWP_Embed();

endif; ?>
