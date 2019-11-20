<?php
/*
Plugin Name: VF-WP Factoid
Description: VF-WP theme block.
Version: 0.1.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

require_once('widget.php');

class VF_Factoid extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_factoid',
    'post_title' => 'Factoid',
  );

  protected $API = array(
    'pattern'             => 'vf-factoid',
    'filter-content-type' => 'factoid',
    'source'              => 'contenthub',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_factoid');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();
    add_action('widgets_init', array($this, 'widgets_init'));
  }

  public function api_url(array $query_vars = array()) {

    $limit = intval(get_field('vf_factoid_limit', $this->post()->ID));
    $id = trim(get_field('vf_factoid_id', $this->post()->ID));

    // Required vars
    $vars = array(
      'limit' => $limit ? $limit : 1
    );

    if ( ! empty($id)) {
      $vars['filter-id'] = $id;
    }

    return parent::api_url(array_merge($vars, $query_vars));
  }

  /**
   * Register sidebar widget
   */
  public function widgets_init() {
    register_widget('VF_Widget_Factoid');
  }

} // VF_Factoid

$plugin = new VF_Factoid(array('init' => true));




?>
