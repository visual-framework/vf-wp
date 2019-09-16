<?php
/*
Plugin Name: VF-WP Global Header
Description: VF-WP theme global container.
Version: 0.1.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Global_Header extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'node-body',
    'filter-content-type' => 'article',
    'source'              => 'contenthub',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_global_header');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_global_header',
        'post_title' => 'Global Header',
        'post_type'  => 'vf_container'
      )
    );
  }

  function api_url(array $query_vars = array()) {
    $id = intval(get_field('vf_global_header_node_id', $this->post->ID));
    $vars = array(
      'filter-id' => $id ? $id : 574
    );
    return parent::api_url(array_merge($vars, $query_vars));
  }

} // VF_Global_Header

$plugin = new VF_Global_Header(array('init' => true));

?>
