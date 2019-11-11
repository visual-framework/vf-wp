<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Type') ) :

/**
 * Represent a custom post type for Visual Framework components
 */
class VF_Type {

  protected $post_type = 'vf_block';
  protected $post_type_plural = 'vf_blocks';
  protected $description = 'VF Blocks';
  protected $labels = array(
    'name'          => 'VF Blocks',
    'singular_name' => 'Block',
    'edit_item'     => 'Edit Block'
  );

  public function __construct() {
    // Nothing
  }

  /**
   * Return plugin post type
   */
  public function type() {
    return $this->post_type;
  }

  public function initialize() {
    add_action(
      'init',
      array($this, 'init')
    );
    add_filter(
      'wp_insert_post_data',
      array($this, 'wp_insert_post_data'),
      10, 2
    );
    add_action(
      'template_redirect',
      array($this, 'template_redirect')
    );
    add_filter('allowed_block_types',
      array($this, 'allowed_block_types'),
      10, 2
    );
    add_filter(
      'acf/location/rule_types',
      array($this, 'acf_rule_types')
    );
    add_filter(
      "acf/location/rule_values/{$this->post_type}",
      array($this, 'acf_rule_values')
    );
    add_filter(
      "acf/location/rule_match/{$this->post_type}",
      array($this, 'acf_rule_match'),
      10, 3
    );
    add_filter(
      "acf/fields/post_object/result/key=field_{$this->post_type}_post",
      array($this, 'acf_post_object_result'),
      10, 4
    );
    add_filter(
      "acf/load_field/key=field_{$this->post_type}_name",
      array($this, 'acf_load_field_post_name')
    );
    add_filter(
      "manage_{$this->post_type}_posts_columns",
      array($this, 'posts_columns')
    );
    add_action(
      "manage_{$this->post_type}_posts_custom_column",
      array($this, 'posts_custom_column'),
      10, 2
    );
  }

  /**
   * Action: plugin activation
   * Setup post type capabilities for caontainers
   */
  public function activate() {
    $role = get_role('administrator');
    $role->add_cap('read_' . $this->post_type);
    $role->add_cap('edit_' . $this->post_type);
    $role->add_cap('edit_' . $this->post_type_plural);
    $role->add_cap('edit_others_' . $this->post_type_plural);
  }

  /**
   * Action: plugin deactivation
   * Tidy up database by removing all capabilities
   */
  public function deactivate() {
    $role = get_role('administrator');
    $role->remove_cap('edit_' . $this->post_type);
    $role->remove_cap('read_' . $this->post_type);
    $role->remove_cap('delete_' . $this->post_type);
    $role->remove_cap('edit_' . $this->post_type_plural);
    $role->remove_cap('edit_others_' . $this->post_type_plural);
    $role->remove_cap('publish_' . $this->post_type_plural);
    $role->remove_cap('read_private_' . $this->post_type_plural);
    $role->remove_cap('delete_' . $this->post_type_plural);
    $role->remove_cap('delete_private_' . $this->post_type_plural);
    $role->remove_cap('delete_published_' . $this->post_type_plural);
    $role->remove_cap('delete_others_' . $this->post_type_plural);
    $role->remove_cap('edit_private_' . $this->post_type_plural);
    $role->remove_cap('edit_published_' . $this->post_type_plural);
    $role->remove_cap('create_' . $this->post_type_plural);
  }

  /**
   * Action: `init`
   * Register custom post type
   */
  public function init() {
    register_post_type($this->post_type, array(
      'labels'          => $this->labels,
      'description'     => $this->description,
      'capability_type' => array(
        $this->post_type,
        $this->post_type_plural
      ),
      'capabilities'    => array(
        'edit_post'   => 'edit_' . $this->post_type,
        'read_post'   => 'read_' . $this->post_type,
        'delete_post' => 'delete_' . $this->post_type,

        'edit_posts'         => 'edit_' . $this->post_type_plural,
        'edit_others_posts'  => 'edit_others_' . $this->post_type_plural,
        'publish_posts'      => 'publish_' . $this->post_type_plural,
        'read_private_posts' => 'read_private_' . $this->post_type_plural,

        'delete_posts'           => 'delete_' . $this->post_type_plural,
        'delete_private_posts'   => 'delete_private_' . $this->post_type_plural,
        'delete_published_posts' => 'delete_published_' . $this->post_type_plural,
        'delete_others_posts'    => 'delete_others_' . $this->post_type_plural,
        'edit_private_posts'     => 'edit_private_' . $this->post_type_plural,
        'edit_published_posts'   => 'edit_published_' . $this->post_type_plural,
        'create_posts'           => 'create_' . $this->post_type_plural
      ),
      'map_meta_cap'       => false,
      'public'             => false,
      'show_ui'            => true,
      'show_in_admin_bar'  => false,
      'show_in_rest'       => true,
      'supports'           => array('title', 'editor'),
      'rewrite'            => false,
      'publicly_queryable' => true,
      'query_var'          => true,
      'can_export'         => false
    ));
  }

