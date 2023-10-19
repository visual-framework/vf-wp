<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Post_Fetch') ) :

require_once('widget.php');

class VFWP_Post_Fetch extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Post_Fetch

// Initialize one instance
$vfwp_post_fetch = new VFWP_Post_Fetch();

endif; ?>
