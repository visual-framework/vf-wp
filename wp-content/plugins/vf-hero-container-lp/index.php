<?php
/*
Plugin Name: VF-WP Hero Landing Page
Description: VF-WP Hero Landing Page container.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_WP_Hero_LP extends VF_Plugin {

  const MAX_WIDTH = 1224;
  const MAX_HEIGHT = 348;

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_wp_hero_lp',
    'post_title' => 'VF Hero (landing-page)',
    'post_type'  => 'vf_container'
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_wp_hero_lp');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    };
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );

  }

  // public function template_callback($block, $content, $is_preview = false, $acf_id) {
  //   include(plugin_dir_path(__FILE__) . 'template.php');
  // }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $paths[] = plugin_dir_path(__FILE__);
    return $paths;
  }

} // VF_WP_Hero_LP


$plugin = new VF_WP_Hero_LP(
  array('init' => true)
);

?>