  /**
   * Disallow slug changes for this type
   */
  public function wp_insert_post_data($data, $postarr) {
    // Ignore new posts
    if (empty($postarr['ID']) || empty($postarr['post_type'])) {
      return $data;
    }
    // Ignore other post types
    if ($postarr['post_type'] !== $this->post_type) {
      return $data;
    }
    // Disallow slug changes
    $post_name = get_post_field('post_name', $postarr['ID'], 'raw');
    if ($post_name !== $postarr['post_name']) {
      wp_die(new WP_Error(
        "{$this->post_type}_edit_post_name",
        __('The slug cannot be edited for this container.', 'vfwp')
      ));
    }
    return $data;
  }

  /**
   * Restrict viewing default VF_Plugin posts standalone
   */
  public function template_redirect() {
    if (get_query_var('post_type') === $this->post_type) {
      if ( ! current_user_can("read_{$this->post_type}")) {
        global $wp_query;
        $wp_query->set_404();
      }
    }
  }

  /**
   * Disallow all Gutenberg blocks for the post type by default.
   * Effectively disabling the editor.
   */
  public function allowed_block_types($allowed, $post) {
    if ($post->post_type === $this->post_type) {
      return false;
    }
    return $allowed;
  }

  /**
   * Add custom ACF location type for post type
   */
  public function acf_rule_types($choices) {
    $choices['Post'][$this->post_type] = $this->labels['name'];
    return $choices;
  }

  /**
   * Add custom ACF locaion values for post type based on `post_name`
   */
  public function acf_rule_values($choices) {
    $plugins = get_posts(array(
      'posts_per_page' => -1,
      'post_type'      => $this->post_type
    ));
    if (is_array($plugins)) {
      foreach($plugins as $plugin) {
        $choices[$plugin->post_name] = $plugin->post_title;
      }
    }
    return $choices;
  }

  /**
   * Match custom ACF location rule for post type
   */
  public function acf_rule_match($match, $rule, $options) {
    global $post;
    if ( ! $post instanceof WP_Post) return;
    if ($post->post_type !== $this->post_type) return;
    if ($rule['operator'] === '==') {
      return $rule['value'] === $post->post_name;
    }
    if ($rule['operator'] === '!=') {
      return $rule['value'] !== $post->post_name;
    }
  }

  /**
   * Label inactive plugins in post type results
   */
  public function acf_post_object_result($title, $post, $field, $post_id) {
    $config = VF_Plugin::get_config($post->post_name);
    if ( ! $config) {
      return $title .= ' (' . __('plugin is not active', 'vfwp') . ')';
    }
    return $title;
  }

  /**
   * Populate select dropdown with list of active posts
   */
  public function acf_load_field_post_name($field) {
    $items = get_posts(array(
      'numberposts' => -1,
      'post_type'   => $this->post_type,
      'post_status' => 'publish',
      'orderby'     => 'title',
      'order'       => 'ASC'
    ));
    $field['choices'] = array();
    foreach ($items as $item) {
      $field['choices'][$item->post_name] = $item->post_title;
    }
    return $field;
  }

  /**
   * Filter: add "Template" column to posts table
   */
  public function posts_columns($columns) {
    $offset = array_search('date', array_keys($columns));
    $columns = array_merge(
      array_slice($columns, 0, $offset),
      array('vf_meta' => __('Meta', 'vfwp')),
      array_slice($columns, $offset)
    );
    return $columns;
  }

  /**
   * Action: output template path for posts table in custom column
   */
  public function posts_custom_column($column, $post_id) {
    if ($column !== 'vf_meta') {
      return;
    }
    $post_name = get_post_field('post_name', $post_id, 'raw');
    $plugin = VF_Plugin::get_plugin($post_name);
    if ( ! $plugin) {
      return;
    }
    $icons = array();
    $path = $plugin->template();
    if ($path) {
      $offset = strpos($path, 'wp-content');
      if ($offset) {
        $path = substr($path, $offset + 10);
      }
      $icons[] = '<span class="dashicons dashicons-edit"></span> <abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            sprintf(
              __('PHP template: %1$s', 'vfwp'),
              $path
            )
          ))
        . '">'
        . esc_html__('PHP', 'vfwp')
        . '</abbr>';
    }
    if ($plugin->is_acf()) {
      $icons[] = '<span class="dashicons dashicons-admin-generic"></span> <abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            __('Advanced Custom Fields configuration', 'vfwp')
          ))
        . '">'
        . esc_html__('ACF', 'vfwp')
        . '</abbr>';
    }
    if ($plugin->is_api()) {
      $icons[] = '<span class="dashicons dashicons-external"></span> <abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            __('Content Hub API integration', 'vfwp')
          ))
        . '">'
        . esc_html__('API', 'vfwp')
        . '</abbr>';
    }
    if ( ! empty($icons)) {
      echo '<p>', implode('&nbsp; ', $icons), '</p>';
    }
  }

} // VF_Type

endif;

?>
