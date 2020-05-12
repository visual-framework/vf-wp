<?php
/*
Plugin Name: VF-WP Publications Group EBI
Description: Query for team publications based of EMBL-EBI specific data source (EBI Content Database).
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Publications_Group_EBI extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_publications_group_ebi',
    'post_title' => 'EBI Team publications',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  public function __construct(array $params = array()) {
    parent::__construct('vf_publications_group_ebi');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

} // VF_Publications_Group_EBI

$plugin = new VF_Publications_Group_EBI(array('init' => true));

?>
