<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Containers') ) :

/**
 * Represent a custom post type for Visual Framework containers
 */
class VF_Containers extends VF_Type {

  protected $post_type = 'vf_container';
  protected $post_type_plural = 'vf_containers';
  protected $description = 'Containers';

  protected $labels = array(
    'name'          => 'Containers',
    'singular_name' => 'Container',
    'edit_item'     => 'Edit Container'
  );

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

  static public function block_category() {
    return 'vf/containers';
  }

  /**
   * Action: `block_categories`
   */
  function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => VF_Containers::block_category(),
          'title' => __('EMBL – Containers', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

} // VF_Containers

endif;

?>
