<?php
/*
Plugin Name: VF-WP EBI VF 1.3 Global Footer
Description: VF-WP theme global container.
Version: 0.1.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_EBI_Global_Footer extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'node-body',
    'filter-content-type' => 'article',
    'source'              => 'contenthub',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_ebi_global_footer');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_ebi_global_footer',
        'post_title' => 'EBI VF 1.3 Global Footer',
        'post_type'  => 'vf_container'
      )
    );
    add_action(
      'wp_enqueue_scripts',
      array($this, 'enqueue_assets'),
      10
    );

    // Ideally this should go in some sort of "EBI CSS+JS plugin" along with
    // the v1.3 CSS+JS
    add_filter('body_class', 'vf_ebi_global_footer__body_class');

    function vf_ebi_global_footer__body_class($classes) {
      $classes[] = 'ebi-vf1-integration'; // enable the VF 1.x workarounds
      return $classes;
    }

  }

  function api_url(array $query_vars = array()) {
    $id = intval(get_field('vf_ebi_global_footer_node_id', $this->post->ID));
    $vars = array(
      'filter-id' => $id ? $id : 6683
    );
    return parent::api_url(array_merge($vars, $query_vars));
  }

  // We load these scripts here as a short term solution to not over-architect
  // something that shouldn't be permananent.
  // This also means that for the EBI Header to function, the EBI Footer must
  // also be used.
  function enqueue_assets() {
    wp_enqueue_script(
      'ebi-framework--defer',
      'https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/js/script.js',
      false,
      'v1.3',
      true
    );
    wp_enqueue_style(
      'ebi-global',
      'https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/css/ebi-global.css',
      array(),
      'v1.3',
      'all'
    );
    wp_enqueue_style(
      'ebi-fonts',
      'https://dev.ebi.emblstatic.net/web_guidelines/EBI-Icon-fonts/v1.3/fonts.css',
      array(),
      'v1.3',
      'all'
    );
  }

} // VF_EBI_Global_Footer

$plugin = new VF_EBI_Global_Footer(array('init' => true));

?>
