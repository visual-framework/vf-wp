<?php

/*
Plugin Name: VF-WP Breadcrumbs (Intranet)
Description: VF-WP Intranet theme container for breadcrumbs.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Intranet_Breadcrumbs extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_wp_breadcrumbs_intranet',
    'post_title' => 'Breadcrumbs (Intranet)',
    'post_type'  => 'vf_container'
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_wp_breadcrumbs_intranet');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );

    
  }
    /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $paths[] = plugin_dir_path(__FILE__);
    return $paths;
  }

} // VF_Breadcrumbs_Intranet

$plugin = new VF_Intranet_Breadcrumbs(array('init' => true));

?>
