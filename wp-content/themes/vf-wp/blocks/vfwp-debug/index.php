<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Debug') ) :

class VFWP_Debug extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Debug

// Initialize one instance
$vfwp_debug = new VFWP_Debug();

endif;

?>
