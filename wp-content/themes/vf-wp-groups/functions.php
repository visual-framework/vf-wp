<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Child_Theme') ) :

class VF_Child_Theme {

  public function __construct() {
    // Add child theme hooks
    add_filter(
      'pre_get_posts',
      array($this, 'pre_get_posts')
    );
    add_filter(
      'body_class',
      array($this, 'body_class')
    );
    add_action(
      'wp_head',
      array($this, 'wp_head')
    );
    add_action(
      'wp_enqueue_scripts',
      array($this, 'wp_enqueue_scripts')
    );
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

  /**
   * Filter page query
   */
  public function pre_get_posts($query) {
    if (is_admin()) {
      return $query;
    }
    if ( ! $query->is_main_query()) {
      return $query;
    }
    // Exclude non posts from search
    if ($query->is_search()) {
      $query->set('post_type', 'post');
    }
    return $query;
  }

  /**
   * Append <body> class for Visual Framework
   */
  public function body_class($classes) {
    $classes[] = 'vf-body';
    $classes[] = 'vf-u-background-color-ui--grey';
    return $classes;
  }

  /**
   * Output inline <head> stuff
   */
  public function wp_head() {
    $theme_color = get_theme_mod('vf_theme_color', '009f4d');
  ?>
  <style>
  .vf-wp-theme .vf-box--secondary,
  .vf-wp-theme .vf-masthead {
    --vf-masthead__color--background: #<?php echo $theme_color; ?>;
  }
  </style>
  <?php
  }

  /**
   * Load CSS and JavaScript assets
   */
  public function wp_enqueue_scripts() {
    $dir = untrailingslashit(get_template_directory_uri());
    // Register assets for "Jobs" template
    if (
      is_page_template('template-jobs.php') &&
      function_exists('embl_taxonomy')
    ) {
      $assets = $dir . '/assets/assets/vfwp-autocomplete';
      wp_register_script(
        'vfwp-autocomplete',
        $assets . '/vfwp-autocomplete.js',
        array(),
        '2.0.1',
        true
      );
      wp_register_style(
        'vfwp-autocomplete',
        $assets . '/vfwp-autocomplete.css',
        array(),
        '2.0.1',
        'all'
      );
      wp_enqueue_script('vfwp-autocomplete');
      wp_enqueue_style('vfwp-autocomplete');
    }
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_css_class($classes, $item, $args, $depth) {
    if (in_array($args->theme_location, array('primary', 'secondary'))) {
      return ['vf-navigation__item'];
    }
    return $classes;
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_link_attributes($atts, $item, $args, $depth) {
    if (in_array($args->theme_location, array('primary', 'secondary'))) {
      $atts['class'] = 'vf-navigation__link';
    }
    return $atts;
  }

} // VF_Child_Theme

endif;

global $vf_child_theme;
if ( ! isset($vf_child_theme)) {
  $vf_child_theme = new VF_Child_Theme();
}

?>
