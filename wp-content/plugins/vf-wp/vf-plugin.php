<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Plugin') ) :

/**
 * Represent a custom plugin for VF blocks, containers, or widgets
 */
class VF_Plugin {

  // Saved config for plugin activation
  private $config;
  // `WP_Post` of custom post type (`vf_container` or `vf_block`)
  protected $post;
  // Plugin file path to index.php extending this class
  protected $file;
  // Plugin contentHub API settings
  protected $API;

  /**
   * `post_name` is used as the unique plugin ID
   */
  public function __construct($post_name = false) {
    if ( ! is_string($post_name)) {
      return;
    }
    $this->setup($post_name);
  }

  /**
   * Setup the plugin instance based on the `post_name` (plugin ID)
   */
  protected function setup($post_name) {
    // Get plugin config
    $config = VF_Plugin::get_config($post_name);
    if ( ! is_array($config) || ! array_key_exists('post_type', $config)) {
      return;
    }

    // Setup plugin post based on config
    $this->post = get_page_by_path($post_name, OBJECT, $config['post_type']);

    // Setup plugin file based on config
    if (array_key_exists('plugin__dirname', $config)) {
      $file = WP_PLUGIN_DIR . "/{$config['plugin__dirname']}/index.php";
      if (file_exists($file)) {
        $this->file = $file;
      }
    }
  }

  /**
   * Initialize a new plugin that is extending this class
   */
  protected function initialize($config = array()) {
    if ( ! is_array($config)) {
      return;
    }
    // Cannot initialize base plugin class
    $config['class'] = get_class($this);
    if ($config['class'] === 'VF_Plugin') {
      return;
    }
    // Plugins extending this class must provide __FILE__
    if ( ! array_key_exists('file', $config) ||
         ! file_exists($config['file'])) {
      return;
    }
    // `post_type` is required (set default)
    if ( ! array_key_exists('post_type', $config)) {
      $config['post_type'] = 'vf_block';
    }

    // Save config for action hooks
    $this->config = $config;

    // Register WP hooks
    register_activation_hook($config['file'], array($this, 'activation_hook'));
    register_deactivation_hook($config['file'], array($this, 'deactivation_hook'));
    add_action('plugins_loaded', array($this, 'plugins_loaded'));
  }

  public function activation_hook() {
    if ( ! is_array($this->config)) return;
    VF_Plugin::register($this->config);
  }

  public function deactivation_hook() {
    if ( ! is_array($this->config)) return;
    VF_Plugin::deregister($this->config);
  }

  /**
   * Action: setup private vars and hooks after the plugin has initialized
   */
  public function plugins_loaded() {
    if ( ! is_array($this->config)) {
      return;
    }
    $this->setup($this->config['post_name']);
    if ( ! $this->post instanceof WP_Post) {
      return;
    }
    if (file_exists("{$this->dir()}group_{$this->post->post_name}.json")) {
      add_action('acf/init', array($this, 'acf_init'));
      add_filter('acf/settings/load_json', array($this, 'acf_settings_load_json'));
      add_action('acf/update_field_group', array($this, 'acf_update_field_group'), 2, 1);
    }
  }

  /**
   * Return plugin post
   */
  public function post() {
    return $this->post;
  }

