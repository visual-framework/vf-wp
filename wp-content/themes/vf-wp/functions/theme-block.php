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
    /*
    add_filter('acf/load_fields',
      array($this, 'acf_load_fields'),
      10, 2
    );
    */
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
    // Add "Full-width Layout" settings for container blocks
    if ($this->is_containerable()) {
      acf_add_local_field_group(array(
        'key'    => "group_5ec3be037f084",
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
    $acf_id = VF_Theme_Content::get_acf_block_ID($blocks[$i]);
    if ($acf_id) {
      $is_container = get_field('is_container', $acf_id);
      if ($is_container === null) {
        $is_container = true;
      }
      return ! (bool) $is_container;
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

  /**
   * Filter: `acf/load_fields`
   */
  /*
  function acf_load_fields($fields, $field_group) {
    // Setup a fake `$screen` to match the block
    $screen = acf_get_location_screen();
    $screen['block'] = "acf/{$this->get_name()}";
    $is_match = false;
    // Check if a solo location rule matches the block
    foreach ($field_group['location'] as $rules) {
      if (empty($rules) || count($rules) !== 1) {
        continue;
      }
      $is_match = acf_match_location_rule($rules[0], $screen, $field_group);
      if ($is_match) {
        break;
      }
    }
    if ( ! $is_match) {
      return $fields;
    }
    return $fields;
  }
  */

} // VFWP_Block


endif;

?>
