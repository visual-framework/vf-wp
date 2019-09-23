<?php
/*
Plugin Name: VF-WP Gutenberg
Description: Adds Visual Framework support and blocks to the Gutenberg editor.
Version: 0.0.1
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
  static function name_block_to_post($str) {
    return preg_replace('/[^\w]/', '_', $str);
  }

  /**
   * Convert a VF_Plugin post name to a Gutenberg block name
   * e.g. "vf_latest_posts" to "vf/latest-posts"
   */
  static function name_post_to_block($str) {
    return preg_replace(
      array('/[\W_]/', '/(^[\w]+)-/'),
      array('-', '$1/'),
      $str
    );
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
    include_once('includes/core/core-button.php');
    include_once('includes/core/core-file.php');
    include_once('includes/core/core-image.php');
    include_once('includes/core/core-quote.php');
    include_once('includes/core/core-separator.php');

    $this->_deprecated_init();
  }

  /**
   * Register an array of core compatible block callbacks
   */
  function add_compatible(string $key, callable $callback) {
    if ( ! array_key_exists($key, $this->compatible)) {
      $this->compatible[$key] = $callback;
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
          'title' => __('Visual Framework (core)', 'vfwp'),
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
    wp_enqueue_style(
      'vf-blocks',
      plugins_url('/assets/vf-blocks.css', __FILE__),
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
      array('iframe-resizer', 'wp-editor', 'wp-blocks'),
      false,
      true
    );
    /**
     * "Localize" script by making config available
     * in the global `vfGutenberg` object
     */
    global $post;
    $config = array(
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
    $html = '';
    $stylesheets = array();
    if (function_exists('vf_get_stylesheet')) {
      $stylesheets[] = vf_get_stylesheet();
    }
    $stylesheets[] = plugins_url('/assets/vf-iframe.css', __FILE__);
    foreach ($stylesheets as $href) {
      $html .= '<link onload="window.vfResize();" rel="stylesheet" href="' . $href . '">';
    }
    // render block
    if (isset($_POST['name'])) {
      $html .= $this->render_block('', array(
        'blockName' => $_POST['name'],
        'attrs' => isset($_POST['attrs']) ? $_POST['attrs'] : false
      ));
    }
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

    // check for matching plugin
    $post_name = VF_Gutenberg::name_block_to_post($block['blockName']);
    $vf_plugin = VF_Plugin::get_plugin($post_name);

    // setup fields with block attributes
    $this->fields = array();
    if (is_array($block['attrs'])) {
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
        $this->fields["{$post_name}_{$key}"] = $value;
      }
    }
    $rendered = false;
    ob_start();
    // render with matching plugin
    if ($vf_plugin) {
      VF_Plugin::render($vf_plugin, $this->fields);
      $rendered = true;
    // otherwise render with template
    } else {
      $path = str_replace('_', '-', $post_name);
      $path = "includes/vf-core/{$path}.php";
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
      $callback = $this->compatible[ $block['blockName'] ];
      $html = call_user_func($callback, $html, $block);
    } else {
      if (strpos($block['blockName'], 'core/') === 0) {
        $html = '<div class="vf-content">' . $html . '</div>';
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
    $plugins = VF_Plugin::get_config();
    if (empty($plugins)) {
      return $config;
    }
    foreach ($plugins as $post_name => $value) {
      $plugin = VF_Plugin::get_plugin($post_name);
      if ( ! $plugin->is_block()) {
        continue;
      }
      // enabled basic support
      $block_name = VF_Gutenberg::name_post_to_block($post_name);
      $config[$block_name] = true;
      // map ACF fields to supported Gutenberg controls
      $fields = acf_get_fields("group_{$post_name}");
      if ( ! is_array($fields)) {
        continue;
      }
      $data = array(
        'id'     => $plugin->post()->ID,
        'title'  => $plugin->post()->post_title,
        'fields' => array(),
      );
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
      $config[$block_name] = $data;
    }
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
      'email'
    ))) {
      $attr['control'] = 'text';
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
    if ($type === 'wysiwyg') {
      $attr['control'] = 'rich';
    }
    return $attr;
  }

  /**
   * WARNING: deprecated
   */

  private $_deprecated_blocks = array();

  private function _deprecated_init() {
    include_once('includes/deprecated/vf-block.php');
    include_once('includes/deprecated/vf-box.php');
    include_once('includes/deprecated/vf-lede.php');
    include_once('includes/deprecated/vf-activity.php');

    add_filter(
      'block_categories',
      array($this, '_deprecated_block_categories'),
      10, 2
    );
    add_action(
      'acf/init',
      array($this, '_deprecated_acf_init')
    );
    add_action(
      'enqueue_block_editor_assets',
      array($this, '_deprecated_enqueue_block_editor_assets')
    );
    add_action(
      'admin_head',
      array($this, '_deprecated_admin_head')
      , 10
    );
    add_action(
      'admin_footer',
      array($this, '_deprecated_admin_footer')
      , 10
    );
    add_action(
      'admin_notices',
      array($this, '_deprecated_admin_notices')
    );
  }

  /**
   * WARNING: deprecated method
   * Action: `admin_notices`
   */
  function _deprecated_admin_notices() {
    if ( ! function_exists('get_current_screen')) {
      return;
    }
    $screen = get_current_screen();
    // if ($screen->id === 'edit-vf_block') {
    //   printf('<div class="%1$s"><p><b>%2$s</b> %3$s</p></div>',
    //     esc_attr('notice notice-warning'),
    //     esc_html__('These blocks are deprecated.', 'vfwp'),
    //     esc_html__('Please use the native blocks within the Gutenberg page editor.', 'vfwp')
    //   );
    // }
  }

  /**
   * WARNING: deprecated method
   * Register an array of custom VF blocks
   */
  function _deprecated_add_block(VF_Gutenberg_Block $instance) {
    $key = 'acf/' . $instance->key();
    if ( ! array_key_exists($key, $this->_deprecated_blocks)) {
      $this->_deprecated_blocks[$key] = $instance;
    }
  }

  /**
   * WARNING: deprecated method
   * Action: `acf/init`
   * Iterate over blocks and register them
   */
  function _deprecated_acf_init() {
    foreach ($this->_deprecated_blocks as $name => $instance) {

      // Register Gutenberg block with ACF
      acf_register_block(array(
        'icon'            => 'no',
        'name'            => $instance->key(),
        'title'           => $instance->title(),
        'category'        => 'vf_blocks_standalone',
        'supports'        => array(
          'inserter' => function_exists('vf_debug') && vf_debug()
        ),
        'render_callback' => array($this, '_deprecated_render_callback')
      ));

      // Register ACF field group
      acf_add_local_field_group(
        array(
          'key' => "group_{$instance->key()}",
          'title' => $instance->title(),
          'fields' => $instance->fields(),
          'location' => array(
            array(
              array(
                'param' => 'block',
                'operator' => '==',
                'value' => "acf/{$instance->key()}",
              ),
            ),
          ),
          'menu_order' => 0,
          'position' => 'normal',
          'style' => 'default',
          'label_placement' => 'top',
          'instruction_placement' => 'label',
          'hide_on_screen' => '',
          'active' => 1,
          'description' => '',
        )
      );
    }
  }

  /**
   * WARNING: deprecated method
   * Render callback for ACF Gutenberg blocks
   */
  function _deprecated_render_callback($block, $content, $is_preview) {
    if ( ! array_key_exists($block['name'], $this->_deprecated_blocks)) {
      return;
    }
    $instance = $this->_deprecated_blocks[ $block['name'] ];
    $html = $instance->render($block);
    if ( $is_preview) {
      $this->_deprecated_render_preview_iframe($block, $html);
    } else {
      echo $html;
    }
  }

  /**
   * WARNING: deprecated method
   * Render block within an iframe
   */
  function _deprecated_render_preview_iframe($block, $html) {
    // Prepend custom CSS
    $css = plugins_url('/assets/vf-iframe.css', __FILE__);
    $pre = array(
    '<link rel="stylesheet" href="' . $css . '">'
    );
    // Prepend Visual Framework CSS
    if (function_exists('vf_get_stylesheet')) {
      $pre[] = '<link rel="stylesheet" href="' . vf_get_stylesheet() . '">';
    }

    // Add deprecated warning
    ob_start();
    include 'includes/deprecated/warning.php';
    $pre[] = ob_get_contents();
    ob_end_clean();

    $html = implode("\n", $pre) . $html;

    $js = plugins_url('/includes/deprecated/iframeResizer.contentWindow.min.js', __FILE__);
    $id = "vfGutenberg_{$block['id']}";
    $attr = array(
      "id=\"{$id}\"",
      "onload=\"setTimeout(function(){{$id}(document.getElementById('{$id}'));}, 1);\"",
      'scrolling="no"',
      'style="border: 0; min-width: 100%; pointer-events: none;"'
    );
?>
<script>
  window.<?php echo $id; ?> = function(iframe) {
    try {
      window.vfGutenbergIFrame(
        iframe,
        <?php echo json_encode($html); ?>,
        <?php echo json_encode($js); ?>
      );
    } catch(e) {}
  };
</script>
<iframe <?php echo implode(' ', $attr); ?>></iframe>
<?php
  }

  /**
   * WARNING: deprecated method
   */
  function _deprecated_enqueue_block_editor_assets() {
    wp_enqueue_script(
      'iframe-resizer',
      plugins_url('/includes/deprecated/iframeResizer.min.js', __FILE__),
      false,
      true
    );
  }

  /**
   * WARNING: deprecated method
   * Output inline styles for Visual Framework Gutenberg blocks
   */
  function _deprecated_admin_head() {
?>
<style>
.wp-block[data-type^="acf/vf-"] {
  max-width: 780px;
}
</style>
<?php
  }

  /**
   * WARNING: deprecated method
   */
  function _deprecated_admin_footer() {
?>
<script>
(function() {
  window.vfGutenbergIFrame = function(iframe, html, js) {
    var body = iframe.contentWindow.document.body;
    body.innerHTML = html;
    // append iframeResizer content window script
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = js;
    script.onload = function() {
      script.onload = null;
      var timeout = setInterval(function() {
        if (!iframe.iFrameResizer) {
          return clearInterval(timeout);
        }
      }, 1000);
    };
    body.appendChild(script);
    // start iframeResizer
    window.iFrameResize({log: false, checkOrigin: false}, iframe);
  };
})();
</script>
<?php
  }

  /**
   * WARNING: deprecated method
   * Filter `block_categories`
   */
  function _deprecated_block_categories($categories, $post) {
    return array_merge(
      $categories,
      array(
        array(
          'slug'  => 'vf_blocks_standalone',
          'title' => __('Visual Framework (deprecated)', 'vfwp'),
          'icon'  => null
        )
      )
    );
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
