<?php
/*
Plugin Name: VF-WP Masthead
Description: VF-WP Masthead container.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Masthead extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_masthead',
    'post_title' => 'Masthead',
    'post_type'  => 'vf_container',
    // Allow block to be previewed in WP admin
    '__experimental__has_admin_preview' => true
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_masthead');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

  // public function template_callback($block, $content, $is_preview = false, $acf_id) {
  //   include(plugin_dir_path(__FILE__) . 'template.php');
  // }

} // VF_Masthead

$plugin = new VF_Masthead(array('init' => true));

?>
