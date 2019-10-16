<?php

if( ! defined( 'ABSPATH' ) ) exit;

include_once('admin-lock.php');

/**
 * Clean up WP Admin for non-admin users
 */
add_action('admin_head', 'vf__admin_head');

function vf__admin_head() {
  if (current_user_can('editor')) {
    // Give editors more capabilities
    $editor = get_role('editor');
    $editor->add_cap('edit_theme_options');

    // Only show "Menus" in the admin menu
    $whitelist = array('nav-menus', 'widgets', 'customize');
    global $submenu;
    if (is_array($submenu) && array_key_exists('themes.php', $submenu)) {
      foreach ($submenu['themes.php'] as $i => $item) {
        $base = explode('.php', $item[2], 2);
        if ( ! in_array($base[0], $whitelist)) {
          unset($submenu['themes.php'][$i]);
        }
      }
    }
  }
?>
<style>
.wp-block {
  max-width: 780px;
}
</style>
<?php
}

/**
 * Clean up WP Admin bar
 */
add_action('admin_bar_menu', 'vf__admin_bar_menu', 999);

function vf__admin_bar_menu($wp_admin_bar) {
  // Remove options from the admin bar
  $wp_admin_bar->remove_menu('customize');
  $wp_admin_bar->remove_menu('widgets');
}

/**
 * Enqueue WP Admin CSS and JavaScript
 */
add_action('admin_enqueue_scripts', 'vf__admin_enqueue_scripts');

function vf__admin_enqueue_scripts() {
  $theme = wp_get_theme();
  $dir = get_template_directory_uri();

  wp_enqueue_style(
    'vf_admin',
    $dir . '/assets/assets/vfwp-admin/vfwp-admin.css',
    array(),
    $theme->version,
    'all'
  );

  wp_enqueue_script(
    'vf_admin',
    $dir . '/assets/js/admin.min.js',
    array('jquery'),
    $theme->version,
    true
  );
}

/**
 * Add theme customisation
 */
add_action('customize_register', 'vf__customize_register');

function vf__customize_register($wp_customize) {

  // $wp_customize->add_setting('vf_theme_color', array(
  //   'default'   => VF_THEME_COLOR
  // ));
  // $wp_customize->add_control(
  //   new WP_Customize_Color_Control($wp_customize, 'vf_theme_color',
  //     array(
  //       'label'    => __('Theme Colour', 'vfwp'),
  //       'section'  => 'vf_theme',
  //       'settings' => 'vf_theme_color'
  //     )
  //   )
  // );

  $wp_customize->add_section('vf_theme' , array(
    'title'    => __('Visual Framework', 'vfwp'),
    'priority' => 30
  ));

  $wp_customize->add_setting('vf_theme_color', array(
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'themeslug_sanitize_select',
    'default' => VF_THEME_COLOR,
  ) );

  $wp_customize->add_control('vf_theme_color', array(
    'type' => 'select',
    'section' => 'vf_theme',
    'label' => __( 'Custom Select Option' ),
    'description' => __( 'This is a custom select option.' ),
    'choices' => array(
      '009f4d' => __( 'EMBL Green' ),
      '007c80' => __( 'EMBL-EBI Petrol' ),
    ),
  ) );

  function themeslug_sanitize_select( $input, $setting ) {
    // Ensure input is a slug.
    $input = sanitize_key( $input );
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
  }

  // No way!
  $wp_customize->remove_section('custom_css');
}

/**
 * Enqueue Gutenberg edtior assets
 */
add_action('enqueue_block_editor_assets', 'vf__enqueue_block_editor_assets');

function vf__enqueue_block_editor_assets() {
  $theme = wp_get_theme();
  $dir = get_template_directory_uri();
  wp_enqueue_script(
    'vf_admin', $dir . '/assets/js/admin.min.js',
    array('wp-editor', 'wp-blocks', 'wp-element'),
    $theme->version,
    true
  );
}

/**
 * Allow SVG upload to media library
 */
add_filter('upload_mimes', 'vf__upload_mimes');

function vf__upload_mimes($mimes = array()) {
  $mimes['svg'] = 'image/svg'; // should be image/svg+xml ?
  return $mimes;
}

?>
