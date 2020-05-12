<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_WP_Summary') ) :

class VFWP_Summary {

  /**
   * Return the block name
   */
  static public function get_name() {
    return 'vfwp-summary';
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
    add_filter(
      "vf/theme/content/is_block_wrapped/name=acf/{$this->get_name()}",
      array($this, 'is_block_wrapped')
    );
    add_action('admin_head',
      array($this, 'admin_head')
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
      'title'    => 'Summary',
      'category' => 'vf/wp',
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
    if ( ! file_exists($template)) {
      $template = locate_template(
        "blocks/{$this->get_name()}/template.php",
        false, false
      );
    }
    // Otherwise default to the plugin template
    // if ( ! file_exists($template)) {
    //   $template = plugin_dir_path(__FILE__) . 'template.php';
    // }
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

  /**
   * Do not wrap the block in `vf-content`
   */
  public function is_block_wrapped($is_wrap) {
    return false;
  }

  /**
   * Add custom CSS for the WordPress Admin area
   */
  public function admin_head() {
?>
<style>
.wp-block[data-type="acf/<?php echo $this->get_name(); ?>"] {
  max-width: none;
}
</style>
<?php
  }

} // VF_WP_Summary

// Initialize one instance
$vfwp_summary = new VFWP_Summary();

endif; ?>