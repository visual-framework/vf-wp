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

    add_action('vf_header', array($this, 'header_containers'));
    add_action('vf_footer', array($this, 'footer_containers'));
  }

  public function activate() {
    parent::activate();

    // Register Page Template container
    // This is a placeholder for the normal WordPress template
    VF_Plugin::register(array(
      'post_name'  => 'vf_page_template',
      'post_title' => 'Page Template',
      'post_type' => $this->post_type
    ));
  }

  /**
   * Output all containers before the first `vf_page_template`
   * added as an action to the `vf_header` hook
   */
  static public function header_containers() {
    if ( ! have_rows('vf_containers', 'option')) {
      return;
    }
    $containers = get_field('vf_containers', 'option');
    $placeholder = array_filter($containers, function ($item) {
      return $item['vf_container_name'] === 'vf_page_template';
    });
    $total = count($containers);
    $end = count($placeholder) ? array_keys($placeholder)[0] : $total;
    for ($i = 0; $i < $end; $i++) {
      if (array_key_exists('vf_container_name', $containers[$i])) {
        $container = VF_Plugin::get_plugin($containers[$i]['vf_container_name']);
        VF_Plugin::render($container);
      }
    }
  }

  /**
   * Output all containers after the last `vf_page_template`
   * added as an action to the `vf_footer` hook
   */
  static public function footer_containers() {
    if ( ! have_rows('vf_containers', 'option')) {
      return;
    }
    $containers = get_field('vf_containers', 'option');
    $placeholder = array_filter($containers, function ($item) {
      return $item['vf_container_name'] === 'vf_page_template';
    });
    if ( ! count($placeholder)) {
      return;
    }
    $total = count($containers);
    $start = array_keys($placeholder)[count($placeholder) - 1];
    for ($i = $start; $i < count($containers); $i++) {
      if (array_key_exists('vf_container_name', $containers[$i])) {
        $container = VF_Plugin::get_plugin($containers[$i]['vf_container_name']);
        VF_Plugin::render($container);
      }
    }
  }

} // VF_Containers

endif;

?>
