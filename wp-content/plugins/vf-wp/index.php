<?php
/*
Plugin Name: VF-WP
Description: VF-WP theme plugin manager.
Version: 0.1.1
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
require_once('vf-template.php');
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
    global $vf_template;

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

    if ( ! isset($vf_template)) {
      $vf_template = new VF_Template();
      $vf_template->initialize();
    }

    register_activation_hook(
      __FILE__,
      array($this, 'activate')
    );

    register_deactivation_hook(
      __FILE__,
      array($this, 'deactivate')
    );

    add_action(
      'init',
      array($this, 'init')
    );

     add_action('acf/init',
      array($this, 'acf_init')
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

    // Handle templates
    add_filter(
      'single_template',
      array($this, 'single_template')
    );
    add_filter(
      'body_class',
      array($this, 'body_class'),
      30, 1
    );
  }

  /**
   * Trigger activation hooks for custom post type classes
   */
  public function activate() {

    global $vf_cache, $vf_blocks, $vf_containers;

    if ($vf_cache instanceof VF_Cache) {
      $vf_cache->activate();
    }

    if ($vf_blocks instanceof VF_Blocks) {
      $vf_blocks->activate();
    }

    if ($vf_containers instanceof VF_Containers) {
      $vf_containers->activate();
    }
  }

  /**
   * Trigger deactivation hooks for custom post type classes
   */
  public function deactivate() {

    global $vf_cache, $vf_blocks, $vf_containers;

    if ($vf_cache instanceof VF_Cache) {
      $vf_cache->deactivate();
    }

    if ($vf_blocks instanceof VF_Blocks) {
      $vf_blocks->deactivate();
    }

    if ($vf_containers instanceof VF_Containers) {
      $vf_containers->deactivate();
    }
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

  public static function single_template_path() {
    return plugin_dir_path(__FILE__) . 'single-plugin.php';
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
      return VF_WP::single_template_path();
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

  function init() {
    // Enable debug comments
    if ( ! vf_debug()) {
      return;
    }
    add_action(
      'vf/plugin/before_render',
      array($this, 'plugin_before_render'),
      1
    );
    add_action(
      'vf/plugin/after_render',
      array($this, 'plugin_after_render'),
      1
    );
  }

  /**
   * Output debug HTML comments around rendered plugin templates
   */
  function plugin_before_render($vf_plugin) {
    $url = $vf_plugin->api_url();
    echo "\n<!--vf:plugin:{$vf_plugin->post()->post_name}-->\n";
    if ($url) echo "<!--vf:api:{$url}-->\n";
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
