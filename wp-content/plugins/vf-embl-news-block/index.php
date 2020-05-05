<?php
/*
Plugin Name: VF-WP EMBL News (Gutenberg block)
Description: VF-WP theme Gutenberg block.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_EMBL_News_Block') ) :

class VF_EMBL_News_Block {

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vf-embl-news';
  }

  /**
   * Constructor - add hooks
   */
  public function __construct() {
    add_action('acf/init',
      array($this, 'acf_init')
    );
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
  }

  /**
   * Return Gutenberg block registration configuration
   * https://www.advancedcustomfields.com/resources/acf_register_block_type/
   * https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
   */
  public function get_config() {
    return array(
      'name'     => $this->get_name(),
      'title'    => __('EMBL News', 'vfwp'),
      'category' => VF_Blocks::block_category(),
      'supports' => array(
        'align'           => false,
        'customClassName' => false
      )
    );
  }

  /**
   * Return block render template path
   */
  public function get_template() {
    // Allow themes to provide a custom template
    $template = locate_template(
      "blocks/{$this->get_name()}.php",
      false, false
    );
    // Otherwise default to the plugin template
    if ( ! file_exists($template)) {
      $template = plugin_dir_path(__FILE__) . 'template.php';
    }
    return $template;
  }

  /**
   * Action: `acf/init`
   */
  public function acf_init() {
    // Setup render callback using VF Gutenberg plugin or fallback
    $callback = function() {
      $args = func_get_args();
      $template = $this->get_template();
      if (class_exists('VF_Gutenberg')) {
        VF_Gutenberg::acf_render_template($args, $template);
      } else {
        $block = $args[0];
        include($template);
      }
    };
    // Register the Gutenberg block with ACF
    acf_register_block_type(array_merge(
      $this->get_config(),
      array(
        'render_callback' => $callback
      )
    ));
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $paths[] = plugin_dir_path(__FILE__);
    return $paths;
  }

} // VF_EMBL_News_Block

// Initialize one instance
$vf_embl_news_block = new VF_EMBL_News_Block();

endif;

?>
