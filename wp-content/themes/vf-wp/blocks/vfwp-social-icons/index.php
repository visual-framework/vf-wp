<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Social_Icons') ) :

require_once('widget.php');

class VFWP_Social_Icons extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Social_Icons

// Initialize one instance
$vfwp_social_icons = new VFWP_Social_Icons();

endif; ?>
