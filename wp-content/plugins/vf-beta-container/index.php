<?php
/*
Deprecated: 0.2.2
Plugin Name: VF-WP Beta (Deprecated)
Description: This plugin is deprecated and can be uninstalled.
Version: 1.0.0-beta.1
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

  protected $API = array(
    'pattern'             => 'node-body',
    'filter-content-type' => 'article',
    'source'              => 'contenthub',
  );

  private $rendered = false;

  /**
   * DEPRECATED - do not call constructor
   */
  function __construct(array $params = array()) {
    // parent::__construct('vf_beta');
    // if (array_key_exists('init', $params)) {
    //   $this->init();
    // }
  }

  /**
   * DEPRECATED - do not initialize
   */
  private function init() {
    parent::initialize();

    add_action(
      'vf/plugin/container/after_render/vf_global_header',
      array($this, 'after_header_container')
    );

    add_action(
      'vf/plugin/container/after_render/vf_ebi_global_header',
      array($this, 'after_header_container')
    );
  }

  function api_url(array $query_vars = array()) {
    $id = intval(get_field('vf_beta_node_id', $this->post()->ID));
    $vars = array(
      'filter-id' => $id ? $id : 580
    );
    return parent::api_url(array_merge($vars, $query_vars));
  }

  /**
   * Action: `vf/plugin/container/after_render/*`
   * Render this plugin once after header container on the homepage
   */
  function after_header_container($vf_plugin) {
    if ( ! $this->rendered && is_front_page()) {
      VF_Plugin::render($this);
      $this->rendered = true;
    }
  }

} // VF_Beta

$plugin = new VF_Beta(array('init' => true));

?>
