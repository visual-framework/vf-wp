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

  // List of custom VF blocks
  private $blocks = array();

  // List of compatible core blocks
  private $compatible = array();

  private $settings;

  function __construct() {
    // Do nothing...
  }

  function initialize() {
    add_filter('render_block', array($this, 'render_block'), 10, 2);
    add_filter('block_categories', array($this, 'block_categories'), 10, 2);
    add_action('acf/init', array($this, 'acf_init'));
    add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    add_action('admin_head', array($this, 'admin_head'), 10);

    // ACF options
    include_once('includes/settings.php');
    $this->settings = new VF_Gutenberg_Settings();

    // Register core transforms
    include_once('includes/core-button.php');
    include_once('includes/core-file.php');
    include_once('includes/core-image.php');
    include_once('includes/core-quote.php');
    include_once('includes/core-separator.php');

    // Register custom blocks
    include_once('includes/vf-block.php');
    include_once('includes/vf-box.php');
    include_once('includes/vf-lede.php');
    include_once('includes/vf-activity.php');
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
   * Register an array of custom VF blocks
   */
  function add_block(VF_Gutenberg_Block $instance) {
    $key = 'acf/' . $instance->key();
    if ( ! array_key_exists($key, $this->blocks)) {
      $this->blocks[$key] = $instance;
    }
  }

  /**
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
   * Render block within an iframe
   */
  function render_preview_iframe($block, $html) {
    // Prepend custom CSS
    $css = plugins_url('/assets/vf-iframe.css', __FILE__);
    $html = '<link rel="stylesheet" href="' . $css . '">' . $html;
    // Prepend Visual Framework CSS
    if (function_exists('vf_get_stylesheet')) {
      $html = '<link rel="stylesheet" href="' . vf_get_stylesheet() . '">' . $html;
    }
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
      'vf-gutenberg',
      plugins_url('/assets/vf-gutenberg.js', __FILE__),
      array('iframe-resizer', 'wp-editor', 'wp-blocks'),
      false,
      true
    );
  }

  /**
   * Filter `block_categories`
   */
  function block_categories($categories, $post) {
    if ( ! in_array($post->post_type, array('post', 'page'))) {
      return $categories;
    }
    return array_merge(
      array(
        array(
          'slug'  => $this->category,
          'title' => __('Visual Framework', 'vfwp'),
          'icon'  => null
        )
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
