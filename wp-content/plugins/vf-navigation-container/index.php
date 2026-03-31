<?php
/*
Plugin Name: VF-WP Navigation
Description: VF-WP theme global container.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Navigation extends VF_Plugin {

  protected static $menu_source_override = null;

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_navigation',
    'post_title' => 'Navigation',
    'post_type'  => 'vf_container',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_navigation');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
    add_filter(
      'nav_menu_css_class',
      array($this, 'nav_menu_css_class'),
      10, 4
    );
    add_filter(
      'nav_menu_link_attributes',
      array($this, 'nav_menu_link_attributes'),
      10, 4
    );

  }

  public static function get_navigation_menu_choices() {
    $choices = array();
    $labels = array();
    $menus = wp_get_nav_menus();

    if (is_array($menus)) {
      foreach ($menus as $menu) {
        if ( ! $menu instanceof WP_Term) {
          continue;
        }

        $label = $menu->name;

        if (in_array($label, $labels, true)) {
          $label = sprintf(__('%s (menu)', 'vfwp'), $menu->name);
        }

        if (in_array($label, $labels, true)) {
          $label = sprintf(__('%s (menu #%d)', 'vfwp'), $menu->name, $menu->term_id);
        }

        $choices["menu:{$menu->term_id}"] = $label;
        $labels[] = $label;
      }
    }

    return $choices;
  }

  public static function get_primary_menu_source() {
    $locations = get_nav_menu_locations();
    if (is_array($locations) && ! empty($locations['primary'])) {
      return "menu:{$locations['primary']}";
    }

    $menus = wp_get_nav_menus();
    if (is_array($menus)) {
      foreach ($menus as $menu) {
        if (
          $menu instanceof WP_Term &&
          in_array(strtolower($menu->name), array('primary', 'primary menu'), true)
        ) {
          return "menu:{$menu->term_id}";
        }
      }
    }

    return 'location:primary';
  }

  public static function resolve_menu_source($menu_source = '') {
    if (
      is_string($menu_source) &&
      preg_match('#^(menu|location):.+$#', $menu_source)
    ) {
      return $menu_source;
    }

    return self::get_primary_menu_source();
  }

  public static function get_render_menu_source() {
    if (
      is_string(self::$menu_source_override) &&
      self::$menu_source_override !== ''
    ) {
      return self::resolve_menu_source(self::$menu_source_override);
    }

    return self::resolve_menu_source(get_field('vf_navigation_menu_source'));
  }

  public static function render_menu($menu_source = '') {
    $previous_menu_source = self::$menu_source_override;
    self::$menu_source_override = self::resolve_menu_source($menu_source);

    VF_Plugin::render(self::get_plugin('vf_navigation'));

    self::$menu_source_override = $previous_menu_source;
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_css_class($classes, $item, $args, $depth) {
    $theme_location = isset($args->theme_location) ? $args->theme_location : '';

    if (
      in_array($theme_location, array('primary', 'secondary')) ||
      ! empty($args->vf_navigation)
    ) {
      $classes[] = 'vf-navigation__item';
    }
    return $classes;
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_link_attributes($atts, $item, $args, $depth) {
    $theme_location = isset($args->theme_location) ? $args->theme_location : '';

    if (
      in_array($theme_location, array('primary', 'secondary')) ||
      ! empty($args->vf_navigation)
    ) {
      $atts['class'] = 'vf-navigation__link';
    }
    return $atts;
  }

  // public function template_callback($block, $content, $is_preview = false, $acf_id) {
  //   include(plugin_dir_path(__FILE__) . 'template.php');
  // }

} // VF_Navigation

$plugin = new VF_Navigation(array('init' => true));

?>
