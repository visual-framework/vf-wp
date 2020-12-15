<?php
/*
Plugin Name: VF-WP EBI VF 1.3 Global Footer
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

class VF_EBI_Global_Footer extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_ebi_global_footer',
    'post_title' => 'EBI VF 1.3 Global Footer',
    'post_type'  => 'vf_container',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  public function __construct(array $params = array()) {
    parent::__construct('vf_ebi_global_footer');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();

    add_action(
      'wp_enqueue_scripts',
      array($this, 'wp_enqueue_scripts'),
      10
    );

    // Ideally this should go in some sort of "EBI CSS+JS plugin" along with
    // the v1.3 CSS+JS
    add_filter('body_class',
      array($this, 'body_class')
    );
  }

  /**
   * Return true if the current page uses the EBI Header or Footer
   */
  private function is_ebi_template() {
    global $post;
    $post = get_post($post);
    // Return true if admin is previewing single container
    if ($post && $post->post_type === 'vf_container') {
      if (in_array($post->post_name, array(
        'vf_ebi_global_header',
        'vf_ebi_global_footer'
      ))) {
        return true;
      }
    }
    global $vf_templates;
    if ( ! $vf_templates instanceof VF_Templates) {
      return false;
    }
    if ($post && $post->post_type === 'vf_template') {
      // Get containers if admin is previewing single template
      $containers = $vf_templates->get_template_plugins($post);
    } else {
      // Get containers for normal pages
      $containers = $vf_templates->get_template_containers();
    }
    if ( ! is_array($containers) || empty($containers)) {
      return false;
    }
    return (
      in_array('vf_ebi_global_header', $containers) ||
      in_array('vf_ebi_global_footer', $containers)
    );
  }

  /**
   * Filter: `body_class`
   */
  public function body_class($classes) {
    // enable the VF 1.x workarounds
    if ($this->is_ebi_template()) {
      $classes[] = 'ebi-vf1-integration';
    }
    return $classes;
  }

  // We load these scripts here as a short term solution to not over-architect
  // something that shouldn't be permananent.
  // This also means that for the EBI Header to function, the EBI Footer must
  // also be used.
  public function wp_enqueue_scripts() {
    if ( ! $this->is_ebi_template()) {
      return;
    }
    wp_enqueue_script(
      'ebi-framework--defer',
      'https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/js/script.js',
      false,
      'v1.3',
      true
    );
    // wp_enqueue_style(
    //   'ebi-global',
    //   'https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/css/ebi-global.css',
    //   array(),
    //   'v1.3',
    //   'all'
    // );
    // The ebi-header footer is actually passed by the contentHub in
    // https://content.embl.org/node/6682/edit
    // However it fails to cache; see https://github.com/visual-framework/vf-wp/issues/606
    wp_enqueue_style(
      'ebi-header',
      'https://assets.emblstatic.net/vf/v2.4.0/assets/ebi-header-footer/ebi-header-footer.css',
      array(),
      'v2.4',
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
