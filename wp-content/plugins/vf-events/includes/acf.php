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
   * Action: `pre_get_posts`
   */
  public function pre_get_posts($query) {
    // Handle order by date metadata in admin table
    if (
      is_admin() &&
      $query->is_main_query()
    ) {
      $orderby = $query->get('orderby');
      if (VF_Events::is_key_date($orderby)) {
        $query->set('meta_key', $orderby);
        $query->set('meta_type', 'numeric');
        $query->set('orderby', 'meta_value');
      }
    }
    // Handle archive template order
    if (
      ! is_admin() &&
      $query->is_main_query() &&
      is_post_type_archive(VF_Events::type())
    ) {
      $key = 'vf_event_start_date';
      $today = date('Ymd');
      $query->set('meta_key', $key);
      $query->set('meta_type', 'numeric');
      $query->set('orderby', 'meta_value');
      $query->set('order', 'ASC');
      $meta_query = array(
        'relation' => 'AND',
        array(
          'key'     => $key,
          'compare' => 'EXISTS'
        ),
        array(
          'key'     => $key,
          'value'   => $today,
          'compare' => '>='
        )
      );
      $query->set(
        'meta_query',
        $meta_query
      );
    }
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
    if (VF_Events::is_key_date($field['name']) && ! empty($value)) {
      $time = strtotime($value);
      if ($time !== false) {
        $value = date(VF_Events::date_format(), $time);
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
