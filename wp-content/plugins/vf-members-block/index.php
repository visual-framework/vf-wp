<?php
/*
Plugin Name: VF-WP Members
Description: VF-WP theme block.
Version: 1.0.0-beta.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

// Include the additional `VF_Person` block
require_once('vf-person/index.php');

class VF_Members extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_members',
    'post_title' => 'Members',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  function __construct(array $params = array()) {
    parent::__construct('vf_members');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

} // VF_Members

$plugin = new VF_Members(array('init' => true));

?>
