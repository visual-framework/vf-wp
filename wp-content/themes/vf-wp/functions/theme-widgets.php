<?php
/**
 * Theme Widgets
 * Sub-class initiated by `VF_Theme`
 * Provides the global `vf_sidebar($id)` template function
 * for sidebar and widgets with Visual Framework markup.
 */

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Theme_Widgets') ) :

class VF_Theme_Widgets {

  public function __construct() {
    add_action(
      'widgets_init',
      array($this, 'widgets_init')
    );
    add_filter(
      'widget_text_content',
      array($this, 'widget_text_content'),
      9, 3
    );
    add_filter(
      'vf/widgets/unregister',
      array($this, 'default_widgets'),
      9, 1
    );
    add_filter(
      'vf/widgets/sidebars',
      array($this, 'default_sidebars'),
      9, 1
    );
    add_filter(
      'vf/widgets/render/recent_entries',
      array($this, 'render_widget_recent_entries'),
      9, 1
    );
    add_filter(
      'vf/widgets/render/archive',
      array($this, 'render_widget_archive'),
      9, 1
    );
    add_filter(
      'vf/widgets/render/categories',
      array($this, 'render_widget_categories'),
      9, 1
    );
    add_filter(
      'vf/widgets/render/pages',
      array($this, 'render_widget_pages'),
      9, 1
    );
    add_filter(
      'vf/widgets/render/search',
      array($this, 'render_widget_search'),
      9, 1
    );
  }

  /**
   * Define default widgets and register sidebars
   */
  public function widgets_init() {
    // Setup and filter the theme widgets
    $widgets = VF_Theme::apply_filters('vf/widgets/unregister', array());
    if (is_array($widgets)) {
      foreach ($widgets as $name) {
        if (is_string($name)) {
          unregister_widget($name);
        }
      }
    }
    // Setup and filter the theme sidebars
    $sidebars = VF_Theme::apply_filters('vf/widgets/sidebars', array());
    if (is_array($sidebars)) {
      foreach ($sidebars as $sidebar) {
        if (is_array($sidebar)) {
          register_sidebar($sidebar);
        }
      }
    }
  }

  /**
   * Return array of default widgets to unregister
   */
  public function default_widgets($widgets) {
    return array(
      // 'WP_Widget_Archives',
      'WP_Widget_Calendar',
      // 'WP_Widget_Categories',
      'WP_Widget_Custom_HTML',
      // 'WP_Widget_Links',
      'WP_Widget_Media_Audio',
      'WP_Widget_Media_Gallery',
      'WP_Widget_Media_Image',
      'WP_Widget_Media_Video',
      'WP_Widget_Meta',
      // 'WP_Widget_Pages',
      // 'WP_Widget_Search',
      // 'WP_Widget_Text',
      // 'WP_Widget_Recent_Posts',
      'WP_Widget_Recent_Comments',
      'WP_Widget_RSS',
      'WP_Widget_Tag_Cloud',
      'WP_Nav_Menu_Widget'
    );
  }

  /**
   * Return the default sidebar args to register
   */
  public function default_sidebars() {
    $defaults = array(
      'before_widget' => '<div class="widget %2$s vf-box vf-box--inlay" data-id="%1$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="vf-box__heading">',
      'after_title'   => '</h3>',
    );
    $sidebars = array(
      array_merge($defaults, array(
        'name'          => __('Blog Sidebar', 'vfwp'),
        'id'            => 'sidebar-blog',
        'description'   => __( 'Sidebar for the blog pages.', 'vfwp')
      )),
      array_merge($defaults, array(
        'name'          => __('Page Sidebar', 'vfwp'),
        'id'            => 'sidebar-page',
        'description'   => __( 'Sidebar for standard pages.', 'vfwp')
      ))
    );
    return $sidebars;
  }

  /**
   * Add `vf-box` classes to WP_Widget_Text paragraph content
   */
  public function widget_text_content($text, $settings, $instance) {
    return preg_replace('#<p([^>]*)>#', '<p class="vf-box__text"$1>', $text);
  }

