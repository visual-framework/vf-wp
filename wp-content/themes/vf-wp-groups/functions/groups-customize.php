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
    add_action(
      'wp_head',
      array($this, 'wp_head')
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
    // Theme color
    $wp_customize->add_setting('vf_theme_color', array(
      'default'           => array_keys($this->theme_colors)[0],
      'sanitize_callback' => array($this, 'admin_customize_sanitize'),
    ));
    $wp_customize->add_control('vf_theme_color', array(
      'type'        => 'select',
      'section'     => 'vf_theme',
      'label'       => __('Theme Color', 'vfwp'),
      'description' => __('Used for the hero background and other design accents.', 'vfwp'),
      'choices'     => $this->theme_colors,
    ));
  }

  /**
   * Output custom inline <head> stuff
   */
  public function wp_head() {
    $theme_color = get_theme_mod(
      'vf_theme_color',
      array_keys($this->theme_colors)[0]
    );
  ?>
<style>
.vf-wp-theme .vf-masthead,
.vf-wp-theme .vf-box--secondary {
  --vf-masthead__color--background: #<?php echo $theme_color; ?>;
}
</style>
  <?php
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
