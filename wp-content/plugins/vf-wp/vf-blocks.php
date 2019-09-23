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
    add_filter(
      'block_categories',
      array($this, '_deprecated_block_categories'),
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

  /**
   * WARNING: deprecated method
   * Add Gutenberg blocks category for Content Hub blocks
   */
  public function _deprecated_block_categories($categories, $post) {
    return array_merge(
      $categories,
      array(
        array(
          'slug'  => 'vf_blocks_content_hub',
          'title' => __('EMBL Content Hub (deprecated)', 'vfwp'),
          'icon'  => null
        )
      )
    );
  }

} // VF_Blocks

endif;

?>
