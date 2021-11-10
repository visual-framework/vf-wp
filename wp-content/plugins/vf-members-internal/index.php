<?php
/*
Plugin Name: VF-WP Members (internal)
Description: VF-WP theme block. Use only to display EMBL staff on VF-WP Intranet theme. 
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
require_once('vf-person-internal/index.php');

class VF_Members_Internal extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_members_internal',
    'post_title' => 'Members (Internal)',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  function __construct(array $params = array()) {
    parent::__construct('vf_members_internal');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

} // VF_Members

$plugin = new VF_Members_Internal(array('init' => true));

?>
