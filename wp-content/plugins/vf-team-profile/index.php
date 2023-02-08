<?php
/*
Plugin Name: VF-WP Team profile
Description: VF-WP theme block.
Version: 1.0.0-beta.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Team_Profile') ) :

class VF_Team_Profile {

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vf-team-profile';
  }

  /**
   * Constructor - add hooks
   */
  public function __construct() {
    add_action('after_setup_theme',
      array($this, 'after_setup_theme')
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
    $category = 'vf/wp';
    if (class_exists('VF_Blocks')) {
      $category = VF_Blocks::block_category();
    }
    return array(
      'name'     => $this->get_name(),
      'title'    => __('Team profile', 'vfwp'),
      'category' => $category,
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
   * Action: `after_setup_theme`
   */
  public function after_setup_theme() {
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

} // VF_Team_Profile

// Initialize one instance
$vf_team_profile = new VF_Team_Profile();

endif;

?>
