<?php
/*
Plugin Name: VF-WP Data resources
Description: VF-WP theme block.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Data_resources extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_data_resources',
    'post_title' => 'Data resources',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  public function __construct(array $params = array()) {
    parent::__construct('vf_data_resources');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

} // VF_Data_resources

$plugin = new VF_Data_resources(array('init' => true));

?>
