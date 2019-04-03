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
   * Add Gutenberg blocks category for Content Hub blocks
   */
  public function block_categories($categories, $post) {
    $post_types = array(
      'post',
      'page'
    );
    if ( ! in_array($post->post_type, $post_types)) {
      return $categories;
    }
    return array_merge(
      array(
        array(
          'slug'  => 'vf_blocks_content_hub',
          'title' => __('EMBL Content Hub', 'vfwp'),
          'icon'  => null
        )
      ),
      $categories
    );
  }

} // VF_Blocks

endif;

?>
