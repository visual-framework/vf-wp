<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Groups_Customize') ) :

class VF_Groups_Customize {

  private $themes;
  private $theme_layouts;
  private $theme_colors;

  public function __construct() {
    // Add hooks
    add_action(
      'vf/__experimental__/admin/customize',
      array($this, 'admin_customize')
    );
    add_filter(
      'vf/__experimental__/admin/customize/themes',
      array($this, 'customize_themes'),
      9, 1
    );
    add_filter(
      'vf/__experimental__/admin/customize/theme_layouts',
      array($this, 'customize_theme_layouts'),
      9, 1
    );
    /**
     * @deprecated https://github.com/visual-framework/vf-wp/issues/141
     *
    add_filter(
      'vf/__experimental__/admin/customize/theme_colors',
      array($this, 'customize_theme_colors'),
      9, 1
    );
    */
    add_filter(
      'body_class',
      array($this, 'body_class')
    );
    /**
     * @deprecated https://github.com/visual-framework/vf-wp/issues/141
     *
    add_action(
      'wp_head',
      array($this, 'wp_head')
    );
    */
    // Setup defaults
    $this->themes = VF_Theme::apply_filters(
      'vf/admin/customize/themes',
      array()
    );
    $this->theme_layouts = VF_Theme::apply_filters(
      'vf/admin/customize/theme_layouts',
      array()
    );
    /**
     * @deprecated https://github.com/visual-framework/vf-wp/issues/141
     *
    $this->theme_colors = VF_Theme::apply_filters(
      'vf/admin/customize/theme_colors',
      array()
    );
    */
  }

  /**
   * Filter: `vf/admin/customize/themes`
   * Add default themes list
   */
  public function customize_themes($themes) {
    $themes = array_merge($themes, array(
      'primary'   => __('Primary', 'vfwp'),
      'secondary' => __('Secondary', 'vfwp'),
      'tertiary'  => __('Tertiary', 'vfwp'),
    ));
    return array_unique($themes);
  }

  /**
   * Filter: `vf/admin/customize/theme_layouts`
   * Add default layouts list
   */
  public function customize_theme_layouts($layouts) {
    $layouts = array_merge($layouts, array(
      'very-easy' => __('Very Easy', 'vfwp'),
      'easy'      => __('Easy', 'vfwp'),
      'normal'    => __('Normal', 'vfwp'),
      'hard'      => __('Hard', 'vfwp'),
      'extreme'   => __('Extreme', 'vfwp'),
    ));
    return array_unique($layouts);
  }

  /**
   * Filter: `vf/admin/customize/theme_colors`
   * Add default color list
   * @deprecated https://github.com/visual-framework/vf-wp/issues/141
   */
  /*
  public function customize_theme_colors($colors) {
    $colors = array_merge($colors, array(
      '009f4d' => __('EMBL Green', 'vfwp'),
      '007c80' => __('EMBL-EBI Petrol', 'vfwp'),
    ));
    return array_unique($colors);
  }
  */

  /**
   * Admin Customize
   * Action: `vf/admin/customize`
   */
  public function admin_customize($wp_customize) {
    // Global theme
    $wp_customize->add_setting('vf_theme', array(
      'default'           => array_keys($this->themes)[0],
      'sanitize_callback' => array($this, 'admin_customize_sanitize'),
    ));
    $wp_customize->add_control('vf_theme', array(
      'type'        => 'select',
      'section'     => 'vf_theme',
      'label'       => __('Theme', 'vfwp'),
      'description' => __('Used for general design variations.', 'vfwp'),
      'choices'     => $this->themes,
    ));
    // Theme layout
    $wp_customize->add_setting('vf_theme_layout', array(
      'default'           => array_keys($this->theme_layouts)[0],
      'sanitize_callback' => array($this, 'admin_customize_sanitize'),
    ));
    $wp_customize->add_control('vf_theme_layout', array(
      'type'        => 'select',
      'section'     => 'vf_theme',
      'label'       => __('Theme Layout', 'vfwp'),
      'description' => __('Used for general design variations.', 'vfwp'),
      'choices'     => $this->theme_layouts,
    ));
    /**
     * @deprecated https://github.com/visual-framework/vf-wp/issues/141
     *
    // Theme color
    $wp_customize->add_setting('vf_theme_color', array(
      'default'           => array_keys($this->theme_colors)[0],
      'sanitize_callback' => array($this, 'admin_customize_sanitize'),
    ));
    $wp_customize->add_control('vf_theme_color', array(
      'type'        => 'select',
      'section'     => 'vf_theme',
      'label'       => __('Primary Theme Color', 'vfwp'),
      'description' => __('Used for general design accents.', 'vfwp'),
      'choices'     => $this->theme_colors,
    ));
    */
  }

  /**
   * Add global theme to body class attribute
   */
  public function body_class($classes) {
    $theme = get_theme_mod(
      'vf_theme',
      array_keys($this->themes)[0]
    );
    $layout = get_theme_mod(
      'vf_theme_layout',
      array_keys($this->theme_layouts)[0]
    );
    $classes[] = "vf-global-theme--{$theme}";
    $classes[] = "vf-global-layout--{$layout}";
    return $classes;
  }

  /**
   * Output custom inline <head> stuff
   * @deprecated https://github.com/visual-framework/vf-wp/issues/141
   */
  /*
  public function wp_head() {
    $theme_color = get_theme_mod(
      'vf_theme_color',
      array_keys($this->theme_colors)[0]
    );
  ?>
<style>
.vf-wp-theme .vf-box--secondary {
  --vf-masthead__color--background: #<?php echo $theme_color; ?>;
}
</style>
  <?php
  }
  */

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
