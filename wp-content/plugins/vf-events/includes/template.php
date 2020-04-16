<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Events_Template') ) :

class VF_Events_Template {

  // Root plugin `__FILE__`
  private $file;

  function __construct($file) {
    $this->file = $file;
    // Add hooks
    add_filter('template_include',
      array($this, 'template_include')
    );
  }

  /**
   * Return the template directory for this plugin
   */
  public function plugin_template_path() {
    $path = plugin_dir_path($this->file);
    $path = $path . 'templates';
    return $path;
  }

  /**
   * Return the template directory for the active theme
   */
  public function theme_template_path() {
    $path = get_stylesheet_directory();
    $path = rtrim($path, '/\\');
    return $path;
  }

  /**
   * Return the full template path
   */
  public function get_template($file) {
    $template = "{$this->plugin_template_path()}/{$file}";
    $override = "{$this->theme_template_path()}/{$file}";
    // Prefer theme template if it exists
    if ( ! empty($override) && file_exists($override)) {
      $template = $override;
    }
    return $template;
  }

  /**
   * Filter: `template_include`
   */
  public function template_include($template) {
    $post_type = VF_Events::type();
    if (VF_Events::is_query_events()) {
      // Choose archive template
      if (is_archive() || is_tax()) {
        return $this->get_template("archive-{$post_type}.php");
      }
      // Choose single template
      if (is_singular()) {
        return $this->get_template("single-{$post_type}.php");
      }
    }
    return $template;
  }

} // VF_Events_Template

endif;

?>
