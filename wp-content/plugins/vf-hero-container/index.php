<?php
/*
Plugin Name: VF-WP Hero Header
Description: VF-WP Hero container.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_WP_Hero extends VF_Plugin {

  const MAX_WIDTH = 1224;
  const MAX_HEIGHT = 348;

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_wp_hero',
    'post_title' => 'VF Hero (group)',
    'post_type'  => 'vf_container'
  );

  function __construct(array $params = array()) {
    parent::__construct();
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();
    add_action(
      'after_setup_theme',
      array($this, 'after_setup_theme')
    );
    add_filter(
      'vf_wp_groups_header/hero_heading',
      array($this, 'filter_hero_heading'),
      9, 1
    );
    add_filter(
      'vf_wp_groups_header/hero_text',
      array($this, 'filter_hero_text_cleanup'),
      8, 1
    );
  }

  /**
   * Theme setup
   */
  public function after_setup_theme() {
    add_image_size(
      'vf-hero',
      self::MAX_WIDTH,
      self::MAX_HEIGHT,
      array('center', 'center')
    );
  }
    
  /**
   * Return `vf-hero` "heading" from custom fields or Content Hub
   */
  public function get_hero_heading() {
    $field = get_field_object(
      'field_vf_hero_heading',
      $this->post()->ID
    );
    $heading = get_field(
      'vf_hero_heading',
      $this->post()->ID
    );
    $heading = trim($heading);
    if (empty($heading)) {
      $heading = $field['default_value'];
    }
    $heading = sprintf(
      $heading,
      get_bloginfo('name')
    );
    $heading = apply_filters(
      'vf_wp_groups_header/hero_heading',
      $heading
    );
    return $heading;
  }

  /**
   * Default filter for hero heading
   */
  public function filter_hero_heading($heading) {
    $heading = esc_html($heading);
    return $heading;
  }

  /**
   * Return `vf-hero` "text" from custom fields or Content Hub
   */
  public function get_hero_text() {
    $text = get_field('vf_hero_text', $this->post()->ID);
    $text = trim($text);
    // If text is empty use the Content Hub description
    if (vf_html_empty($text) && class_exists('VF_Cache')) {
      // Get the global taxonomy term
      $term_id = get_field('embl_taxonomy_term_what', 'option');
      $term_uuid = embl_taxonomy_get_uuid($term_id);
      // Query Content Hub via cache
      if ($term_uuid) {
        $url = VF_Cache::get_api_url();
        $url .= '/pattern.html';
        $url = add_query_arg(array(
          'filter-uuid'         => $term_uuid,
          'filter-content-type' => 'profiles',
          'pattern'             => 'node-teaser',
          'source'              => 'contenthub',
        ), $url);
        $text = VF_Cache::fetch($url);
      }
    }
    $text = apply_filters(
      'vf_wp_groups_header/hero_text',
      $text
    );
    return $text;
  }

  /**
   * Default filter for hero text
   */
  public function filter_hero_text_cleanup($text) {
    if ( ! apply_filters('vf_wp_groups_header/hero_text_cleanup', true)) {
      return $text;
    }
    // Cleanup Content Hub response
    $text = preg_replace(
      '#<[^>]*?embl-conditional-edit[^>]*?>.*?</[^>]*?>#',
      '', $text
    );
    // Filter allowed tags
    $text = wp_kses($text, array(
      'a' => array(
        'href' => array(),
        'title' => array()
      ),
      'em'     => array(),
      'strong' => array(),
    ));
    return $text;
  }


  /**
   * Return `vf-hero` image
   */
  public function get_hero_image() {
    $image = get_field('vf_hero_image', $this->post()->ID);
    if ( ! is_array($image)) {
      return null;
    }
    return $image;
  }

} // VF_WP_Hero_Header

$plugin = new VF_WP_Hero(
  array('init' => true)
);

?>
