<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Events_ACF') ) :

class VF_Events_ACF {

  // Root plugin `__FILE__`
  private $file;

  function __construct($file) {
    $this->file = $file;
    // Cannot do anything without ACF plugin
    if ( ! class_exists('ACF')) {
      return;
    }
    // Add hooks
    $post_type = VF_Events::type();
    add_action(
      'pre_get_posts',
      array($this, 'pre_get_posts')
    );
    add_action('acf/init',
      array($this, 'acf_init')
    );
    add_filter(
      'acf/format_value',
      array($this, 'acf_format_value'),
      10, 3
    );
    add_filter(
      "manage_{$post_type}_posts_columns",
      array($this, 'posts_columns')
    );
    add_action(
      "manage_{$post_type}_posts_custom_column",
      array($this, 'posts_custom_column'),
      10, 2
    );
    add_filter(
      "manage_edit-{$post_type}_sortable_columns",
      array($this, 'sortable_columns')
    );
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
  }

  /**
   * Return true if `$key` is a date meta property
   */
  static public function is_key_date($key) {
    return preg_match(
      '#' . preg_quote(VF_Events::type()) . '_(.*?)_date$#',
      $key
    );
  }

  /**
   * Action: `pre_get_posts`
   */
  public function pre_get_posts($query) {
    // Ignore non-event queries
    if ($query->get('post_type') !== VF_Events::type()) {
      return;
    }
    if ( ! $query->is_main_query()) {
      return;
    }
    // Handle order by date metadata in admin table
    if (is_admin()) {
      $this->pre_get_posts_admin($query);
      return;
    }
    // Handle archive template order
    if ($query->is_archive()) {
      $this->pre_get_posts_archive($query);
      return;
    }
  }

  /**
   * Main query for events admin edit posts table
   */
  private function pre_get_posts_admin($query) {
    $orderby = $query->get('orderby');
    if ( ! VF_Events_ACF::is_key_date($orderby)) {
      return;
    }
    $query->set('meta_key', $orderby);
    $query->set('meta_type', 'numeric');
    $query->set('orderby', 'meta_value');
  }

  /**
   * Main query for events archive template
   */
  private function pre_get_posts_archive($query) {
    // Meta key to order by numeric value
    $key = 'vf_event_start_date';
    // Meta value for comparison
    $today = date('Ymd');

    // Events per page
    $ppp = get_field('vf_events_per_page', 'option');
    $ppp = intval($ppp);
    if (is_nan($ppp) || $ppp < 1 || $ppp > 100) {
      $ppp = 10;
    }
    $query->set('posts_per_page', $ppp);

    // Default to upcoming events
    $order = $query->get('order');
    if (empty($order)) {
      $order = 'ASC';
    }
    $query->set('meta_key', $key);
    $query->set('meta_type', 'numeric');
    $query->set('orderby', 'meta_value');
    $query->set('order', $order);
    $meta_query = array(
      'relation' => 'AND',
      array(
        'key'     => $key,
        'compare' => 'EXISTS'
      )
    );
    // Filter by upcoming events
    if ($order === 'ASC') {
      $meta_query[] = array(
        'key'     => $key,
        'value'   => $today,
        'compare' => '>='
      );
    }
    // Filter by past events
    if ($order === 'DESC') {
      $meta_query[] = array(
        'key'     => $key,
        'value'   => $today,
        'compare' => '<'
      );
    }
    $query->set(
      'meta_query',
      $meta_query
    );
  }

  /**
   * Action: `acf/init`
   */
  public function acf_init() {
    acf_add_options_page(array(
      'menu_slug'   => 'vf-events-settings',
      'menu_title'  => __('Settings', 'vfwp'),
      'page_title'  => __('Events Settings', 'vfwp'),
      'parent_slug' => 'edit.php?post_type=' . VF_Events::type(),
      'capability'  => 'manage_options'
    ));
  }

  /**
   * Filter: `acf/format_value`
   */
  public function acf_format_value($value, $post_id, $field) {
    // Ignore non-event posts
    if (get_post_type($post_id) !== VF_Events::type()) {
      return $value;
    }
    // Format date values
    if (VF_Events_ACF::is_key_date($field['name']) && ! empty($value)) {
      $time = strtotime($value);
      if ($time !== false) {
        $value = date(VF_Events::get_date_format(), $time);
      }
    }
    return $value;
  }

  /**
   * Filter: `manage_{$post_type}_posts_columns`
   */
  public function posts_columns($columns) {
    // Insert date columns after title
    $offset = array_search('title', array_keys($columns));
    $columns = array_merge(
      array_slice($columns, 0, $offset + 1),
      array(
        'vf_event_start_date' => __('Start Date', 'vfwp'),
        'vf_event_end_date'   => __('End Date', 'vfwp'),
      ),
      array_slice($columns, $offset + 1)
    );
    return $columns;
  }

  /**
   * Action: `manage_{$post_type}_posts_custom_column`
   */
  public function posts_custom_column($column, $post_id) {
    if (strpos($column, 'vf_') !== 0) {
      return;
    }
    // Get meta value
    $value = get_field($column, $post_id);
    $value = trim($value);
    echo esc_html($value);
  }

  /**
   * Filter: `manage_edit-{$post_type}_sortable_columns`
   */
  public function sortable_columns($columns) {
    // Append sortable date columns
    $columns = array_merge($columns, array(
      'vf_event_start_date' => 'vf_event_start_date',
      'vf_event_end_date'   => 'vf_event_end_date',
    ));
    return $columns;
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $dir = plugin_dir_path($this->file);
    $paths[] = "{$dir}acf-json";
    return $paths;
  }

} // VF_Events_ACF

endif;

?>