  /**
   * Modify default "Recent Entries" widget code
   * https://visual-framework.github.io/vf-core/components/detail/vf-link-list.html
   */
  public function render_widget_recent_entries($html) {
    $html = str_replace('vf-box vf-box--inlay', 'vf-links vf-box vf-box--inlay', $html);
    $html = str_replace('vf-box__heading', 'vf-links__heading', $html);
    $html = str_replace('<ul>', '<ul class="vf-links__list | vf-list">', $html);
    $html = str_replace('<li>', '<li class="vf-list__item">', $html);
    $html = str_replace('<a ', '<a class="vf-list__link" ', $html);
    return $html;
  }

  /**
   * Modify the "Archives" widget code
   */
  public function render_widget_archive($html) {
    // Replace hidden text class
    $html = str_replace('screen-reader-text', 'vf-sr-only', $html);
    // Add select class
    $html = preg_replace(
      '#<select([^>]*?)>#',
      '<select $1 class="vf-form__select">',
      $html
    );
    // Add `vf-list` classes
    $html = str_replace('<ul>', '<ul class="vf-links__list | vf-list">', $html);
    $html = str_replace('<li>', '<li class="vf-list__item">', $html);
    return $html;
  }

  /**
   * Modify the "Categories" widget code
   */
  public function render_widget_categories($html) {
    // Replace hidden text class
    $html = str_replace('screen-reader-text', 'vf-sr-only', $html);
    // Add select class
    $html = preg_replace(
      '#<select[^>]*?>#',
      '<select class="vf-form__select" id="cat" name="cat">',
      $html
    );
    // Add `vf-list` classes
    $html = str_replace('<ul>', '<ul class="vf-links__list | vf-list">', $html);
    $html = preg_replace(
      '#<li\s+class="(cat-item[^"]*?)"#',
      '<li class="$1 | vf-list__item"',
      $html
    );
    return $html;
  }

  /**
   * Modify the "Pages" widget code
   */
  public function render_widget_pages($html) {
    // Replace hidden text class
    // $html = str_replace('screen-reader-text', 'vf-sr-only', $html);

    // Add `vf-list` classes
    $html = str_replace('<ul>', '<ul class="vf-links__list | vf-list">', $html);
    $html = preg_replace(
      '#<li\s+class="(page_item[^"]*?)"#',
      '<li class="$1 | vf-list__item"',
      $html
    );
    return $html;
  }

  /**
   * Modify the "Search" widget code
   */
  public function render_widget_search($html) {
    // Remove `vf-box` classes
    $html = preg_replace(
      '#(widget\s+?widget_search)\s+?vf-box\s+?vf-box--inlay#',
      '$1', $html
    );
    return $html;
  }

  static function render_sidebar($id) {
    // Capture default template
    ob_start();
    dynamic_sidebar($id);
    $html = ob_get_contents();
    ob_end_clean();
    // Find starting element for each widget
    if (preg_match_all(
      '#<div\s+class="widget[^>]*?>#',
      $html, $matches
    )) {
      // Add marker before adjacent widgets
      for ($i = 1; $i < count($matches[0]); $i++) {
        $html = str_replace(
          $matches[0][$i],
          "<!-- vf:widget -->{$matches[0][$i]}",
          $html
        );
      }
      // Split HTML into seperate widget code
      $widgets = explode('<!-- vf:widget -->', $html);
      foreach ($widgets as $i => $code) {
        $widgets[$i] = trim($code);
        // Get the class name to apply filter for each widget
        if (preg_match(
          '#<div\s+class="widget\s+widget_(\w+)[^>]*?>#',
          $code, $match
        )) {
          $widgets[$i] = VF_Theme::apply_filters(
            "vf/widgets/render/{$match[1]}",
            $code
          );
        }
      }
      $html = implode("\n", $widgets);
    }
    echo $html;
  }

} // VF_Theme_Widgets

endif;

/**
 * Template function to render a dynamic sidebar and widgets
 */
if ( ! function_exists('vf_sidebar')) {
  function vf_sidebar($id) {
    VF_Theme_Widgets::render_sidebar($id);
  }
}

?>
