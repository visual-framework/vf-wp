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
    add_action('wp_footer',
      array($this, 'render_chatbot_modal'),
      100
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
      if (is_tax("{$post_type}_type", 'embo-embl-symposia')) {
        return $this->get_template("taxonomy-{$post_type}-symposia.php");
      }
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

  /**
   * Action: `wp_footer`
   */
  public function render_chatbot_modal() {
    if ( ! is_singular(VF_Events::type())) {
      return;
    }
    if ( ! VF_Events::is_chatbot_enabled()) {
      return;
    }

    include $this->get_template('partials/chatbot-modal.php');
  }

} // VF_Events_Template

endif;

?>
