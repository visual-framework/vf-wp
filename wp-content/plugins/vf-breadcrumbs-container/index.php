<?php
/*
Plugin Name: VF-WP Breadcrumbs
Description: VF-WP theme global container.
Version: 1.0.0-beta.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Breadcrumbs extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_breadcrumbs',
    'post_title' => 'Breadcrumbs',
    'post_type'  => 'vf_container',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_breadcrumbs');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

  // public function template_callback($block, $content, $is_preview = false, $acf_id) {
  //   include(plugin_dir_path(__FILE__) . 'template.php');
  // }

} // VF_Breadcrumbs

$plugin = new VF_Breadcrumbs(array('init' => true));

?>
