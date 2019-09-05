<?php
/*
Plugin Name: VF-WP
Description: VF-WP theme plugin manager.
Version: 0.1.0
Author: EMBL-EBI Web Development
Plugin URI: https://git.embl.de/grp-stratcom/vf-wp
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
require_once('vf-admin.php');

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

    global $vf_cache, $vf_blocks, $vf_containers;

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

    register_activation_hook(__FILE__, array($this, 'activate'));
    register_deactivation_hook(__FILE__, array($this, 'deactivate'));

    add_action(
      'init',
      array($this, 'init')
    );

    // ACF load and save setup - saving only useful for development
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json'),
      1
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
    if (vf_debug()) {
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

  if (is_admin()) {
    $vf_admin = new VF_Admin();
  }
}

?>
