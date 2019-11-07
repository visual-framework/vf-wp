<?php
/*
Plugin Name: VF-WP Beta
Description: VF-WP theme global container.
Version: 0.2.0
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Beta extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'node-body',
    'filter-content-type' => 'article',
    'source'              => 'contenthub',
  );

  private $rendered = false;

  function __construct(array $params = array()) {
    parent::__construct('vf_beta');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_beta',
        'post_title' => 'Beta',
        'post_type'  => 'vf_container'
      )
    );

    add_action(
      'vf/container/after_render/post_name=vf_global_header',
      array($this, 'after_vf_breadcrumbs')
    );

    add_action(
      'vf/container/after_render/post_name=vf_ebi_global_header',
      array($this, 'after_vf_breadcrumbs')
    );
  }

  function api_url(array $query_vars = array()) {
    $id = intval(get_field('vf_beta_node_id', $this->post->ID));
    $vars = array(
      'filter-id' => $id ? $id : 580
    );
    return parent::api_url(array_merge($vars, $query_vars));
  }

  /**
   * Action: `vf/plugin/after_render/post_name=vf_breadcrumbs`
   * Render this plugin once after `vf_breadcrumbs` on the homepage
   */
  function after_vf_breadcrumbs($vf_plugin) {
    if ( ! $this->rendered && is_front_page()) {
      VF_Plugin::render($this);
      $this->rendered = true;
    }
  }

} // VF_Beta

$plugin = new VF_Beta(array('init' => true));

?>
