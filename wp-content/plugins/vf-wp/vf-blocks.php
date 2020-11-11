<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Blocks') ) :

/**
 * Represent a custom post type for Visual Framework blocks
 */
class VF_Blocks extends VF_Type {

  public function initialize() {
    parent::initialize();
    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );
    add_action('after_setup_theme',
      array($this, 'after_setup_theme'),
      20
    );
    add_filter(
      'acf/pre_render_fields',
      array($this, 'acf_pre_render_fields'),
      10, 2
    );
  }

  public function activate() {
    parent::activate();
  }

  static public function post_type() {
    return 'vf_block';
  }

  static public function block_category() {
    return 'vf/blocks';
  }

  /**
   * Action: `block_categories`
   */
  static public function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => VF_Blocks::block_category(),
          'title' => __('EMBL – Content Hub', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

  /**
   * Convert a Gutenberg block name to a VF_Plugin post name
   * e.g. `acf/vf-latest-posts` to `vf_latest_posts`
   * e.g. `vf/latest-posts` to `vf_latest_posts` (legacy support)
   */
  static function name_block_to_post($str, $separator = '_') {
    $str = str_replace('acf/', '', $str);
    $str = preg_replace('/[^\w]/', $separator, $str);
    return $str;
  }

  /**
   * Convert a VF_Plugin post name to a Gutenberg block name
   * e.g. `vf_latest_posts` to `acf/vf-latest-posts`
   */
  static function name_post_to_block($str, $prefix = 'acf/', $separator = '-') {
    $str = preg_replace('/[\W_]/', $separator, $str);
    return "{$prefix}$str";
  }

  /**
   * Action: `after_setup_theme`
   */
  public function after_setup_theme() {
    global $vf_plugins;
    if ( ! is_array($vf_plugins)) {
      return;
    }

    // Iterate over registered block plugins
    foreach ($vf_plugins as $key => $config) {
      if ($config['post_type'] !== $this->type()) {
        continue;
      }

      // Skip unknown or deprecated plugins
      $plugin = VF_Plugin::get_plugin($key);
      if ( ! $plugin || $plugin->is_deprecated()) {
        continue;
      }

      // Setup render callback using VF Gutenberg plugin or fallback
      $callback = function() use ($plugin) {
        $args = func_get_args();
        $acf_id = $plugin->post()->ID;
        $template = $plugin->template();
        $block = $args[0];
        if ( ! get_field('defaults', $block['id'])) {
          $acf_id = $block['id'];
        }
        if (get_field('is_plugin', $block['id']) === 1) {
          $plugin = VF_Blocks::name_block_to_post($block['name']);
          $plugin = VF_Plugin::get_plugin($plugin);
          if ($plugin) {
            acf_reset_meta($block['id']);
            $template = function($args) use ($plugin) {
              VF_Plugin::render($plugin);
            };
          }
        }
        if (class_exists('VF_Gutenberg')) {
          VF_Gutenberg::acf_render_template($args, $template, $acf_id);
        } else {
          include($template);
        }
      };

      // Register the Gutenberg block with ACF
      acf_register_block_type(array_merge(
        array(
          'name'     => VF_Blocks::name_post_to_block($key, ''),
          'title'    => $plugin->post()->post_title,
          'category' => VF_Blocks::block_category(),
          'supports' => array(
            'align'           => false,
            'customClassName' => false
          )
        ),
        array(
          'render_callback' => $callback
        )
      ));

      acf_add_local_field_group(array(
        'key' => "group_{$key}_settings",
        'title' => __('Block Settings', 'vfwp'),
        'fields' => array(
          array(
            'key' => 'field_defaults',
            'label' => __('Use Defaults', 'vfwp'),
            'name' => 'defaults',
            'type' => 'true_false',
            'instructions' => __('Disable custom settings and use the block defaults.', 'vfwp'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'ui' => 1,
            'ui_on_text' => '',
            'ui_off_text' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'block',
              'operator' => '==',
              'value' => 'acf/' . str_replace('_', '-', $key),
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
      ));
    }
  }

  /**
   * Filter: `acf/pre_render_fields`
   */
  public function acf_pre_render_fields($fields, $post_id) {
    // Return if not Gutenberg block
    if ( ! preg_match('/^block_/', $post_id)) {
      return $fields;
    }
    // Return if field group is empty
    $fields = array_filter($fields);
    if (empty($fields)) {
      return $fields;
    }
    // Return if "Defaults" toggle has not been prepended
    if ($fields[0]['key'] !== 'field_defaults') {
      return $fields;
    }
    // Iterate over fields
    foreach ($fields as $i => $field) {
      if ($field['key'] === 'field_defaults') {
        continue;
      }
      // Create conditional logic array if missing
      $logic = $field['conditional_logic'];
      if ( ! is_array($logic)) {
        $logic = array(array());
      }
      // Prepend "AND" condition to all logic clauses
      foreach ($logic as $k => $condition) {
        if ( ! is_array($condition)) {
          continue;
        }
        array_unshift($condition, array(
          'field'    => 'field_defaults',
          'operator' => '==',
          'value'    => '0'
        ));
        $logic[$k] = $condition;
      }
      $fields[$i]['conditional_logic'] = $logic;
    }
    return $fields;
  }

} // VF_Blocks

endif;

?>
