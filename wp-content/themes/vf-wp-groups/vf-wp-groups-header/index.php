<?php
/*
Plugin Name: VF-WP Groups Header
Description: VF-WP Groups theme global container.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_WP_Groups_Header extends VF_Plugin {

  const MAX_WIDTH = 1224;
  const MAX_HEIGHT = 348;

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_wp_groups_header',
    'post_title' => 'Groups Header',
    'post_type'  => 'vf_container',

    // Allow block to be previewed in WP admin
    '__experimental__has_admin_preview' => true
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
    add_filter(
      'vf_wp_groups_header/hero_text',
      array($this, 'filter_hero_text_link'),
      9, 1
    );
    add_filter(
      'vf_wp_groups_header/hero_link',
      array($this, 'filter_hero_link'),
      9, 1
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
   * Return design level
   */
  public function get_hero_theme() {
    $field = get_field_object(
      'field_vf_hero_theme',
      $this->post()->ID
    );
    $theme = get_field(
      'vf_hero_theme',
      $this->post()->ID
    );
    if (is_array($theme)) {
      $theme = $theme[0];
    }
    if (empty($theme) || $theme === 'default') {
      $theme = get_theme_mod(
        'vf_theme',
        $field['default_value']
      );
    }
    return $theme;
  }

  /**
   * Return design level
   */
  public function get_hero_level() {
    $field = get_field_object(
      'field_vf_hero_level',
      $this->post()->ID
    );
    $level = get_field(
      'vf_hero_level',
      $this->post()->ID
    );
    if (is_array($level)) {
      $level = $level[0];
    }
    if (empty($level)) {
      $level = get_level_mod(
        'vf_level',
        $field['default_value']
      );
    }
    return $level;
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
   * Default filter for hero text
   */
  public function filter_hero_text_link($text) {
    if ( ! apply_filters('vf_wp_groups_header/hero_text_link', true)) {
      return $text;
    }
    // Adds link to the hero introduction
    $link = $this->get_hero_link();

    if (is_array($link)) {
      $link_text = '<a class="vf-link" href="'
        . esc_url($link['url'])
        . '">'
        . $text
        . '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
      </svg>'
        . '</a>';
      return $link_text;
    }

    else {
      return $text;
    }
  }

  /**
   * Return `vf-hero` "link" from custom fields
   */
  public function get_hero_link() {
    $link = get_field('vf_hero_link', $this->post()->ID);
    $link = apply_filters(
      'vf_wp_groups_header/hero_link',
      $link
    );
    if ( ! is_array($link) ) {
      $link = null;
    }
    return $link;
  }

  /**
   * Default filter for hero link
   */
  public function filter_hero_link($link) {
    if ( ! is_array($link)) {
      $link = null;
      //  Use "Read more" (/about/) as fallback if page exists
      /* 
      $page = get_page_by_path('/about/');
      if ($page instanceof WP_Post) {
        $link = array(
          'title' => __('Read more', 'vfwp'),
          'url'   => get_the_permalink($page->ID)
        );
      } */
    }
    if ($link) {
      $title = $link['title'];
      $title = trim($title);
      $title = esc_html($title);
      // Replace single whitespace with non-breaking to avoid widows
      if (preg_match('#^[^\s]+?\s[^\s]+?$#', $title)) {
        $title = preg_replace('#\s+?#', '&nbsp;', $title);
      }
      $link['title'] = $title;
    }
    return $link;
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

} // VF_WP_Groups_Header

$plugin = new VF_WP_Groups_Header(
  array('init' => true)
);

?>
