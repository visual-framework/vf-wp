<?php
/*
Plugin Name: VF-WP EMBL News
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

class VF_EMBL_News extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_embl_news',
    'post_title' => 'EMBL News',
    'post_type'  => 'vf_container',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  function __construct(array $params = array()) {
    parent::__construct('vf_embl_news');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();
    add_action('init', array($this, 'add_taxonomy_fields'), 11);
  }

  /**
   * Action: `init`
   * Cannot run on `acf/init` because taxonomy is not registered
   */
  function add_taxonomy_fields() {

    if (function_exists('embl_taxonomy')) {
      acf_add_local_field(
        array(
          'parent' => 'group_vf_embl_news',
          'key' => 'field_vf_embl_news_term',
          'label' => __('EMBL Taxonomy', 'vfwp'),
          'name' => 'vf_embl_news_term',
          'type' => 'taxonomy',
          'instructions' => __('Filter articles by this term – takes priority over <b>Keyword</b>.', 'vfwp'),
          'required' => 0,
          'wrapper' => array(
            'width' => '50',
            'class' => '',
            'id' => '',
          ),
          'taxonomy' => 'embl_taxonomy',
          'field_type' => 'select',
          'allow_null' => 1,
          'add_term' => 0,
          'save_terms' => 0,
          'load_terms' => 0,
          'return_format' => 'id',
          'multiple' => 0,
        )
      );
    }
    
  }

} // VF_EMBL_News

$plugin = new VF_EMBL_News(array('init' => true));

?>
