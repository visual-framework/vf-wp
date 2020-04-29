<?php
/*
Plugin Name: VF-WP Gutenberg
Description: Adds Visual Framework support and blocks to the Gutenberg editor.
Version: 0.2.0
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/
/**
 * Documentation for blocks:
 * https://developer.wordpress.org/block-editor/developers/block-api/
 *
 * Gutenberg block library source:
 * https://github.com/WordPress/gutenberg/tree/master/packages/block-library
 * https://github.com/WordPress/gutenberg/tree/master/packages
 *
 * WordPress does not make it practical to edit the default block templates.
 * To disable default blocks and roll our own seems ill-advised.
 * Instead I'm using a filter to adapt the HTML.
 *
 * The `render_block` filter is called via `do_blocks` function which itself
 * is a default filter for `the_content`.
 *
 * https://github.com/WordPress/WordPress/blob/5.0-branch/wp-includes/blocks.php#L239
 * https://github.com/WordPress/WordPress/blob/5.0-branch/wp-includes/default-filters.php#L159
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg') ) :

class VF_Gutenberg {

  private $settings;

  // List of compatible core blocks
  private $compatible = array();

  /**
   * Attributes from the Gutenberg block that should be ignored
   */
  private $protected_attrs = array(
    'ver',
    'mode',
    'style',
    'defaults'
  );

  /**
   * ACF field types that are supported by Gutenberg blocks
   */
  private $supported_fields = array(
    'checkbox',
    'date_picker',
    'email',
    'number',
    'range',
    'radio',
    'select',
    'taxonomy',
    'text',
    'textarea',
    'true_false',
    'url',
    'wysiwyg'
  );

  /**
   * Store block data during `render_block` action
   */
  private $fields;

  /**
   * Convert a Gutenberg block name to a VF_Plugin post name
   * e.g. "vf/latest-posts" to "vf_latest_posts"
   */
  static function name_block_to_post($str, $separator = '_') {
    $str = str_replace('vf/container-', 'vf/', $str);
    $str = preg_replace('/[^\w]/', $separator, $str);
    return $str;
  }

  /**
   * Convert a VF_Plugin post name to a Gutenberg block name
   * e.g. "vf_latest_posts" to "vf/latest-posts"
   */
  static function name_post_to_block($str, $prefix = '') {
    $prefix = empty($prefix) ? '' : "{$prefix}-";
    return preg_replace(
      array('/[\W_]/', '/(^[\w]+)-/'),
      array('-', '$1/' . $prefix),
      $str
    );
  }

  /**
   * Return true if template has grid wrappers and should not be contained
   */
  static function is_block_standalone($block_name) {
    $post_name = VF_Gutenberg::name_block_to_post($block_name);
    if (class_exists('VF_Plugin')) {
      $plugin = VF_Plugin::get_plugin($post_name);
      if ($plugin) {
        return $plugin->is_template_standalone();
      }
    }
    return false;
  }

  function __construct() {
    // Do nothing...
  }

  function initialize() {
    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );
    add_action(
      'enqueue_block_editor_assets',
      array($this, 'enqueue_block_editor_assets')
    );
    add_filter(
      'wp_ajax_vf/gutenberg/fetch_block',
      array($this, 'ajax_fetch_block')
    );
    add_filter(
      'wp_ajax_vf/gutenberg/fetch_terms',
      array($this, 'ajax_fetch_terms')
    );
    add_filter(
      'render_block',
      array($this, 'render_block'),
      10, 2
    );
    add_filter(
      'render_block',
      array($this, 'render_block_compatible'),
      10, 2
    );

    // ACF options
    include_once('includes/settings.php');
    $this->settings = new VF_Gutenberg_Settings();

    // Register core transforms
    include_once('includes/core/core-colors.php');
    include_once('includes/core/core-button.php');
    include_once('includes/core/core-file.php');
    include_once('includes/core/core-image.php');
    include_once('includes/core/core-paragraph.php');
    include_once('includes/core/core-quote.php');
    include_once('includes/core/core-separator.php');
  }

  /**
   * Register an array of core compatible block callbacks
   */
  function add_compatible($keys, callable $callback) {
    if ( ! is_array($keys)) {
      $keys = array($keys);
    }
    foreach ($keys as $key) {
      if (is_string($key)) {
        if ( ! array_key_exists($key, $this->compatible)) {
          $this->compatible[$key] = array();
        }
        $this->compatible[$key][] = $callback;
      }
    }
  }

  /**
   * Action: `block_categories`
   */
  function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => 'vf/core',
          'title' => __('EMBL – Visual Framework', 'vfwp'),
          'icon'  => null
        )
      ),
      $categories
    );
  }

  /**
   * Enqueue WP Admin CSS and JavaScript
   */
  function enqueue_block_editor_assets() {
    $vfwp_blocks = get_template_directory_uri()
      . '/assets/assets/vfwp-gutenberg-blocks/vfwp-gutenberg-blocks.css';
    wp_enqueue_style(
      'vfwp-gutenberg-blocks',
      $vfwp_blocks,
      array(),
      false,
      'all'
    );
    wp_register_script(
      'vf-blocks',
      plugins_url(
        '/assets/vf-blocks' . (vf_debug() ? '' : '.min') .  '.js',
        __FILE__
      ),
      array('wp-editor', 'wp-blocks'),
      false,
      true
    );
    /**
     * "Localize" script by making config available
     * in the global `vfGutenberg` object
     */
    $prefix = '<wbr style="display:block;clear:both;height:0;">';
    $suffix = $prefix;

    // Inline stylesheets to prefix
    $dir = get_template_directory();
    $stylesheets = array(
      "{$dir}/assets/css/styles.css",
      "{$dir}/assets/assets/vfwp-gutenberg-iframe/vfwp-gutenberg-iframe.css"
    );
    foreach ($stylesheets as $path) {
      if (file_exists($path)) {
        ob_start();
?>
<style type="text/css">
<?php include($path); ?>
</style>
<?php
        $prefix .= ob_get_contents();
        ob_end_clean();
      }
    }

    global $post;

    $config = array(
      'renderPrefix' => $prefix,
      'renderSuffix' => $suffix,
      'coreOptin'    => 1,
      'plugins' => $this->get_config_plugins(),
      'nonce'   => wp_create_nonce("vf_nonce_{$post->ID}"),
      'postId'  => $post->ID
    );

    wp_localize_script('vf-blocks', 'vfGutenberg', $config);
    wp_enqueue_script('vf-blocks');
  }

  /**
   * Called from AJAX handlers to validate the nonce
   */
  function ajax_validate_nonce() {
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    $post_id = isset($_POST['postId']) ? intval($_POST['postId']) : 0;
    if ( ! wp_verify_nonce($nonce, "vf_nonce_{$post_id}")) {
      wp_send_json_error();
    }
  }

  /**
   * Handle AJAX request to render block preview
   */
  function ajax_fetch_block() {
    $this->ajax_validate_nonce();
    if (!isset($_POST['name'])) {
      wp_send_json_error();
    }
    $html = $this->render_block('', array(
      'blockName' => $_POST['name'],
      'attrs'     => isset($_POST['attrs']) ? $_POST['attrs'] : false
    ));
    wp_send_json_success(
      array(
        'hash' => hash('crc32', $html),
        'html' => $html
      )
    );
  }

  /**
   * Handle AJAX request to fetch taxonomy terms
   */
  function ajax_fetch_terms() {
    $this->ajax_validate_nonce();
    $taxonomy = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
    if ( ! taxonomy_exists($taxonomy)) {
      wp_send_json_error();
    }
    $terms = get_terms(
      array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false
      )
    );
    $terms = array_map(function($term) {
      return array(
        'name'    => html_entity_decode($term->name),
        'term_id' => $term->term_id,
      );
    }, $terms);
    wp_send_json_success(
      array(
        'terms' => $terms
      )
    );
  }

  /**
   * Template function for Visual Framework block templates
   * accessible during the `render_block` action
   */
  function get_field($name, $default = false) {
    if ( ! is_array($this->fields)) {
      return $default;
    }
    if (isset($this->fields[$name])) {
      return $this->fields[$name];
    }
  }

  /**
   * Render VF Plugin blocks
   * Filter `render_block`
   */
  function render_block($html, $block) {
    if ( ! class_exists('VF_Plugin')) {
      return $html;
    }
    if ( ! preg_match('/^vf\//', $block['blockName'])) {
      return $html;
    }
    if (
      ! array_key_exists('attrs', $block) ||
      ! is_array($block['attrs'])
    ) {
      $block['attrs'] = array();
    }
    // setup empty custom fields
    $this->fields = array();

    // Get plugin name from block
    $post_name = VF_Gutenberg::name_block_to_post($block['blockName']);

    // Get true name from `ref` attribute for generic preview
    if (
      $post_name === 'vf_plugin' &&
      array_key_exists('ref', $block['attrs'])
    ) {
      $post_name = $block['attrs']['ref'];
      $block['attrs']['defaults'] = 0;
    }

    // check for matching plugin
    $vf_plugin = VF_Plugin::get_plugin($post_name);

    // setup fields with block attributes
    foreach ($block['attrs'] as $key => $value) {
      // Ignore customization and use ACF settings
      if ($key === 'defaults' && intval($value) === 1) {
        $this->fields = null;
        break;
      }
      if (in_array($key, $this->protected_attrs)) {
        continue;
      }
      if ($key === 'className') {
        if (preg_match('/is-style-([^\s"]+)/', $value, $matches)) {
          $this->fields["{$post_name}_style"] = $matches[1];
        }
      }
      // Prefix field key with `post_name` if not already
      if (strpos($key, 'vf_') !== 0) {
        $this->fields["{$post_name}_{$key}"] = $value;
      } else {
        $this->fields[$key] = $value;
      }
    }

    ob_start();
    $rendered = false;
    // use template render
    $render = $this->get_field("{$post_name}_render");
    if ($render) {
      echo $render;
      $rendered = true;
    // render with matching plugin
    } else if ($vf_plugin) {
      VF_Plugin::render($vf_plugin, $this->fields);
      $rendered = true;
    // otherwise render with template
    } else {
      $path = str_replace('_', '-', $post_name);
      $path = "includes/templates/{$path}.php";
      $path = plugin_dir_path(__FILE__) . $path;
      if (file_exists($path)) {
        include($path);
        $rendered = true;
      }
    }
    if ($rendered) {
      $html = ob_get_contents();
    }
    ob_end_clean();
    $this->fields = null;
    return $html;
  }

  /**
   * Edit compatible core blocks to use VF markup
   * Wrap other core blocks in `vf-content` class
   */
  public function render_block_compatible($html, $block) {
    if (array_key_exists($block['blockName'], $this->compatible)) {
      $callbacks = $this->compatible[ $block['blockName'] ];
      foreach ($callbacks as $fn) {
        $html = call_user_func($fn, $html, $block);
      }
    }
    return $html;
  }

  /**
   * Return enabled plugins and their fields for the Gutenberg editor
   */
  private function get_config_plugins() {
    $config = array();
    if ( ! class_exists('VF_Plugin')) {
      return $config;
    }
    global $vf_plugins;
    if (empty($vf_plugins)) {
      return $config;
    }
    foreach ($vf_plugins as $post_name => $value) {
      $plugin = VF_Plugin::get_plugin($post_name);
      // add prefix for containers to avoid conflicts
      $block_name = VF_Gutenberg::name_post_to_block(
        $post_name, $plugin->is_container() ? 'container' : ''
      );
      // block settings
      $data = array(
        'id'          => $plugin->post()->ID,
        'title'       => $plugin->post()->post_title,
        'category'    => VF_Blocks::block_category(),
        'isBlock'     => $plugin->is_block(),
        'isContainer' => $plugin->is_container(),
        'fields'      => array(),
      );
      // map ACF fields to supported Gutenberg controls
      $fields = acf_get_fields("group_{$post_name}");
      if ( ! is_array($fields)) {
        $fields = array();
      }
      foreach ($fields as $field) {
        $type = $field['type'];
        if ( ! in_array($type, $this->supported_fields)) {
          continue;
        }
        $name = preg_replace(
          '#^' . preg_quote($post_name) . '_#',
          '', $field['name']
        );
        if (in_array($name, $this->protected_attrs)) {
          continue;
        }
        $data['fields'][] = $this->map_acf_field_to_attr($name, $type, $field);
      }
      // Set container category
      // Hide by default and disable custom fields
      if ($plugin->is_container()) {
        $data['preview']  = get_permalink($data['id']);
        $data['category'] = VF_Containers::block_category();
        $data['fields']   = array();
        $data['supports'] = array(
          'customClassName' => false,
          'reusable'        => false
        );
      }
      if ($plugin->is_deprecated()) {
        $data['supports']['inserter'] = false;
      }
      $config[$block_name] = $data;
    }
    // Add generic plugin for previews
    $config['vf/plugin'] = array(
      'title'      => __('Preview', 'vfwp'),
      'category'   => VF_Blocks::block_category(),
      'fields'     => [],
      'supports'   => array(
        'customClassName' => false,
        'inserter'        => false,
        'reusable'        => false
      ),
      'attributes' => array(
        'ref' => array(
          'type' => 'string'
        )
      )
    );
    return $config;
  }

  /**
   * Map ACF field data to Gutenberg block attributes
   */
  private function map_acf_field_to_attr($name, $type, $field) {
    $attr = array(
      'acf'     => $type,
      'control' => $type,
      'name'    => $name,
      'type'    => 'string',
      'label'   => html_entity_decode($field['label']),
      'default' => '',
    );
    if (in_array($type, array(
      'checkbox'
    ))) {
      $attr['type'] = 'array';
      $attr['default'] = array();
    }
    if (in_array($type, array(
      'number'
    ))) {
      $attr['type'] = 'number';
    }
    if (in_array($type, array(
      'range',
      'taxonomy',
      'true_false'
    ))) {
      $attr['type'] = 'integer';
    }
    if (in_array($type, array(
      'number',
      'range'
    ))) {
      $attr['min'] = intval($field['min']);
      $attr['max'] = intval($field['max']);
      $attr['step'] = intval($field['step']);
    }
    if (in_array($type, array(
      'checkbox',
      'radio',
      'select'
    ))) {
      $attr['options'] = array();
      foreach ($field['choices'] as $k => $v) {
        $attr['options'][] = array(
          'label' => html_entity_decode($v),
          'value' => $k
        );
      }
    }
    if ($type === 'taxonomy') {
      $attr['taxonomy'] = $field['taxonomy'];
    }
    return $attr;
  }

  /**
   * Callback for `acf_register_block_type` to render within iframe
   * $args = array($block, $content, $is_preview, $post_id)
   */
  static function acf_render_template($args, $template) {
    $is_preview = $args[2];
    if ( ! file_exists($template)) {
      if ($is_preview) {
        echo __('Block template missing.', 'vfwp');
      }
      return;
    }
    $block = $args[0];
    // Capture the block template
    ob_start();
    // Output head include for preview
    if ($is_preview) {
      get_template_part('partials/head');
    }
    // Load template
    include($template);
    // Output foot include for preview
    if ($is_preview) {
      get_template_part('partials/foot');
    }
    $html = ob_get_contents();
    ob_end_clean();
    // Render block if not admin preview
    if ($is_preview !== true) {
      echo $html;
      return;
    }
    $id = "vfwp_{$block['id']}";
?>
<script>
window.<?php echo $id; ?> = function() {
  const iframe = document.getElementById('<?php echo $id; ?>');
  iframe.vfActive = true;
  var doc = iframe.contentWindow.document;
  doc.body.innerHTML = <?php echo json_encode($html); ?>;
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.innerHTML = `
window.vfResize = () => {
  requestAnimationFrame(() => {
    window.parent.postMessage({
        id: '<?php echo $id; ?>',
        height: document.documentElement.scrollHeight
      }, '*'
    );
  });
};
window.addEventListener('resize', window.vfResize);
setTimeout(window.vfResize, 100);
`;
  doc.body.appendChild(script);
};
</script>
<div class="vf-block" data-editing="false" data-loading="false">
  <div class="vf-block__view">
    <iframe
      class="vf-block__iframe"
      id="<?php echo $id; ?>"
      onload="<?php echo "setTimeout(()=>{{$id}();}, 1);"; ?>"
      scrolling="no"></iframe>
  </div>
</div>
<?php
  }

} // VF_Gutenberg

function vf_gutenberg() {
  global $vf_gutenberg;
  if ( ! isset($vf_gutenberg)) {
    $vf_gutenberg = new VF_Gutenberg();
    $vf_gutenberg->initialize();
  }
  return $vf_gutenberg;
}

vf_gutenberg();

endif;

?>
