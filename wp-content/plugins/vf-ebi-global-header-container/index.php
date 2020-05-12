<?php
/*
Plugin Name: VF-WP EBI VF 1.3 Global Header
Description: VF-WP theme global container.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_EBI_Global_Header extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_ebi_global_header',
    'post_title' => 'EBI VF 1.3 Global Header',
    'post_type'  => 'vf_container',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_ebi_global_header');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

} // VF_EBI_Global_Header

$plugin = new VF_EBI_Global_Header(array('init' => true));

?>
