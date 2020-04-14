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

    // Register field group
    acf_add_local_field_group(array(
      'key' => 'group_vf_setting',
      'title' => __('Visual Framework', 'vfwp'),
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
          'message' => __('Include CDN Stylesheet on front-end', 'vfwp'),
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
          'message' => __('Include CDN JavaScript on front-end', 'vfwp'),
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
      'menu_order' => 100,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => 1,
      'description' => '',
      'modified' => 1544016028,
    ));
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
