<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Plugin') ) :

/**
 * Represent a custom plugin for VF blocks, containers, or widgets
 */
class VF_Plugin {

  // Plugin file path to index.php extending this class
  protected $file;
  // Saved config for plugin activation
  protected $config;
  // Plugin contentHub API settings
  protected $API;

  // `WP_Post` of custom post type (e.g. `vf_block`)
  private $post;

  public function __construct() {
    // Setup configuration
    $this->config['file'] = $this->file;
    $this->config['class'] = get_class($this);
    // Setup defaults
    if ( ! array_key_exists('post_type', $this->config)) {
      $this->config['post_type'] = 'vf_block';
    }
  }

  /**
   * Return full config for plugin
   */
  public function config() {
    $config = $this->config;
    if ($this->post()) {
      $config['post_id'] = $this->post()->ID;
    }
    return $config;
  }

  /**
   * Initialize a new plugin that is extending this class
   */
  protected function initialize() {
    if ( ! is_array($this->config)) {
      return;
    }
    // Add to global register
    global $vf_plugins;
    if ( ! is_array($vf_plugins)) {
      $vf_plugins = array();
    }
    $vf_plugins[$this->config['post_name']] = $this->config;

    // "Activate" immediately if loaded via theme directory
    if ($this->is_builtin() || $this->is_theme()) {
      if ( ! $this->post()) {
        $this->activation_hook();
      }
    }
    // Register WP hooks for plugin directory
    if ($this->is_plugin()) {
      register_activation_hook(
        $this->file,
        array($this, 'activation_hook')
      );
      register_deactivation_hook(
        $this->file,
        array($this, 'deactivation_hook')
      );
    }
    // Add ACF JSON locations
    if ($this->is_acf()) {
      add_filter(
        'acf/settings/load_json',
        array($this, 'acf_settings_load_json')
      );
      add_action(
        'acf/update_field_group',
        array($this, 'acf_update_field_group'),
        2, 1
      );
    }
  }

  public function activation_hook() {
    VF_Plugin::register($this->config);
  }

  public function deactivation_hook() {
    VF_Plugin::deregister($this->config);
  }

  /**
   * Return plugin post
   */
  public function post() {
    if ( ! $this->post instanceof WP_Post) {
      $this->post = get_page_by_path(
        $this->config['post_name'],
        OBJECT,
        $this->config['post_type']
      );
    }
    if ( ! $this->post instanceof WP_Post) {
      $this->post = null;
    }
    return $this->post;
  }

  public function is_block() {
    return $this->config['post_type'] === 'vf_block';
  }

  public function is_container() {
    return $this->config['post_type'] === 'vf_container';
  }

  /**
   * Return true if plugin is predefined
   */
  public function is_builtin() {
    return false;
  }

  /**
   * Return true if plugin is loaded from plugins directory
   */
  public function is_plugin() {
    return strpos($this->dir(), 'wp-content/plugins') !== false;
  }

  /**
   * Return true if plugin is loaded from theme directory
   */
  public function is_theme() {
    return strpos($this->dir(), 'wp-content/themes') !== false;
  }

  /**
   * Return true if plugin has ACF configuration
   */
  public function is_acf() {
    $path = "{$this->dir()}group_{$this->config['post_name']}.json";
    return file_exists($path);
  }

  /**
   * Return true if plugin has API configuration
   */
  public function is_api() {
    return empty($this->API) === false;
  }

  /**
   * Return true if plugin is deprecated (override per plugin)
   * New Gutenberg blocks cannot be added (`inserter` = `false`)
   * Existing Gutenberg blocks are still rendered if plugin is active
   */
  public function is_deprecated() {
    return false;
  }

  /**
   * Return true if template has grid wrappers and should not be contained
   */
  public function is_template_standalone() {
    return false;
  }

