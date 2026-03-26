<?php
/*
Plugin Name: VF-WP Events
Description: VF-WP events custom post type and Gutenberg blocks.
Version: 1.0.0-beta.1
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
  static public function topic() {
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
    register_deactivation_hook(
      __FILE__,
      array($this, 'deactivate')
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
    VF_Events::maybe_schedule_chatbot_routes_refresh();
    VF_Events::refresh_chatbot_routes_payload();
    flush_rewrite_rules();
  }

  /**
   * Action: `register_deactivation_hook`
   */
  public function deactivate() {
    VF_Events::clear_chatbot_routes_refresh_schedule();
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

    VF_Events::maybe_schedule_chatbot_routes_refresh();
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
   * Return true if query is related to events
   */
  static public function is_query_events($query = null) {
    if ( ! $query instanceof WP_Query) {
      global $wp_query;
      $query = $wp_query;
    }
    if (VF_Events::is_query_events_post($query)) {
      return true;
    }
    if (VF_Events::is_query_events_taxonomy($query)) {
      return true;
    }
    return false;
  }

  /**
   * Return true if query is related to events post type
   */
  static public function is_query_events_post($query) {
    $obj = $query->get_queried_object();
    if (
      $obj instanceof WP_Post &&
      $obj->post_type === VF_Events::type()
    ) {
      return true;
    }
    if (
      $obj instanceof WP_Post_Type &&
      $obj->name === VF_Events::type()
    ) {
      return true;
    }
    return false;
  }

  /**
   * Return true if query is related to an events taxonomy
   */
  static public function is_query_events_taxonomy($query) {
    $obj = $query->get_queried_object();
    return (
      $obj instanceof WP_Term &&
      preg_match(
        '#^' . preg_quote(VF_Events::type()) . '_#',
        $obj->taxonomy
      )
    );
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
    if ( ! empty($option)) {
      $option = trim($option);
    }
    if ( ! empty($option)) {
      $format = $option;
    }
    return $format;
  }

  /**
   * Return true when the events chatbot is enabled in settings.
   */
  static public function is_chatbot_enabled() {
    $enabled = null;

    if (function_exists('get_field')) {
      $enabled = get_field('vf_events_enable_chatbot', 'option');
    }

    if ($enabled === null) {
      $enabled = get_option('options_vf_events_enable_chatbot');
    }

    return (bool) $enabled;
  }

  /**
   * Option name for stored chatbot routes JSON.
   */
  static public function chatbot_routes_option_name() {
    return 'options_vf_events_chatbot_routes_payload';
  }

  /**
   * Option name for chatbot routes refresh timestamp.
   */
  static public function chatbot_routes_updated_at_option_name() {
    return 'options_vf_events_chatbot_routes_updated_at';
  }

  /**
   * Cron hook for refreshing chatbot routes.
   */
  static public function chatbot_routes_refresh_hook() {
    return 'vf_events_refresh_chatbot_routes_daily';
  }

  /**
   * REST route path for chatbot routes.
   */
  static public function chatbot_routes_rest_path() {
    return 'vf-events/v1/chatbot-routes';
  }

  /**
   * URL for chatbot routes payload.
   */
  static public function get_chatbot_routes_url() {
    return rest_url(self::chatbot_routes_rest_path());
  }

  /**
   * Admin URL for the Events settings page.
   */
  static public function get_settings_url() {
    return admin_url(
      'edit.php?post_type=' . self::type() . '&page=vf-events-settings'
    );
  }

  /**
   * Last refresh timestamp for stored routes payload.
   */
  static public function get_chatbot_routes_updated_at() {
    return (int) get_option(self::chatbot_routes_updated_at_option_name(), 0);
  }

  /**
   * Schedule the daily chatbot routes refresh if it is not already scheduled.
   */
  static public function maybe_schedule_chatbot_routes_refresh() {
    if (wp_next_scheduled(self::chatbot_routes_refresh_hook())) {
      return;
    }

    wp_schedule_event(
      time() + HOUR_IN_SECONDS,
      'daily',
      self::chatbot_routes_refresh_hook()
    );
  }

  /**
   * Clear the scheduled daily chatbot routes refresh.
   */
  static public function clear_chatbot_routes_refresh_schedule() {
    $timestamp = wp_next_scheduled(self::chatbot_routes_refresh_hook());

    if ($timestamp) {
      wp_unschedule_event($timestamp, self::chatbot_routes_refresh_hook());
    }
  }

  /**
   * Return the stored chatbot routes JSON string.
   */
  static public function get_chatbot_routes_payload_raw() {
    $payload = get_option(self::chatbot_routes_option_name(), '');

    return is_string($payload) ? $payload : '';
  }

  /**
   * Decode chatbot routes payload JSON into an array.
   */
  static public function decode_chatbot_routes_payload($payload_json) {
    if (!is_string($payload_json) || $payload_json === '') {
      return false;
    }

    $payload = json_decode($payload_json, true);

    if (!is_array($payload) || !isset($payload['routes']) || !is_array($payload['routes'])) {
      return false;
    }

    return $payload;
  }

  /**
   * Return the stored routes payload, refreshing it if needed.
   */
  static public function get_chatbot_routes_payload($refresh_if_empty = false) {
    $payload = self::decode_chatbot_routes_payload(
      self::get_chatbot_routes_payload_raw()
    );

    if ($payload !== false) {
      return $payload;
    }

    if ($refresh_if_empty) {
      $payload = self::refresh_chatbot_routes_payload();

      if ($payload !== false) {
        return $payload;
      }
    }

    return array('routes' => array());
  }

  /**
   * Refresh and persist the chatbot routes payload.
   */
  static public function refresh_chatbot_routes_payload($posts_per_page = 100) {
    $payload = self::build_chatbot_routes_payload($posts_per_page);
    $json = wp_json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    if (!is_string($json)) {
      return false;
    }

    update_option(self::chatbot_routes_option_name(), $json, false);
    update_option(self::chatbot_routes_updated_at_option_name(), time(), false);

    return $payload;
  }

  /**
   * Build the chatbot routes payload from event posts.
   */
  static public function build_chatbot_routes_payload($posts_per_page = 100) {
    $events = self::get_chatbot_routes_events_data($posts_per_page);

    return array(
      'routes' => self::map_chatbot_routes_from_events($events),
    );
  }

  /**
   * Get upcoming event data for chatbot routes generation.
   */
  static private function get_chatbot_routes_events_data($posts_per_page = 100) {
    $today = date('Y-m-d');
    $query = new WP_Query(array(
      'post_type' => self::type(),
      'posts_per_page' => $posts_per_page,
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'meta_key' => 'vf_event_start_date',
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => 'vf_event_end_date',
          'value' => $today,
          'compare' => '>=',
          'type' => 'DATE',
        ),
        array(
          'relation' => 'AND',
          array(
            'key' => 'vf_event_end_date',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key' => 'vf_event_start_date',
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE',
          ),
        ),
      ),
    ));
    $events = array();

    if (!$query->have_posts()) {
      return $events;
    }

    while ($query->have_posts()) {
      $query->the_post();

      $start_date = get_field('vf_event_start_date');
      $end_date = get_field('vf_event_end_date');
      $event_type = self::get_chatbot_event_type_details(
        get_field('vf_event_event_type')
      );

      $events[] = array(
        'id' => get_post_field('post_name', get_the_ID()),
        'post_id' => get_the_ID(),
        'title' => get_the_title(),
        'start_date' => $start_date,
        'end_date' => $end_date,
        'date_label' => self::get_chatbot_event_date_label($start_date, $end_date),
        'event_type' => $event_type['label'],
        'event_type_value' => $event_type['value'],
        'hero_image' => self::get_chatbot_hero_image_url(get_field('vf_event_hero')),
      );
    }

    wp_reset_postdata();

    return $events;
  }

  /**
   * Map event data into the chatbot routes structure.
   */
  static private function map_chatbot_routes_from_events($events) {
    $routes = array();

    foreach ($events as $event) {
      $routes[] = array(
        'id' => $event['id'],
        'title' => $event['title'],
        'start_date' => $event['start_date'],
        'end_date' => $event['end_date'],
        'date_label' => $event['date_label'],
        'event_type' => $event['event_type'],
        'event_type_value' => $event['event_type_value'],
        'hero_image' => $event['hero_image'],
        'post_id' => $event['post_id'],
      );
    }

    return $routes;
  }

  /**
   * Normalize an event hero URL so it is portable across environments.
   */
  static private function get_chatbot_hero_image_url($hero_image) {
    $hero_image_url = '';

    if (is_array($hero_image)) {
      $hero_image_url = !empty($hero_image['url']) ? $hero_image['url'] : '';
    } elseif (is_numeric($hero_image)) {
      $hero_image_url = wp_get_attachment_url((int) $hero_image);
    } elseif (is_string($hero_image)) {
      $hero_image_url = $hero_image;
    }

    if (!is_string($hero_image_url) || $hero_image_url === '') {
      return '';
    }

    if (strpos($hero_image_url, '/wp-content/uploads/') !== false) {
      return wp_make_link_relative($hero_image_url);
    }

    return $hero_image_url;
  }

  /**
   * Extract event type value/label for chatbot routes.
   */
  static private function get_chatbot_event_type_details($event_type) {
    $event_type_value = '';
    $event_type_label = '';

    if (is_array($event_type)) {
      if (!empty($event_type['value'])) {
        $event_type_value = $event_type['value'];
      } elseif (!empty($event_type['label'])) {
        $event_type_value = sanitize_title($event_type['label']);
      }

      if (!empty($event_type['label'])) {
        $event_type_label = $event_type['label'];
      }
    } elseif (is_string($event_type)) {
      $event_type_value = $event_type;
      $event_type_label = $event_type;
    }

    return array(
      'value' => $event_type_value,
      'label' => $event_type_label,
    );
  }

  /**
   * Format the event date label for chatbot routes.
   */
  static private function get_chatbot_event_date_label($start_date, $end_date) {
    if (empty($start_date)) {
      return '';
    }

    $start = DateTime::createFromFormat('j M Y', $start_date);
    $end = !empty($end_date)
      ? DateTime::createFromFormat('j M Y', $end_date)
      : false;

    if (!$start instanceof DateTime) {
      return $start_date;
    }

    if ($end instanceof DateTime) {
      if ($start->format('M') === $end->format('M')) {
        return $start->format('j') . ' – ' . $end->format('j M Y');
      }

      return $start->format('j M') . ' – ' . $end->format('j M Y');
    }

    return $start->format('j M Y');
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
    if ( ! empty($upcoming_title)) {
      $upcoming_title = trim($upcoming_title);
    }
    $title = $upcoming_title;
    // Get past events title
    if ( ! is_bool($is_past)) {
      $is_past = VF_Events::is_past_archive();
    }
    if ($is_past) {
      $past_title = get_field('vf_event_past_title', 'options');
      if ( ! empty($past_title)) {
        $past_title = trim($past_title);
      }
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
    $next_link = get_next_posts_link('', $pages);
    if ($next_link &&
      preg_match(
        '#href="([^"]*)"#is',
        $next_link,
        $matches)
    ) {
      $pagination['next'] = trim($matches[1]);
    }
    $prev_link = get_previous_posts_link('', $pages);
    if ($prev_link &&
      preg_match(
        '#href="([^"]*)"#is',
        $prev_link,
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
