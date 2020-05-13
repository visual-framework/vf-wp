<?php
/*
Plugin Name: VF-WP
Description: VF-WP theme plugin manager.
Version: 1.0.0-beta.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Require classes
require_once('vf-helpers.php');
require_once('vf-cache.php');
require_once('vf-plugin.php');
require_once('vf-type.php');
require_once('vf-blocks.php');
require_once('vf-containers.php');
require_once('vf-templates.php');
require_once('vf-acf.php');

// Add action hook after opening `<body>` tag
function vf_header() {
  do_action('vf_header');
}

// Add action hook before closing `</body>` tag and before `wp_footer` action
function vf_footer() {
  do_action('vf_footer');
}

class VF_WP {
  /**
   * Initialize custom post type classes using global variables
   */
  public function __construct() {

    global $vf_acf;
    global $vf_cache;
    global $vf_blocks;
    global $vf_containers;
    global $vf_templates;

    if ( ! isset($vf_acf)) {
      $vf_acf = new VF_ACF();
      $vf_acf->initialize();
    }

    if ( ! isset($vf_cache)) {
      $vf_cache = new VF_Cache();
      $vf_cache->initialize();
    }

    if ( ! isset($vf_blocks)) {
      $vf_blocks = new VF_Blocks();
      $vf_blocks->initialize();
    }

    if ( ! isset($vf_containers)) {
      $vf_containers = new VF_Containers();
      $vf_containers->initialize();
    }

    if ( ! isset($vf_templates)) {
      $vf_templates = new VF_Templates();
      $vf_templates->initialize();
    }

    register_activation_hook(
      __FILE__,
      array($this, 'activate')
    );

    register_deactivation_hook(
      __FILE__,
      array($this, 'deactivate')
    );

    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );

     add_action('acf/init',
      array($this, 'acf_init')
    );

    add_action(
      'enqueue_block_editor_assets',
      array($this, 'enqueue_block_editor_assets')
    );

    add_action(
      'admin_menu',
      array($this, 'admin_menu')
    );

    // ACF load and save setup - saving only useful for development
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json'),
      1
    );

    add_filter(
      'single_template',
      array($this, 'single_template')
    );

    add_filter(
      'body_class',
      array($this, 'body_class'),
      30, 1
    );

    if (vf_debug()) {
      add_action(
        'vf/plugin/before_render',
        array($this, 'plugin_before_render')
      );
      add_action(
        'vf/plugin/after_render',
        array($this, 'plugin_after_render')
      );
    }
  }

  /**
   * Trigger activation hooks for custom post type classes
   */
  public function activate() {
    global $vf_cache;
    global $vf_blocks;
    global $vf_containers;
    global $vf_templates;
    if ($vf_cache instanceof VF_Cache) {
      $vf_cache->activate();
    }
    if ($vf_blocks instanceof VF_Blocks) {
      $vf_blocks->activate();
    }
    if ($vf_containers instanceof VF_Containers) {
      $vf_containers->activate();
    }
    if ($vf_templates instanceof VF_Templates) {
      $vf_templates->activate();
    }
  }

  /**
   * Trigger deactivation hooks for custom post type classes
   */
  public function deactivate() {
    global $vf_cache;
    global $vf_blocks;
    global $vf_containers;
    global $vf_templates;
    if ($vf_cache instanceof VF_Cache) {
      $vf_cache->deactivate();
    }
    if ($vf_blocks instanceof VF_Blocks) {
      $vf_blocks->deactivate();
    }
    if ($vf_containers instanceof VF_Containers) {
      $vf_containers->deactivate();
    }
    if ($vf_templates instanceof VF_Templates) {
      $vf_templates->deactivate();
    }
  }

  /**
   * Action: `block_categories`
   */
  static public function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => 'vf/wp',
          'title' => __('EMBL – WordPress (local content)', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

  /**
   * Enqeue script for plugins
   */
  function enqueue_block_editor_assets() {
    global $post;

    // If editing plugin post
    $plugin = VF_Plugin::get_plugin($post->post_name);

    wp_register_script(
      'vf-plugin',
      plugins_url(
        '/assets/vf-plugin.js',
        __FILE__
      ),
      array('wp-editor', 'wp-blocks'),
      false,
      true
    );
    wp_localize_script('vf-plugin', 'vfPlugin', array(
      'plugin' => $plugin ? $plugin->config() : null,
      'post_type' => get_post_type()
    ));
    wp_enqueue_script('vf-plugin');
  }

  /**
   * Action: `admin_menu`
   */
  public function admin_menu() {
    add_menu_page(
      __('Content Hub', 'vfwp'),
      __('Content Hub', 'vfwp'),
      'manage_options',
      'vf-settings',
      '',
      'dashicons-admin-settings',
      50
    );
  }

  /**
   * Action `acf/init`
   */
  public function acf_init() {
    if ( ! function_exists('acf_add_options_page')) {
      return;
    }
    // Add options page
    acf_add_options_page(array(
      'menu_title'  => __('Settings', 'vfwp'),
      'menu_slug'   => 'vf-settings',
      'parent_slug' => 'vf-settings',
      'page_title'  => __('Settings', 'vfwp'),
      'capability'  => 'manage_options'
    ));
  }

  /**
   * Return true if current template is a single block or container
   */
  private function is_singular() {
    global $post, $vf_blocks, $vf_containers;
    if ($post instanceof WP_Post &&
      in_array($post->post_type, array(
        $vf_blocks->type(),
        $vf_containers->type()
      )
    )) {
      return true;
    }
    return false;
  }

  /**
   * Return the post type template for blocks and containers
   */
  function single_template($template) {
    if ($this->is_singular()) {
      return plugin_dir_path(__FILE__) . 'single-plugin.php';
    }
    if (is_singular('vf_template')) {
      return plugin_dir_path(__FILE__) . 'single-template.php';
    }
    return $template;
  }

  /**
   * Strip theme classes from blocks and containers single template
   */
  function body_class($classes) {
    if ($this->is_singular()) {
      $classes = array_map(function($class) {
        return strpos($class, 'vf-') === 0 ? '' : $class;
      }, $classes);
    }
    return $classes;
  }

  /**
   * Filter: add load path for ACF json
   */
  function acf_settings_load_json($paths) {
    unset($paths[0]);
    $paths[] = get_template_directory();
    $paths[] = plugin_dir_path(__FILE__);
    return $paths;
  }

  /**
   * Output debug HTML comments around rendered plugin templates
   */
  function plugin_before_render($vf_plugin) {
    echo "\n<!--vf:plugin:{$vf_plugin->post()->post_name}-->\n";
  }

  function plugin_after_render($vf_plugin) {
    echo "\n<!--/vf:plugin:{$vf_plugin->post()->post_name}-->\n";
  }

} // VF_WP

global $vf_wp;

if ( ! isset($vf_wp)) {
  $vf_wp = new VF_WP();
}

?>
