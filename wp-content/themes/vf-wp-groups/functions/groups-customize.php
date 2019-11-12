<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Groups_Customize') ) :

class VF_Groups_Customize {

  private $theme_colors;

  public function __construct() {
    // Add hooks
    add_action(
      'vf/__experimental__/admin/customize',
      array($this, 'admin_customize')
    );
    add_filter(
      'vf/__experimental__/admin/customize/theme_colors',
      array($this, 'customize_theme_colors'),
      9, 1
    );
    // Setup defaults
    $this->theme_colors = VF_Theme::apply_filters(
      'vf/admin/customize/theme_colors',
      array()
    );
  }

  /**
   * Filter: `vf/admin/customize/theme_colors`
   * Add default color list
   */
  public function customize_theme_colors($colors) {
    $colors = array_merge($colors, array(
      '009f4d' => __('EMBL Green', 'vfwp'),
      '007c80' => __('EMBL-EBI Petrol', 'vfwp'),
    ));
    return array_unique($colors);
  }

  /**
   * Admin Customize
   * Action: `vf/admin/customize`
   */
  public function admin_customize($wp_customize) {
    // Add setting
    $wp_customize->add_setting('vf_theme_color', array(
      'capability' => 'edit_theme_options',
      'sanitize_callback' => array($this, 'admin_customize_sanitize'),
      'default' => array_keys($this->theme_colors)[0],
    ));
    // Add control
    $wp_customize->add_control('vf_theme_color', array(
      'type' => 'select',
      'section' => 'vf_theme',
      'label' => __('Theme Color', 'vfwp'),
      'description' => __('Used for the header background and other design accents.', 'vfwp'),
      'choices' => $this->theme_colors,
    ));
  }

  /**
   * Admin Customize: `sanitize_callback` for select values
   */
  public function admin_customize_sanitize($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (
      array_key_exists($input, $choices)
      ? $input
      : $setting->default
    );
  }

} // VF_Groups_Customize

endif;

?>
