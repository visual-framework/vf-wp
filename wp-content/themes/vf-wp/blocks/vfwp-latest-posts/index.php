<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Latest_Posts') ) :

class VFWP_Latest_Posts extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);
  }

} // VFWP_Latest_Posts

// Initialize one instance
$vfwp_latest_posts = new VFWP_Latest_Posts();

endif;

?>
