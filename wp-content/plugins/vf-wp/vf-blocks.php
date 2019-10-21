<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Blocks') ) :

/**
 * Represent a custom post type for Visual Framework blocks
 */
class VF_Blocks extends VF_Type {

  public function initialize() {
    parent::initialize();
    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );
  }

  public function activate() {
    parent::activate();
  }

  /**
   * Action: `block_categories`
   */
  function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => 'vf/wp',
          'title' => __('Visual Framework (WordPress)', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

} // VF_Blocks

endif;

?>
