<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Latest_Posts') ) :

class VFWP_Latest_Posts extends VFWP_Block {

  public function __construct() {
    // Allow block to use full-width container layout
    $this->setup_containerable();

    parent::__construct(__FILE__);
  }

  public function get_config() {
    return array(
      'name'     => 'vfwp-latest-posts',
      'title'    => 'Latest Posts',
      'category' => 'vf/wp'
    );
  }

} // VFWP_Latest_Posts

// Initialize one instance
$vfwp_latest_posts = new VFWP_Latest_Posts();

endif;

?>
