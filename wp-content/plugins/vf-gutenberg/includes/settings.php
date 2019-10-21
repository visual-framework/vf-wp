<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Settings') ) :

class VF_Gutenberg_Settings {

  function __construct() {
    add_action('acf/init', array($this, 'acf_init'));
  }

  /**
   * Action `acf/init`
   */
  function acf_init() {

    // Add options page
    acf_add_options_page(array(
      'menu_title'  => 'VF Settings',
      'menu_slug'   => 'vf-settings',
      'parent_slug' => 'options-general.php',
      'page_title'  => 'Visual Framework Settings',
      'capability'  => 'manage_options'
    ));

    // Register field group
    acf_add_local_field_group(array(
      'key' => 'group_vf_setting',
      'title' => 'VF Settings',
      'fields' => array(
        array(
          'key' => 'field_vf_cdn_stylesheet',
          'label' => 'CDN Stylesheet',
          'name' => 'vf_cdn_stylesheet',
          'type' => 'url',
        ),
        array(
          'key' => 'field_vf_cdn_stylesheet_optin',
          'label' => '',
          'name' => 'vf_cdn_stylesheet_optin',
          'type' => 'true_false',
          'message' => 'Include CDN Stylesheet on front-end',
          'default_value' => 0,
          'ui' => 1,
        ),
        array(
          'key' => 'field_vf_cdn_javascript',
          'label' => 'CDN JavaScript',
          'name' => 'vf_cdn_javascript',
          'type' => 'url',
        ),
        array(
          'key' => 'field_vf_cdn_javascript_optin',
          'label' => '',
          'name' => 'vf_cdn_javascript_optin',
          'type' => 'true_false',
          'message' => 'Include CDN JavaScript on front-end',
          'default_value' => 0,
          'ui' => 1,
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'vf-settings',
          ),
        ),
      ),
      'menu_order' => 10,
      'position' => 'normal',
      'style' => 'seamless',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => 1,
      'description' => '',
      'modified' => 1544016028,
    ));

    acf_add_local_field(
      array(
        'parent'        => 'group_vf_setting',
        'key'           => 'field_vf_gutenberg_enable_core_blocks',
        'label'         => '(Alpha) Gutenberg blocks',
        'name'          => 'vf_gutenberg_core_blocks_optin',
        'type'          => 'true_false',
        'message'       => 'Opt-in to enable core Visual Framework Gutenberg blocks',
        'instructions'  => 'This is an alpha feature and breaking changes are expected. In future updates stable blocks will be enabled by default.',
        'default_value' => 0,
        'ui'            => 1,
      )
    );
  }

} // VF_Gutenberg_Settings

/**
 * Return the VF stylesheet URL
 */
function vf_get_stylesheet() {
  if ( ! function_exists('get_field')) {
    return null;
  }
  $optin = (bool) get_field('vf_cdn_stylesheet_optin', 'option');
  if (!$optin) {
    return null;
  }
  $url = trim(get_field('vf_cdn_stylesheet', 'option'));
  return empty($url) ? null : $url;
}

/**
 * Return the VF javascript URL
 */
function vf_get_javascript() {
  if ( ! function_exists('get_field')) {
    return null;
  }
  $optin = (bool) get_field('vf_cdn_javascript_optin', 'option');
  if (!$optin) {
    return null;
  }
  $url = trim(get_field('vf_cdn_javascript', 'option'));
  return empty($url) ? null : $url;
}

endif;

?>
