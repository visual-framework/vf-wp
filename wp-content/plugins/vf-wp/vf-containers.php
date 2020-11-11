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
    add_action('after_setup_theme',
      array($this, 'after_setup_theme'),
      20
    );
  }

  public function activate() {
    parent::activate();
  }

  static public function post_type() {
    return 'vf_container';
  }

  static public function block_category() {
    return 'vf/containers';
  }

  /**
   * Action: `block_categories`
   */
  static public function block_categories($categories, $post) {
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

  /**
   * Convert a Gutenberg block name to a VF_Plugin post name
   * e.g. `acf/vf-container-global-header` to `vf_global_header`
   * e.g. `vf/container-global-header` to `vf_global_header` (legacy support)
   */
  static function name_block_to_post($str, $separator = '_') {
    $str = str_replace('acf/vf-container-', 'vf/', $str);
    $str = str_replace('vf/container-', 'vf/', $str);
    $str = preg_replace('/[^\w]/', $separator, $str);
    return $str;
  }

  /**
   * Convert a VF_Plugin post name to a Gutenberg block name
   * e.g. `vf_global_header` to `acf/vf-container-global-header`
   */
  static function name_post_to_block($str, $prefix = 'acf/', $separator = '-') {
    $str = preg_replace('/[\W_]/', $separator, $str);
    $str = preg_replace('/(^[\w]+)-/', '$1-container-', $str);
    return "{$prefix}$str";
  }

  /**
   * Action: `after_setup_theme`
   */
  public function after_setup_theme() {
    global $vf_plugins;
    if ( ! is_array($vf_plugins)) {
      return;
    }

    // Iterate over registered container plugins
    foreach ($vf_plugins as $key => $config) {
      if ($config['post_type'] !== $this->type()) {
        continue;
      }

      // Skip unknown or deprecated plugins
      $plugin = VF_Plugin::get_plugin($key);
      if ( ! $plugin || $plugin->is_deprecated()) {
        continue;
      }

      // Setup render callback using VF Gutenberg plugin or fallback
      $callback = function() use ($plugin) {
        $args = func_get_args();
        $acf_id = $plugin->post()->ID;
        $template = $plugin->template();
        $block = $args[0];
        if (method_exists($plugin, 'template_callback')) {
          $template = array($plugin, 'template_callback');
        }
        if (get_field('is_plugin', $block['id']) === 1) {
          $plugin = VF_Containers::name_block_to_post($block['name']);
          $plugin = VF_Plugin::get_plugin($plugin);
          if ($plugin) {
            acf_reset_meta($block['id']);
            $template = function($args) use ($plugin) {
              VF_Plugin::render($plugin);
            };
          }
        }
        if (class_exists('VF_Gutenberg')) {
          VF_Gutenberg::acf_render_template($args, $template, $acf_id);
        } else {
          include($template);
        }
      };

      // Register the Gutenberg block with ACF
      acf_register_block_type(array_merge(
        array(
          'name'     => VF_Containers::name_post_to_block($key, ''),
          'title'    => $plugin->post()->post_title,
          'category' => VF_Containers::block_category(),
          'supports' => array(
            'customClassName' => false,
            'align'           => false,
            'multiple'        => false,
            'mode'            => false,
          )
        ),
        array(
          'render_callback' => $callback
        )
      ));
    }
  }

} // VF_Containers

endif;

?>
