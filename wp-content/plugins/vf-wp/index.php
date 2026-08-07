<?php
/*
Plugin Name: VF-WP
Description: VF-WP theme plugin manager.
Version: 1.0.0-beta.7
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
      'block_categories_all',
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
      'wp_ajax_vf/plugin/preview_fields',
      array($this, 'ajax_plugin_preview_fields')
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
  function block_categories( $categories, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {
      array_push(
        $categories,
        array(
          'slug'  => 'vf/wp',
          'title' => __('EMBL – WordPress (local content)', 'vfwp'),
          'icon'  => null )
      );
    }
    return $categories;
  }

  /**
   * Enqeue script for plugins
   */
  function enqueue_block_editor_assets() {
    global $post;

    // If editing plugin post
    $plugin = $post ? VF_Plugin::get_plugin($post->post_name) : null;
    $post_id = $post instanceof WP_Post ? $post->ID : 0;
    $script_path = plugin_dir_path(__FILE__) . 'assets/vf-plugin.js';
    $preview_ref = null;

    if ($post instanceof WP_Post) {
      if (
        class_exists('VF_Blocks') &&
        $post->post_type === VF_Blocks::post_type()
      ) {
        $preview_ref = VF_Blocks::name_post_to_block($post->post_name);
      } elseif (
        class_exists('VF_Containers') &&
        $post->post_type === VF_Containers::post_type()
      ) {
        $preview_ref = VF_Containers::name_post_to_block($post->post_name);
      }
    }

    wp_register_script(
      'vf-plugin',
      plugins_url(
        '/assets/vf-plugin.js',
        __FILE__
      ),
      array('wp-editor', 'wp-blocks', 'wp-data', 'wp-block-editor'),
      file_exists($script_path) ? filemtime($script_path) : false,
      true
    );
    wp_localize_script('vf-plugin', 'vfPlugin', array(
      'plugin' => $plugin ? $plugin->config() : null,
      'post_type' => get_post_type(),
      'post_id' => $post_id,
      'preview_ref' => $preview_ref,
      'preview_nonce' => wp_create_nonce("vf_plugin_preview_{$post_id}")
    ));
    wp_enqueue_script('vf-plugin');
  }

  /**
   * Store unsaved ACF field values so the new-tab preview can render them.
   */
  function ajax_plugin_preview_fields() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    if (
      ! $post_id ||
      ! wp_verify_nonce($nonce, "vf_plugin_preview_{$post_id}") ||
      ! current_user_can('edit_post', $post_id)
    ) {
      wp_send_json_error();
    }

    $post_type = get_post_type($post_id);
    if ( ! in_array($post_type, array('vf_block', 'vf_container'), true)) {
      wp_send_json_error();
    }

    $fields = isset($_POST['fields']) && is_array($_POST['fields'])
      ? wp_unslash($_POST['fields'])
      : array();

    set_transient(
      self::plugin_preview_fields_key($post_id),
      $fields,
      15 * MINUTE_IN_SECONDS
    );

    wp_send_json_success();
  }

  static public function plugin_preview_fields_key($post_id, $user_id = 0) {
    if ( ! $user_id) {
      $user_id = get_current_user_id();
    }
    return "vf_plugin_preview_fields_{$user_id}_{$post_id}";
  }

  static public function get_plugin_preview_fields($post_id) {
    $fields = get_transient(self::plugin_preview_fields_key($post_id));
    return is_array($fields) ? $fields : null;
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
