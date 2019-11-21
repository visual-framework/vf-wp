<?php
/**
 * VF Admin Reusable
 * Expand upon core reusable block features
 */
if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin_Reusable') ) :

class VF_Admin_Reusable {

  // cache instance counts from `count_instances()`
  private $counts = array();

  // post ID when viewing single block uses
  private $post_id = null;

  // query var map (prefix to avoid conflicts)
  private $query_vars = array(
    'post_id' => 'vf_post_id'
  );

  public function __construct() {
    if ( ! is_admin()) {
      return;
    }
    // Get post ID when viewing instances of a single block
    $var = $this->query_vars['post_id'];
    if (isset($_GET[$var])) {
      $this->post_id = intval($_GET[$var]);
    }
    // Add admin hooks
    add_action(
      'registered_post_type',
      array($this, 'registered_post_type'),
      10, 2
    );
    // Add columns for "Reusable Blocks" table
    if ($this->post_id === null) {
      add_filter(
        'manage_wp_block_posts_columns',
        array($this, 'manage_wp_block_posts_columns')
      );
      add_action(
        'manage_wp_block_posts_custom_column',
        array($this, 'manage_wp_block_posts_custom_column'),
        10, 2
      );
    }
    // Add query filters when viewing instance usage
    if (is_numeric($this->post_id)) {
      add_action(
        'pre_get_posts',
        array($this, 'pre_get_posts'),
        10, 1
      );
      add_filter('posts_where',
        array($this, 'posts_where'),
        10, 2
      );
    }
  }

  /**
   * Expose the "Reusable Blocks" post type in the admin menu
   */
  public function registered_post_type($post_type, $post_type_object) {
    if ($post_type !== 'wp_block') {
      return;
    }
    $post_type_object->_builtin = false;
    $post_type_object->labels->name = __('Reusable Blocks');
    $post_type_object->labels->menu_name = __('Reusable');
    $post_type_object->show_in_menu = true;
    $post_type_object->menu_position = 20;
    $post_type_object->menu_icon = 'dashicons-layout';
  }

  /**
   * Return instance markup to search within post content
   */
  public function get_tag($post_id) {
    return '<!-- wp:block {"ref":' . $post_id . '}';
  }

  /**
   * Count instances of a reusable block with a post
   */
  public function count_instances($post_id) {
    if (array_key_exists($post_id, $this->counts)) {
      return $this->counts[$post_id];
    }
    global $wpdb;
    $sql = "
      SELECT
      SUM(
        (
          LENGTH(post_content) -
          LENGTH(
            REPLACE(post_content, %s, '')
          )
        )
        DIV LENGTH(%s)
      )
      AS instances
      FROM {$wpdb->prefix}posts
      WHERE post_content
      LIKE %s
      AND post_status
      IN ('publish', 'draft', 'future', 'pending')
    ";
    $comment = $this->get_tag($post_id);
    $search = $wpdb->esc_like($comment);
    $query = $wpdb->prepare($sql, $comment, $comment, "%{$search}%");
    $this->counts[$post_id] = intval(
      $wpdb->get_var($query)
    );
    return $this->counts[$post_id];
  }

  /**
   * Modify query to include any post type for instance usage
   */
  public function pre_get_posts($query) {
    if (
      ! is_numeric($this->post_id) ||
      ! $query->is_main_query()
    ) {
      return;
    }
    $query->set('post_type', 'any');
  }

  /**
   * Modify query to filter posts that have instance usage
   */
  public function posts_where($where, $query) {
    if ( ! is_numeric($this->post_id)) {
      return;
    }
    if ($query->get('post_type') === 'any') {
      global $wpdb;
      $comment = $this->get_tag($this->post_id);
      $search = $wpdb->esc_like($comment);
      $where .= $wpdb->prepare(" AND post_content LIKE %s ", "%{$search}%");
    }
    return $where;
  }

  /**
   * Add reusable block instance column to posts table
   */
  public function manage_wp_block_posts_columns($columns) {
    $offset = array_search('title', array_keys($columns));
    $columns = array_merge(
      array_slice($columns, 0, $offset + 1),
      array(
        'vf_instances' => __('Used', 'vfwp')
      ),
      array_slice($columns, $offset + 1)
    );
    return $columns;
  }

  /**
   * Add reusable block instance value to posts table
   */
  public function manage_wp_block_posts_custom_column($column, $post_id) {
    if ($column !== 'vf_instances') {
      return;
    }
    $count = $this->count_instances($post_id);
    if ($count > 0) {
      echo '<a href="',
        admin_url(
          'edit.php?post_type=wp_block&'
          . $this->query_vars['post_id']
          . '='
        ),
        esc_attr($post_id),
        '">';
    }
    printf(
      esc_html(
        _n('%s instance', '%s instances', $count, 'vwfp')
      ),
      number_format_i18n($count)
    );
    if ($count > 0) {
      echo '</a>';
    }
  }

} // VF_Admin_Reusable

endif;

?>
