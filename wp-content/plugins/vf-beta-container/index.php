<?php
/*
Deprecated: 0.2.2
Plugin Name: VF-WP Beta (Deprecated)
Description: This plugin is deprecated and can be uninstalled.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Beta extends VF_Plugin {

  public function is_deprecated() {
    return true;
  }

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_beta',
    'post_title' => 'Beta',
    'post_type'  => 'vf_container',
  );

  /**
   * DEPRECATED - do not call constructor
   */
  function __construct(array $params = array()) {
    // Deprecated...
  }

} // VF_Beta

$plugin = new VF_Beta(array('init' => true));

?>
