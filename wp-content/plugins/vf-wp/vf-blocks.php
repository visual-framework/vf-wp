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
      11
    );
  }

  public function activate() {
    parent::activate();
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
   * Action: `after_setup_theme`
   */
  public function after_setup_theme() {
    global $vf_plugins;

    foreach ($vf_plugins as $key => $plugin) {
      if ($plugin['post_type'] !== $this->type()) {
        continue;
      }
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

} // VF_Blocks

endif;

?>
