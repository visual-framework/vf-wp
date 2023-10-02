<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Block') ) :

class VFWP_Block {

  private $file = null;
  private $is_containerable = false;

  public function __construct($file) {
    $this->file = $file;

    // Add hooks
    add_action('acf/init',
      array($this, 'acf_init')
    );
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
    add_filter(
      "vf/theme/content/is_block_wrapped/name=acf/{$this->get_name()}",
      array($this, 'is_block_wrapped'),
      10, 4
    );
  }

  /**
   * Return true if this block has container layout
   */
  public function is_containerable() {
    return (bool) $this->is_containerable;
  }

  public function setup_containerable() {
    $this->is_containerable = true;
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
    $config = $this->get_config();
    if (isset($config['supports']['vf/innerBlocks'])) {
      $config['supports']['jsx'] = boolval($config['supports']['vf/innerBlocks']);
    }
    // Setup render callback using VF Gutenberg plugin or fallback
    $callback = function() use ($config) {
      $args = func_get_args();
      $template = $this->get_template();
      // Render block in iFrame by default if plugin exists
      $is_iframe = class_exists('VF_Gutenberg');
      // Disable iFrame render if support is disabled
      if (
        isset($config['supports']['vf/renderIFrame']) &&
        $config['supports']['vf/renderIFrame'] === false
      ) {
        $is_iframe = false;
      }
      if ($is_iframe) {
        VF_Gutenberg::acf_render_template($args, $template);
      } else {
        $block = $args[0];
        $is_preview = $args[2];
        include($template);
      }
    };
    // Register the Gutenberg block with ACF
    acf_register_block_type(array_merge(
      $config,
      array(
        'render_callback' => $callback
      )
    ));
    // Add "Full-width Layout" settings for container blocks
    if ($this->is_containerable()) {
      acf_add_local_field_group(array(
        'key'    => uniqid('group_'),
        'title'  => __('Block Settings', 'vfwp'),
        'fields' => array(
          array(
            'key'           => 'field_5ec3be037f09c',
            'label'         => __('Full-width Layout', 'vfwp'),
            'name'          => 'is_container',
            'type'          => 'true_false',
            'instructions'  => __('Display using a full-width layout on supported templates.', 'vfwp'),
            'default_value' => 1,
            'ui'            => 1
          ),
        ),
        'location' => array(
          array(
            array(
              'param'    => 'block',
              'operator' => '==',
              'value'    => "acf/{$this->get_name()}",
            ),
          ),
        ),
        'menu_order' => 100,
      ));
    }
  }

  /**
   * Filter whether to wrap block in `vf-content`
   */
  public function is_block_wrapped($is_wrap, $block_name, $blocks, $i) {
    // Always wrapped if container layout is not supported
    if ( ! $this->is_containerable()) {
      return true;
    }
    // Check block attributes for container toggle
    if (strpos($blocks[$i], '<!--(is_container)-->') !== false) {
      return false;
    }
    return true;
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    if ( ! empty($this->file)) {
      $paths[] = plugin_dir_path($this->file);
    }
    return $paths;
  }

} // VFWP_Block


endif;

?>