  /**
   * Return full plugin directory path (with trailing slash)
   */
  public function dir() {
    if ( ! file_exists($this->file)) return;
    return plugin_dir_path($this->file);
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
    if (empty($this->API)) {
      return;
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
    return VF_Cache::get_post($this->api_url());
  }

  /**
   * Return HTML attributes for wrapping element
   */
  public function api_attr(array $attr = array()) {
    $url = $this->api_url();
    $attr = array_merge(array(
      'data-cache' => VF_Cache::hash($url)
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
   * Action: `acf/init`
   */
  public function acf_init() {

    // ACF key used for Gutenberg field group
    $key = 'acf/' . acf_slugify($this->post->post_name);

    // Add ACF group for Gutenberg block editor
    acf_add_local_field_group(array(
      'key'    => "group_{$key}",
      'title'  => "VF {$this->post->post_title} (Gutenberg)",
      'fields' => array(
        array(
          'parent'  => "group_{$key}",
          'key'     => 'field_vf_block_custom',
          'name'    => "vf_block_custom",
          'label'   => 'Customize',
          'type'    => 'true_false',
          'ui'      => true
        ),
        array(
          'parent'  => "group_{$key}",
          'key'     => "field_{$key}_clone",
          'name'    => "{$key}_clone",
          'label'   => $this->post->post_title,
          'type'    => 'clone',
          'clone'   => array("group_{$this->post->post_name}"),
          'display' => 'group',
          'layout'  => 'block',
          'conditional_logic' => array(array(
              array(
                'field'    => 'field_vf_block_custom',
                'operator' => '==',
                'value'    => '1'
              )
            ))
        )
      ),
      'location' => array(
        array(
          array(
            'param'    => 'block',
            'operator' => '==',
            'value'    => $key
          )
        )
      )
    ));

    // Register the Gutenberg block for this plugin
    acf_register_block(
      array(
        'name'     => $this->post->post_name,
        'title'    => $this->post->post_title,
        'icon'     => 'format-aside',
        'supports' => array(
          'align' => false,
          'mode'  => false
        ),
        'category' => 'vf_blocks_content_hub',
        'render_callback' => function($block, $content, $is_preview) use ($key) {
          $custom = get_field('vf_block_custom', $block['id']);
          $fields = $custom ? get_field("{$key}_clone", $block['id']) : null;
          ob_start();
          VF_Plugin::render($this, $fields);
          $html = ob_get_contents();
          ob_end_clean();
          // Use the VF Gutenberg plugin to render iframe previews if available
          global $vf_gutenberg;
          if ($is_preview && isset($vf_gutenberg)) {
            $vf_gutenberg->render_preview_iframe($block, $html);
          } else {
            echo $html;
          }
        }
      )
    );
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
      if ($group['key'] === "group_{$this->post->post_name}") {
        update_option('vf__acf_save_json', $this->dir());
      }
    }
    return $group;
  }

  /**
   * Return a plugin from the global option if registered
   */
  static public function get_config($post_name) {
    $plugins = unserialize(get_option('vf__plugins'));
    if ( ! is_array($plugins)) {
      return;
    }
    if ( ! array_key_exists($post_name, $plugins)) {
      return;
    }
    return $plugins[$post_name];
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
    if (array_key_exists('class', $config) && class_exists($config['class'])) {
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
    // `post_name` is required
    if ( ! array_key_exists('post_name', $config)) {
      return;
    }
    // `post_title` is required
    if ( ! array_key_exists('post_title', $config)) {
      return;
    }
    // `post_type` is required (set default)
    if ( ! array_key_exists('post_type', $config)) {
      $config['post_type'] = 'vf_block';
    }
    // Add plugin post if it does not exist
    $plugin = get_page_by_path($config['post_name'], OBJECT, $config['post_type']);
    if ( ! $plugin) {
      $plugin = get_post(
          wp_insert_post(array(
          'post_author' => 1,
          'post_name'   => $config['post_name'],
          'post_title'  => $config['post_title'],
          'post_type'   => $config['post_type'],
          'post_status' => 'publish'
        ), true)
      );
    }
    // Ensure plugin post is published and update title
    if ($plugin instanceof WP_Post) {
      wp_update_post(array(
        'ID'          => $plugin->ID,
        'post_title'  => $config['post_title'],
        'post_status' => 'publish'
      ));
    }
    // Get plugins option and save new plugin data
    $plugins = unserialize(get_option('vf__plugins'));
    if ( ! is_array($plugins)) {
      $plugins = array();
    }
    $plugins[$config['post_name']] = array(
      'post_type' => $config['post_type']
    );
    // Save plugin class to option
    if (array_key_exists('class', $config) && is_string($config['class'])) {
      $plugins[$config['post_name']]['class'] = $config['class'];
    }
    // Save plugin directory name to option
    if (array_key_exists('file', $config) && file_exists($config['file'])) {
      $plugins[$config['post_name']]['plugin__dirname'] = basename(dirname($config['file']));
    }
    update_option('vf__plugins', serialize($plugins));
  }

  /**
   * Remove plugin data from the global option
   */
  static public function deregister($config) {
    // Get plugins option
    $plugins = unserialize(get_option('vf__plugins'));
    // Validate option
    if ( ! is_array($plugins)) {
      return;
    }
    if ( ! array_key_exists($config['post_name'], $plugins)) {
      return;
    }
    // Remove plugin and save option
    unset($plugins[$config['post_name']]);
    update_option('vf__plugins', serialize($plugins));
    // Get plugin post and set to draft (do not delete)
    $plugin = get_page_by_path($config['post_name'], OBJECT, $config['post_type']);
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
    if ( ! $plugin instanceof VF_Plugin ) return;
    do_action($action, $plugin);
    if ($plugin->post() instanceof WP_Post) {
      do_action("{$action}/post_name={$plugin->post()->post_name}", $plugin);
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
         ! $plugin->post()->post_status === 'publish' ||
         ! $plugin->template()
    ) {
      return;
    }

    // Disable cache so ACF doesnt get confused
    // `wp_cache_flush` is too severe
    // `acf_delete_cache` is tricky for nested fields
    if (function_exists('acf_disable_cache')) {
      acf_disable_cache();
    }


    $action = str_replace('vf_', 'vf/', $plugin->post()->post_type);
    VF_Plugin::do_actions($plugin, 'vf/plugin/before_render');
    VF_Plugin::do_actions($plugin, "{$action}/before_render");

    // Setup globals so plugin template acts as if the main query
    global $post, $vf_plugin;
    $vf_plugin = $plugin;
    $post = $vf_plugin->post();
    setup_postdata($post);

    // Override fields from Gutenberg block or clone field
    if (is_array($fields)) {
      if (function_exists('acf_setup_postdata')) {
        acf_setup_postdata($fields, $post->ID, true);
      }
    }

    // Include the plugin template
    include($vf_plugin->template());

    // Reset local meta and cached values
    if (function_exists('acf_reset_postdata')) {
      acf_reset_postdata($post->ID);
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

    VF_Plugin::do_actions($plugin, 'vf/plugin/after_render');
    VF_Plugin::do_actions($plugin, "{$action}/after_render");

    // Re-enable the cache
    if (function_exists('acf_enable_cache')) {
      acf_enable_cache();
    }
  }

} // VF_Plugin

endif;

?>