  /**
   * Return true if plugin is rendered in `vf/plugin` Gutenberg block
   */
  public function __experimental__is_admin_render() {
    $ref = get_field("{$this->post()->post_name}_ref", $this->post()->ID);
    return $ref === $this->post()->post_name;
  }

  /**
   * Return true if plugin can be previewed in the WP Admin post editor
   */
  public function __experimental__has_admin_preview() {
    return array_key_exists(
      '__experimental__has_admin_preview',
      $this->config
    );
  }

  /**
   * Return full plugin directory path (with trailing slash)
   */
  public function dir() {
    if (file_exists($this->file)) {
      return plugin_dir_path($this->file);
    }
  }

  /**
   * Return full template path for plugin
   */
  public function template() {
    $path = "{$this->dir()}template.php";
    if (file_exists($path)) {
      return $path;
    }
  }

  /**
   * Return API URL base for all instances of the plugin
   */
  public function api_url(array $query_vars = array()) {
    if ( ! $this->is_api()) {
      return '';
    }
    $url = VF_Cache::get_api_url();
    $url .= '/pattern.html';
    if (is_array($this->API)) {
      $url = add_query_arg($this->API, $url);
    }
    if (count($query_vars)) {
      $url = add_query_arg($query_vars, $url);
    }
    return $url;
  }

  /**
   * Return API HTML from cache
   */
  public function api_html() {
    return VF_Cache::fetch($this->api_url());
  }

  /**
   * Return HTML attributes for wrapping element
   */
  public function api_attr(array $attr = array()) {
    $url = $this->api_url();
    $attr = array_merge(array(
      'data-cache' => VF_Cache::hash(esc_url_raw($url))
      // 'data-api'   => $url
    ), $attr);
    $value = array_reduce(
      array_keys($attr),
      function($value, $key) use ($attr) {
        return $value .= $key . '="' . esc_attr($attr[$key]) . '" ';
      },
    '');
    echo trim($value);
  }

  /**
   * Return a plugin from the global register
   */
  static public function get_config($post_name = '') {
    global $vf_plugins;
    if ( ! is_array($vf_plugins)) {
      $vf_plugins = array();
    }
    if (array_key_exists($post_name, $vf_plugins)) {
      return $vf_plugins[$post_name];
    }
  }

  /**
   * Return new plugin class instance from `post_name`
   */
  static public function get_plugin($post_name) {
    $config = VF_Plugin::get_config($post_name);
    if ( ! is_array($config)) {
      return;
    }
    // Use extended class if it is recognised
    if (
      array_key_exists('class', $config) &&
      class_exists($config['class'])
    ) {
      return new $config['class']();
    // Otherwise return generic base plugin
    } else {
      return new VF_Plugin($post_name);
    }
  }

  /**
   * Save plugin data to a global option key/value array
   * This option helps keep track of which VF-WP plugins are active
   */
  static public function register($config) {
    // required
    if ( ! is_array($config)) {
      return;
    }
    // required keys
    if (array_diff(
      array('post_name', 'post_title', 'post_type'),
      array_keys($config)
    )) {
      return;
    }
    // Add plugin post if it does not exist
    $plugin = get_page_by_path(
      $config['post_name'],
      OBJECT,
      $config['post_type']
    );

    // Add generic plugin preview block to post content
    $post_content = '';
    if (array_key_exists('__experimental__has_admin_preview', $config)) {
      $post_content = '<!-- wp:vf/plugin {"ver":"1.0.0","ref":"'
        . $config['post_name']
        . '","defaults":1} /-->';
    }

    // Insert if newly activated
    if ( ! $plugin instanceof WP_Post) {
      $plugin = get_post(
          wp_insert_post(array(
          'post_author'  => 1,
          'post_name'    => $config['post_name'],
          'post_title'   => $config['post_title'],
          'post_type'    => $config['post_type'],
          'post_content' => $post_content,
          'post_status'  => 'publish'
        ), true)
      );
    }
    // Ensure plugin post is published and update title
    if ($plugin instanceof WP_Post) {
      wp_update_post(array(
        'ID'           => $plugin->ID,
        'post_title'   => $config['post_title'],
        'post_content' => $post_content,
        'post_status'  => 'publish'
      ));
    }
  }

