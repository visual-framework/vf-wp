<?php
/*
Plugin Name: VF-WP Banner
Description: VF-WP theme global container.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Banner extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_banner',
    'post_title' => 'Banner',
    'post_type'  => 'vf_container',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_banner');
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

} // VF_Banner

$plugin = new VF_Banner(array('init' => true));

?>
