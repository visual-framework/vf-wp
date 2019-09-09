<?php
/*
Plugin Name: Visual Framework (Gutenberg)
Description: Adds Visual Framework patterns to the Gutenberg editor and adapt default blocks.
Version: 0.0.1
Author: EMBL-EBI Web Development
Plugin URI: https://git.embl.de/grp-stratcom/vf-wp
Text Domain: vfwp

Documentation for new block templates:
https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-templates/

Default Gutenberg block library source:
https://github.com/WordPress/gutenberg/tree/master/packages/block-library

*/

/**
 * WordPress does not make it practical to edit the default block templates.
 * To disable default blocks and roll our own seems ill-advised.
 * Instead I'm using a filter to adapt the HTML.
 *
 * The `render_block` filter is called via `do_blocks` function which itself
 * is a default filter for `the_content`.
 *
 * https://github.com/WordPress/WordPress/blob/5.0-branch/wp-includes/blocks.php#L239
 * https://github.com/WordPress/WordPress/blob/5.0-branch/wp-includes/default-filters.php#L159
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg') ) :

class VF_Gutenberg {

  // Custom block category
  private $category = 'vf_blocks_standalone';

  // List of compatible core blocks
  private $compatible = array();

  // List of custom VF blocks
  private $blocks = array();

  private $settings;

  function __construct() {
    // Do nothing...
  }

  function initialize() {
    add_filter(
      'render_block',
      array($this, 'render_block'),
      10, 2
    );
    add_action(
      'admin_enqueue_scripts',
      array($this, 'admin_enqueue_scripts')
    );
    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );
    add_filter(
      'wp_ajax_vf_gutenberg_fetch_block',
      array($this, 'fetch_block')
    );

    // TODO: remove deprecated filters
    add_filter(
      'block_categories',
      array($this, '_deprecated_block_categories'),
      10, 2
    );
    add_action(
      'acf/init',
      array($this, 'acf_init')
    );
    add_action(
      'admin_head',
      array($this, 'admin_head')
      , 10
    );
    add_action(
      'admin_notices',
      array($this, 'admin_notices')
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

    // TODO: remove deprecated blocks
    // Register custom blocks
    include_once('includes/deprecated/vf-block.php');
    include_once('includes/deprecated/vf-box.php');
    include_once('includes/deprecated/vf-lede.php');
    include_once('includes/deprecated/vf-activity.php');
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
    // if ( ! in_array($post->post_type, array('post', 'page'))) {
    //   return $categories;
    // }
    return array_merge(
      array(
        array(
          'slug'  => 'vf/core',
          'title' => __('Visual Framework', 'vfwp'),
          'icon'  => null
        ),
        array(
          'slug'  => 'vf/contenthub',
          'title' => __('EMBL Content Hub', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

  /**
   * Filter `render_block`
   * Edit compatible core blocks to use VF markup
   * Wrap other core blocks in `vf-content` class
   */
  function render_block($html, $block) {
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
   * Enqueue WP Admin CSS and JavaScript
   */
  function admin_enqueue_scripts() {
    wp_enqueue_script(
      'iframe-resizer',
      plugins_url('/assets/iframeResizer.min.js', __FILE__),
      false,
      true
    );
    wp_enqueue_script(
      'vf-blocks',
      plugins_url('/assets/vf-blocks.min.js', __FILE__),
      array('iframe-resizer', 'wp-editor', 'wp-blocks'),
      false,
      true
    );

    wp_register_script(
      'vf-gutenberg',
      plugins_url('/assets/vf-gutenberg.js', __FILE__),
      array('iframe-resizer', 'wp-editor', 'wp-blocks'),
      false,
      true
    );

    global $post;
    $post_id = $post instanceof WP_Post ? $post->ID : 0;
    wp_localize_script('vf-gutenberg', 'vfGutenberg', array(
      'nonce' => wp_create_nonce("vf_nonce_{$post_id}"),
      'postId' => $post_id,
      'instanceId' => 0
    ));

    wp_enqueue_script('vf-gutenberg');
  }

  /**
   * Handle AJAX request to render block preview
   */
  function fetch_block() {
    $post_id = isset($_POST['postId']) ? intval($_POST['postId']) : 0;
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';

    if ( ! wp_verify_nonce($nonce, "vf_nonce_{$post_id}")) {
      wp_send_json_error();
      wp_die();
    }
    wp_send_json_success(
      $_POST
    );
    wp_die();
  }

  /**
   * WARNING: deprecated code below
   */

  /**
   * WARNING: deprecated method
   * Action: `admin_notices`
   */
  function admin_notices() {
    if ( ! function_exists('get_current_screen')) {
      return;
    }
    $screen = get_current_screen();
    if ($screen->id === 'edit-vf_block') {
      printf('<div class="%1$s"><p><b>%2$s</b> %3$s</p></div>',
        esc_attr('notice notice-warning'),
        esc_html__('These blocks are deprecated.', 'vfwp'),
        esc_html__('Please use the native blocks within the Gutenberg page editor.', 'vfwp')
      );
    }
  }

  /**
   * WARNING: deprecated method
   * Register an array of custom VF blocks
   */
  function add_block(VF_Gutenberg_Block $instance) {
    $key = 'acf/' . $instance->key();
    if ( ! array_key_exists($key, $this->blocks)) {
      $this->blocks[$key] = $instance;
    }
  }

  /**
   * WARNING: deprecated method
   * Action: `acf/init`
   * Iterate over blocks and register them
   */
  function acf_init() {
    foreach ($this->blocks as $name => $instance) {

      // Register Gutenberg block with ACF
      acf_register_block(array(
        'name'            => $instance->key(),
        'title'           => $instance->title(),
        'category'        => $this->category,
        'render_callback' => array($this, 'render_callback')
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
  function render_callback($block, $content, $is_preview) {
    if ( ! array_key_exists($block['name'], $this->blocks)) {
      return;
    }
    $instance = $this->blocks[ $block['name'] ];
    $html = $instance->render($block);
    if ( $is_preview) {
      $this->render_preview_iframe($block, $html);
    } else {
      echo $html;
    }
  }

  /**
   * WARNING: deprecated method
   * Render block within an iframe
   */
  function render_preview_iframe($block, $html) {
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

    $js = plugins_url('/assets/iframeResizer.contentWindow.min.js', __FILE__);
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
    window.vfGutenbergIFrame(
      iframe,
      <?php echo json_encode($html); ?>,
      <?php echo json_encode($js); ?>
    );
  };
</script>
<iframe <?php echo implode(' ', $attr); ?>></iframe>
<?php
  }

  /**
   * WARNING: deprecated method
   * Output inline styles for Visual Framework Gutenberg blocks
   */
  function admin_head() {
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
   * Filter `block_categories`
   */
  function _deprecated_block_categories($categories, $post) {
    if ( ! in_array($post->post_type, array('post', 'page'))) {
      return $categories;
    }
    return array_merge(
      array(
        array(
          'slug'  => $this->category,
          'title' => __('Visual Framework (deprecated)', 'vfwp'),
          'icon'  => null
        )
      ),
      $categories
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