  /**
   * Remove plugin data from the global option
   */
  static public function deregister($config) {
    // Get plugin post and set to draft (do not delete)
    $plugin = get_page_by_path(
      $config['post_name'],
      OBJECT,
      $config['post_type']
    );
    if ($plugin instanceof WP_Post) {
      wp_update_post(array(
        'ID'          => $plugin->ID,
        'post_status' => 'draft'
      ));
    }
  }

  /**
   * Trigger action hooks before and after a plugin is rendered
   */
  static public function do_actions($plugin, $action) {
    if ( ! $plugin instanceof VF_Plugin) {
      return;
    }
    do_action($action, $plugin);
    if ($plugin->post()) {
      do_action("{$action}/{$plugin->post()->post_name}", $plugin);
    }
  }

  /**
   * Output plugin template
   * specify `$fields` to override default ACF post data
   * specify `$parent` plugin when nested (otherwise global post is reset to main query)
   */
  static public function render($plugin, $fields = null, $parent = null) {
    if ( ! $plugin instanceof VF_Plugin ||
         ! $plugin->post() instanceof WP_Post ||
         ! $plugin->post()->post_status === 'publish'
    ) {
      return;
    }
    if (empty($fields)) {
      $fields = null;
    }
    // User callback method if defined by plugin
    if (method_exists($plugin, 'template_callback')) {
      $plugin->template_callback($fields);
      return;
    }
    // Use template file if defined by plugin
    if ( ! $plugin->template()) {
      return;
    }

    // Flush the ACF cache and setup post meta
    if (is_array($fields)) {
      foreach (array_keys($fields) as $field_name) {
        acf_flush_value_cache($plugin->post()->ID, $field_name);
      }
      acf_setup_meta($fields, $plugin->post()->ID, true);
    }

    // Before actions
    $action = preg_replace('#^vf_#', '', $plugin->post()->post_type);
    VF_Plugin::do_actions($plugin, 'vf/plugin/before_render');
    VF_Plugin::do_actions($plugin, "vf/plugin/{$action}/before_render");

    // Setup globals so plugin template acts as if the main query
    global $post, $vf_plugin;
    $vf_plugin = $plugin;
    $post = $vf_plugin->post();
    setup_postdata($post);

    // Include the plugin template
    include($vf_plugin->template());

    // Flush the ACF cache *again* and reset the post meta
    if (is_array($fields)) {
      acf_reset_meta($post->ID);
      foreach (array_keys($fields) as $field_name) {
        acf_flush_value_cache($post->ID, $field_name);
      }
    }

    // Reset globals to parent plugin (or main template loop)
    if ($parent instanceof VF_Plugin) {
      $vf_plugin = $parent;
      $post = $vf_plugin->post();
      setup_postdata($post);
    } else {
      $vf_plugin = null;
      wp_reset_postdata();
    }

    // After actions
    VF_Plugin::do_actions($plugin, 'vf/plugin/after_render');
    VF_Plugin::do_actions($plugin, "vf/plugin/{$action}/after_render");

  }

  /**
   * Filter: add load path for ACF json
   */
  public function acf_settings_load_json($paths) {
    if ($this->dir()) {
      $paths[] = rtrim($this->dir(), '/\\');
    }
    return $paths;
  }

  /**
   * Action: update save path option for ACF json if this group is edited
   */
  public function acf_update_field_group($group) {
    if ($this->dir()) {
      if ($group['key'] === "group_{$this->config['post_name']}") {
        update_option('vf__acf_save_json', $this->dir());
      }
    }
    return $group;
  }

} // VF_Plugin

endif;

?>
