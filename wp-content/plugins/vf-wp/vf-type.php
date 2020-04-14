<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Type') ) :

/**
 * Represent a custom post type for Visual Framework components
 */
class VF_Type {

  protected $post_type = 'vf_block';
  protected $post_type_plural = 'vf_blocks';
  protected $description = 'Blocks';

  protected $labels = array(
    'name'          => 'Blocks',
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
    add_filter(
      'map_meta_cap',
      array($this, 'map_meta_cap'),
      10, 4
    );
  }

  /**
   * Action: plugin activation
   * Setup post type capabilities for caontainers
   */
  public function activate() {
    $this->activate_roles();
  }

  private function activate_roles() {
    $role = get_role('administrator');
    if ( ! $role) {
      return;
    }
    $role->add_cap("edit_$this->post_type_plural");
    $role->add_cap("publish_$this->post_type_plural");
  }

  /**
   * Action: plugin deactivation
   * Tidy up database by removing all capabilities
   */
  public function deactivate() {
    $this->deactivate_roles();
  }

  private function deactivate_roles() {
    $role = get_role('administrator');
    if ( ! $role) {
      return;
    }
    $role->remove_cap("edit_$this->post_type_plural");
    $role->remove_cap("publish_$this->post_type_plural");
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
        // Meta capabilities
        'edit_post'           => "edit_{$this->post_type}",
        'read_post'           => "read_{$this->post_type}",
        'delete_post'         => "delete_{$this->post_type}",
        // Primitive capabilities used outside of map_meta_cap():
        'edit_posts'          => "edit_$this->post_type_plural",
        'edit_others_posts'   => "edit_$this->post_type_plural",
        'publish_posts'       => "publish_$this->post_type_plural",
        'read_private_posts'  => "read_$this->post_type_plural",
        // Primitive capabilities used within map_meta_cap():
        'read'                   => 'read',
        'delete_posts'           => "delete_{$this->post_type_plural}",
        'delete_private_posts'   => "delete_{$this->post_type_plural}",
        'delete_published_posts' => "delete_{$this->post_type_plural}",
        'delete_others_posts'    => "delete_{$this->post_type_plural}",
        'edit_private_posts'     => "edit_$this->post_type_plural",
        'edit_published_posts'   => "edit_$this->post_type_plural",
        'create_posts'           => "create_$this->post_type_plural"
      ),
      'map_meta_cap'       => false,
      'public'             => false,
      'show_ui'            => true,
      'show_in_admin_bar'  => false,
      'show_in_rest'       => true,
      'show_in_menu'       => 'vf-settings',
      'supports'           => array('title', 'editor'),
      'rewrite'            => false,
      'publicly_queryable' => true,
      'query_var'          => true,
      'can_export'         => false
    ));

    // Lock post content preview
    $post_type_object = get_post_type_object($this->post_type);
    if ($post_type_object) {
      $post_type_object->template = array(
        array('vf/plugin', array()),
      );
      $post_type_object->template_lock = 'insert';
    }

    // Ensure admin roles are activated
    $this->activate_roles();
  }

  /**
   * Filter: `map_meta_cap`
   * Map meta capabilities to the custom post type
   */
  public function map_meta_cap($caps, $cap, $user_id, $args) {
    // Ignore unrelated post types
    $meta_caps = array(
      "read_{$this->post_type}",
      "edit_{$this->post_type}",
      "delete_{$this->post_type}",
      "create_{$this->post_type_plural}"
    );
    if ( ! in_array($cap, $meta_caps)) {
      return $caps;
    }
    // Anyone can read/view
    if ($cap === $meta_caps[0]) {
      $caps = array(
        'read'
      );
    }
    // Allow editing posts (assigned to admin roles)
    if ($cap === $meta_caps[1]) {
      $caps = array(
        "edit_{$this->post_type_plural}"
      );
    }
    // Disallow deleting posts
    if ($cap === $meta_caps[2]) {
      $caps = array(
        "delete_{$this->post_type_plural}__unassigned"
      );
    }
    // Disallow creating posts
    if ($cap === $meta_caps[3]) {
      $caps = array(
        "create_{$this->post_type_plural}__unassigned"
      );
    }
    return $caps;
  }

  /**
   * Disallow slug changes for this type
   */
  public function wp_insert_post_data($data, $postarr) {
    // Ignore new posts
    if (
      empty($postarr['ID']) ||
      empty($postarr['post_type']) ||
      empty($postarr['post_status'])
    ) {
      return $data;
    }
    // Ignore other post types
    if ($postarr['post_type'] !== $this->post_type) {
      return $data;
    }
    // Ignore non-published posts
    if ($postarr['post_status'] !== 'publish') {
      return $data;
    }
    // Get existing post name and matching plugin
    $post_name = get_post_field('post_name', $postarr['ID'], 'raw');
    $plugin = VF_Plugin::get_plugin($post_name);
    // Disallow slug changes
    if ($plugin && $post_name !== $postarr['post_name']) {
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
      // Check if plugin supports editor preview
      $plugin = VF_Plugin::get_plugin($post->post_name);
      if ($plugin && $plugin->__experimental__is_admin_render()) {
        return array(
          'vf/plugin'
        );
      }
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
    $posts = get_posts(array(
      'posts_per_page' => -1,
      'post_type'      => $this->post_type
    ));
    if ( ! is_array($posts)) {
      return $choices;
    }
    foreach($posts as $post) {
      $plugin = VF_Plugin::get_plugin($post->post_name);
      if ($plugin) {
        $choices[$post->post_name] = $post->post_title;
      }
    }
    return $choices;
  }

  /**
   * Match custom ACF location rule for post type
   */
  public function acf_rule_match($match, $rule, $options) {
    global $post;
    if ( ! $post instanceof WP_Post) {
      return;
    }
    if ($post->post_type !== $this->post_type) {
      return;
    }
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
    if ($plugin->is_plugin()) {
      $icons[] = '<abbr title="'
        . esc_attr(sprintf(
            __('Plugin loaded from %1$s', 'vfwp'),
            __('plugins directory', 'vfwp')
          ))
        . '">'
        . '<span class="dashicons dashicons-admin-plugins"></span>'
        . '</abbr>';
    }
    else if ($plugin->is_theme()) {
      $icons[] = '<abbr title="'
        . esc_attr(sprintf(
            __('Plugin loaded from %1$s', 'vfwp'),
            __('theme directory', 'vfwp')
          ))
        . '">'
        . '<span class="dashicons dashicons-admin-appearance"></span>'
        . '</abbr>';
    }
    $path = $plugin->template();
    if ($path) {
      $offset = strpos($path, 'wp-content');
      if ($offset) {
        $path = substr($path, $offset + 10);
      }
      $icons[] = '<abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            sprintf(
              __('PHP template: %1$s', 'vfwp'),
              $path
            )
          ))
        . '">'
        . '<span class="dashicons dashicons-edit"></span>'
        . '</abbr>';
    }
    if ($plugin->is_acf()) {
      $icons[] = '<abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            __('Advanced Custom Fields configuration', 'vfwp')
          ))
        . '">'
        . '<span class="dashicons dashicons-admin-generic"></span>'
        . '</abbr>';
    }
    if ($plugin->is_api()) {
      $icons[] = '<abbr title="'
        . esc_attr(sprintf(
            __('Plugin has %1$s', 'vfwp'),
            __('Content Hub API integration', 'vfwp')
          ))
        . '">'
        . '<span class="dashicons dashicons-external"></span>'
        . '</abbr>';
    }
    if ( ! empty($icons)) {
      echo implode('&nbsp; ', $icons);
    }
  }

} // VF_Type

endif;

?>
