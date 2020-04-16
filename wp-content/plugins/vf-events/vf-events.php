<?php
/*
Plugin Name: VF-WP Events
Description: VF-WP events custom post type and Gutenberg blocks.
Version: 0.1.0
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once(plugin_dir_path(__FILE__) . 'includes/acf.php');
require_once(plugin_dir_path(__FILE__) . 'includes/register.php');
require_once(plugin_dir_path(__FILE__) . 'includes/template.php');

if ( ! class_exists('VF_Events') ) :

class VF_Events {

  private $acf;
  private $register;
  private $template;

  function __construct() {
    // Do nothing...
  }

  // Return custom post type
  static public function type() {
    return 'vf_event';
  }

  public function initialize() {
    // Initialize sub-class instances
    $this->acf = new VF_Events_ACF(__FILE__);
    $this->register = new VF_Events_Register(__FILE__);
    $this->template = new VF_Events_Template(__FILE__);
    // Add hooks
    register_activation_hook(
      __FILE__,
      array($this, 'activate')
    );
    add_action(
      'init',
      array($this, 'init')
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
    );
  }

  public function template() {
    return $this->template;
  }

  /**
   * Action: `register_activation_hook`
   */
  public function activate() {
    // Ensure custom post type is registered then flush permalinks
    $this->register->init_register();
    flush_rewrite_rules();
  }

  /**
   * Action: `init`
   */
  public function init() {
    // Get events post type vars
    $post_type_object = get_post_type_object(
      VF_Events::type()
    );
    $type = $post_type_object->name;
    $slug = $post_type_object->rewrite['slug'];
    $rules = array();
    // Add rewrite rule for "Past Events" archive
    $rules[] = array(
      $slug . '/past/?$',
      'index.php?post_type=' . $type . '&order=DESC',
    );
    // Add rewrite rule for "Past Events" pagination
    $rules[] = array(
      $slug . '/past/page/([0-9]{1,})/?$',
      'index.php?post_type=' . $type . '&order=DESC&paged=$matches[1]',
    );
    foreach ($rules as $rule) {
      add_rewrite_rule($rule[0], $rule[1], 'top');
    }
  }

  /**
   * Action: `admin_enqueue_scripts`
   */
  public function admin_enqueue_scripts() {
    $plugin = get_plugin_data(__FILE__);
    wp_enqueue_style(
      'vf-events-admin',
      plugin_dir_url(__FILE__) . 'assets/admin.css',
      array(),
      $plugin['Version'],
      'all'
    );
  }

  /**
   * Return true if viewing past events archive
   */
  static public function is_past_archive() {
    if ( ! is_post_type_archive(VF_Events::type())) {
      return false;
    }
    $order = get_query_var('order');
    return $order === 'DESC';
  }

  /**
   * Return true if viewing upcoming events archive
   */
  static public function is_upcoming_archive() {
    if ( ! is_post_type_archive(VF_Events::type())) {
      return false;
    }
    return ! VF_Events::is_past_archive();
  }

  /**
   * Return `WP_Query` for ordered event posts
   */
  static public function get_events($args) {
    $args = array(
      'post_type'      => VF_Events::type(),
      'post_status'    => 'publish',
      'posts_per_page' => $args['posts_per_page'],
      'order'          => (bool) $args['is_past'] ? 'DESC' : 'ASC'
    );
    $query = new WP_Query($args);
    $query->set('is_archive', false);
    return $query;
  }

  /**
   * Return events archive link
   */
  static public function get_archive_link($is_past = false) {
    $url = get_post_type_archive_link(VF_Events::type());
    if ($is_past) {
      $url = "{$url}past/";
    }
    return $url;
  }

  // Return date format from ACF options page
  static public function get_date_format() {
    $format = 'j F Y';
    if ( ! function_exists('get_field')) {
      return $format;
    }
    $option = get_field('vf_event_date_format', 'option');
    if ($option === 'custom') {
      $option = get_field('vf_event_date_format_custom', 'option');
    }
    $option = trim($option);
    if ( ! empty($option)) {
      $format = $option;
    }
    return $format;
  }

  /**
   * Return the page title for archive templates
   */
  static public function get_archive_title($is_past = null) {
    $post_type_object = get_post_type_object(
      VF_Events::type()
    );
    if ( ! function_exists('get_field')) {
      return $post_type_object->label;
    }
    $upcoming_title = get_field('vf_event_upcoming_title', 'options');
    $upcoming_title = trim($upcoming_title);
    $title = $upcoming_title;
    // Get past events title
    if ( ! is_bool($is_past)) {
      $is_past = VF_Events::is_past_archive();
    }
    if ($is_past) {
      $past_title = get_field('vf_event_past_title', 'options');
      $past_title = trim($past_title);
      $title = $past_title;
    }
    // Use post type label for default title
    if (empty($title)) {
      $title = sprintf(
        _x('Upcoming %1$s', 'events archive title', 'vfwp'),
        $post_type_object->label
      );
      if ($is_past) {
        $title = sprintf(
          _x('Past %1$s', 'events archive title', 'vfwp'),
          $post_type_object->label
        );
      }
    }
    return $title;
  }

  /**
   * Return pagination URL
   */
  static public function get_archive_pages() {
    // Get pagination vars and URLs
    global $wp_query;
    $found = intval($wp_query->found_posts);
    $limit = $wp_query->get(
      'posts_per_page',
      get_option('posts_per_page')
    );
    $pages = intval(
      ceil($found / $limit)
    );
    $pagination = array(
      'next'     => false,
      'previous' => false
    );
    if (
      preg_match(
        '#href="([^"]*)"#is',
        get_next_posts_link('', $pages),
        $matches)
    ) {
      $pagination['next'] = trim($matches[1]);
    }
    if (
      preg_match(
        '#href="([^"]*)"#is',
        get_previous_posts_link('', $pages),
        $matches)
    ) {
      $pagination['previous'] = trim($matches[1]);
    }
    return $pagination;
  }

} // VF_Events

// Return the global `VF_Events` instance
function vf_events() {
  global $vf_events;
  if ( ! isset($vf_events)) {
    $vf_events = new VF_Events();
    $vf_events->initialize();
  }
  return $vf_events;
}

// Initialize global instance
vf_events();

endif;

?>
