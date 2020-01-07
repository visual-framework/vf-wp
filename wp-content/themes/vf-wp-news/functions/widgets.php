<?php

/**
 * Define default widgets and register sidebars
 */
add_action('widgets_init', 'vf__widgets_init');

function vf__widgets_init() {

  $widgets = array(
    'WP_Widget_Archives',
    'WP_Widget_Calendar',
    // 'WP_Widget_Categories',
    'WP_Widget_Custom_HTML',
    // 'WP_Widget_Links',
    'WP_Widget_Media_Audio',
    'WP_Widget_Media_Gallery',
    'WP_Widget_Media_Image',
    'WP_Widget_Media_Video',
    'WP_Widget_Meta',
    'WP_Widget_Pages',
    // 'WP_Widget_Search',
    // 'WP_Widget_Text',
    // 'WP_Widget_Recent_Posts',
    'WP_Widget_Recent_Comments',
    'WP_Widget_RSS',
    'WP_Widget_Tag_Cloud',
    'WP_Nav_Menu_Widget'
  );

  foreach ($widgets as $name) {
    unregister_widget($name);
  }

  $defaults = array(
    'before_widget' => '<div class="widget %2$s vf-box vf-box--inlay" data-id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="vf-box__heading">',
    'after_title'   => '</h3>',
  );

  register_sidebar(array_merge($defaults, array(
    'name'          => __('Blog Sidebar', 'vfwp'),
    'id'            => 'sidebar-blog',
    'description'   => __( 'Sidebar for the blog pages.', 'vfwp')
  )));

  register_sidebar(array_merge($defaults, array(
    'name'          => __('Page Sidebar', 'vfwp'),
    'id'            => 'sidebar-page',
    'description'   => __( 'Sidebar for standard pages.', 'vfwp')
  )));
}

/**
 * Add `vf-box` classes to WP_Widget_Text paragraph content
 */
add_filter('widget_text_content', 'vf__widget_text_content', 10, 3);

function vf__widget_text_content($text, $settings, $instance) {
  return preg_replace('#<p([^>]*)>#', '<p class="vf-box__text"$1>', $text);
}

/**
 * Modify default "Recent Entries" widget code
 * https://visual-framework.github.io/vf-core/components/detail/vf-link-list.html
 */
add_filter('vf/render_widget_recent_entries', 'vf__render_widget_recent_entries');

function vf__render_widget_recent_entries($html) {
  $html = str_replace('vf-box vf-box--inlay', 'vf-links vf-box vf-box--inlay', $html);
  $html = str_replace('vf-box__heading', 'vf-links__heading', $html);
  $html = str_replace('<ul>', '<ul class="vf-links__list | vf-list">', $html);
  $html = str_replace('<li>', '<li class="vf-list__item">', $html);
  $html = str_replace('<a ', '<a class="vf-list__link" ', $html);
  return $html;
}

/**
 * Modify the "Categories" widget code
 */
add_filter('vf/render_widget_categories', 'vf__render_widget_categories');

function vf__render_widget_categories($html) {
  // Replace hidden text class
  $html = str_replace('screen-reader-text', 'vf-sr-only', $html);
  // Add select class
  $html = preg_replace(
    '#<select[^>]*?>#',
    '<select class="vf-form__select" id="cat" name="cat">',
    $html
  );
  return $html;
}

/**
 * Modify the "Search" widget code
 */
add_filter('vf/render_widget_search', 'vf__render_widget_search');

function vf__render_widget_search($html) {
  // Remove `vf-box` classes
  $html = preg_replace(
    '#(widget\s+?widget_search)\s+?vf-box\s+?vf-box--inlay#',
    '$1', $html
  );
  return $html;
}

/**
 * Template function to render a dynamic sidebar and widgets
 */
function vf_sidebar($id) {

  // Capture default template
  ob_start();
  dynamic_sidebar($id);
  $html = ob_get_contents();
  ob_end_clean();

  // Find starting element for each widget
  if (preg_match_all('#<div\s+class="widget[^>]*?>#', $html, $matches)) {

    // Add marker before adjacent widgets
    for ($i = 1; $i < count($matches[0]); $i++) {
      $html = str_replace($matches[0][$i], "<!-- vf:widget -->{$matches[0][$i]}", $html);
    }

    // Split HTML into seperate widget code
    $widgets = explode('<!-- vf:widget -->', $html);

    foreach ($widgets as $i => $code) {
      $widgets[$i] = trim($code);
      // Get the class name to apply filter for each widget
      if (preg_match('#<div\s+class="widget\s+(widget_\w+)[^>]*?>#', $code, $match)) {
        $widgets[$i] = apply_filters("vf/render_{$match[1]}", $code);
      }
    }

    $html = implode("\n", $widgets);
  }

  echo $html;
}

?>
