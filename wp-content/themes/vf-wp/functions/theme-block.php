<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Block') ) :

class VFWP_Block {

  private $file = null;
  private $config = null;
  private $is_containerable = false;
  private $debug = array();

  public function __construct($file) {
    $this->file = $file;
    $this->config = VFWP_Block::default_config();

    // Add hooks
    add_action('acf/init',
      array($this, 'acf_init')
    );
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
    add_filter('block_type_metadata',
      array($this, 'block_type_metadata')
    );
    if (vf_debug()) {
      add_action('admin_notices',
        array($this, 'admin_notices')
      );
    }
  }

  /**
   * Return block name
   */
  public function name() {
    return isset($this->config['name']) ? $this->config['name'] : null;
  }

  /**
   * Return true if this block has container layout
   */
  public function is_containerable() {
    return isset($this->config['vfwp']['containerable'])
      && (bool) $this->config['vfwp']['containerable'];
  }

  public function setup_containerable() {
    $this->is_containerable = true;
  }

  /**
   * Return block render template path
   */
  public function get_template() {
    // Allow themes to provide a custom template
    $template = locate_template(
      "blocks/{$this->name()}.php",
      false, false
    );
    if ( ! file_exists($template)) {
      $template = locate_template(
        "blocks/{$this->name()}/template.php",
        false, false
      );
    }
    return $template;
  }

  /**
   * Action: `acf/init`
   */
  public function acf_init() {
    // Load config from block.json
    $json = plugin_dir_path($this->file) . 'block.json';
    if (file_exists($json)) {
      $this->config = json_decode(file_get_contents($json), true);
      if ( ! is_array($this->config)) {
        $this->debug[] = array(
          'type' => 'error',
          'message' => sprintf(
            __('Invalid Block JSON: <code>%s</code>', 'vfwp'),
            $json
          )
        );
        return;
      }
    } else {
      // Otherwise fallback to PHP config
      $this->config = $this->get_config();
      $json = false;
    }

    // Basic validation
    if ( ! $this->name()) {
      $this->debug[] = array(
        'type' => 'error',
        'message' => sprintf(
          __('Block missing name: <code>%s</code>', 'vfwp'),
          $this->file
        )
      );
      return;
    }

    $this->config = VFWP_Block::merge_config(
      VFWP_Block::default_config(),
      $this->config
    );

    // Decide which template to use
    if (isset($this->config['acf']['renderTemplate'])) {
      $renderTemplate = plugin_dir_path($this->file) . $this->config['acf']['renderTemplate'];
      $this->config['acf']['renderTemplate'] = $renderTemplate;
    } else {
      $this->config['acf']['renderTemplate'] = $this->get_template();
    }

    // TODO: remove?
    if (isset($this->config['supports']['vf/innerBlocks'])) {
      $this->config['supports']['jsx'] = boolval($this->config['supports']['vf/innerBlocks']);
    }

    // TODO: remove?
    // Support legacy setting
    if ($this->is_containerable) {
      $this->config['vfwp']['containerable'] = true;
    }

    // Setup render callback using VF Gutenberg plugin or fallback
    $callback = function() {
      $args = func_get_args();
      $template = $this->config['acf']['renderTemplate'];
      // Render block in iFrame by default if plugin exists
      $is_iframe = class_exists('VF_Gutenberg');
      // Disable iFrame render if support is disabled
      if (
        isset($this->config['supports']['vf/renderIFrame']) &&
        $this->config['supports']['vf/renderIFrame'] === false
      ) {
        $is_iframe = false;
      }
      if ($is_iframe) {
        VF_Gutenberg::acf_render_template($args, $template);
      } else {
        $block = $args[0];
        $is_preview = $args[2];
        include($template);
      }
    };

    if ($json) {

      // Use modern block.json registration
      register_block_type(
        $json,
        array(
          'render_callback' => $callback
        )
      );
    } else {
      // Use legacy ACF registration
      acf_register_block_type(array_merge(
        $this->config,
        array(
          'render_callback' => $callback
        )
      ));
    }

    // Add "Full-width Layout" settings for container blocks
    if ($this->is_containerable()) {
      acf_add_local_field_group(array(
        'key'    => uniqid('group_'),
        'title'  => __('Block Settings', 'vfwp'),
        'fields' => array(
          array(
            'key'           => 'field_5ec3be037f09c',
            'label'         => __('Full-width Layout', 'vfwp'),
            'name'          => 'is_container',
            'type'          => 'true_false',
            'instructions'  => __('Display using a full-width layout on supported templates.', 'vfwp'),
            'default_value' => 1,
            'ui'            => 1
          ),
        ),
        'location' => array(
          array(
            array(
              'param'    => 'block',
              'operator' => '==',
              'value'    => "acf/{$this->name()}",
            ),
          ),
        ),
        'menu_order' => 100,
      ));
    }

    // Add hooks
    add_filter(
      "vf/theme/content/is_block_wrapped/name=acf/{$this->name()}",
      array($this, 'is_block_wrapped'),
      10, 4
    );
  }

  /**
   * Filter block registration to ensure default config
   */
  public function block_type_metadata($metadata) {
    if ($this->name() && $metadata['name'] === "acf/{$this->name()}") {
      $metadata = VFWP_Block::merge_config($this->config, $metadata);
    }
    return $metadata;
  }

  /**
   * Filter whether to wrap block in `vf-content`
   */
  public function is_block_wrapped($is_wrap, $block_name, $blocks, $i) {
    // Always wrapped if container layout is not supported
    if ( ! $this->is_containerable()) {
      return true;
    }
    // Check block attributes for container toggle
    if (strpos($blocks[$i], '<!--(is_container)-->') !== false) {
      return false;
    }
    return true;
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    if ( ! empty($this->file)) {
      $paths[] = plugin_dir_path($this->file);
    }
    return $paths;
  }

  /**
   * Handle developer admin notices
   */
  public function admin_notices() {
    foreach ($this->debug as $notice) {
      vf_log($notice['message']);
      $type = isset($notice['type']) ? $notice['type'] : 'notice';
?>
  <div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
    <p><?php echo wp_kses_post($notice['message']); ?></p>
  </div>
<?php
    }
  }

  // Return defaults for block.json
  static function default_config() {
    return array(
      'category' => 'vf/wp',
      'vfwp' => array(
        'containerable' => false
      ),
      'acf' => array(
        'mode' => 'preview'
      ),
      'supports' => array(
        'jsx'             => false,
        'align'           => false,
        'customClassName' => false
      )
    );
  }

  // Merge all properties of $a into $b
  static function merge_config($a, $b) {
    foreach ($a as $k => $v) {
      if (is_array($v)) {
        if ( ! isset($b[$k])) {
          $b[$k] = array();
        }
        $b[$k] = self::merge_config($v, $b[$k]);
      } else {
        if ( ! isset($b[$k])) {
          $b[$k] = $v;
        }
      }
    }
    return $b;
  }

} // VFWP_Block


endif;

?>
