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
    $path = rtrim($path, '/\\') . '/templates';
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
   * Filter: `template_include`
   */
  public function template_include($template) {
    $override = null;
    $post_type = VF_Events::type();
    // Choose archive template
    if (is_post_type_archive($post_type)) {
      $template = "{$this->plugin_template_path()}/archive.php";
      $override = "{$this->theme_template_path()}/archive-{$post_type}.php";
     }
     // Choose single template
    if (is_singular($post_type)) {
      $template = "{$this->plugin_template_path()}/single.php";
      $override = "{$this->theme_template_path()}/single-{$post_type}.php";
    }
    // Prefer theme template if it exists
    if ( ! empty($override) && file_exists($override)) {
      $template = $override;
    }
    return $template;
  }

} // VF_Events_Template

endif;

?>
