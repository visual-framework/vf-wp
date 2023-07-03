<?php
/*
Plugin Name: VF-WP Gutenberg
Description: Adds Visual Framework support and blocks to the Gutenberg editor.
Version: 1.0.0-beta.1
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

  function __construct() {
    // Do nothing...
  }

  function initialize() {
    add_filter(
      'block_categories_all',
      array($this, 'block_categories'),
      10, 2
    );
    add_action(
      'enqueue_block_editor_assets',
      array($this, 'enqueue_block_editor_assets')
    );
    add_filter(
      'wp_ajax_vf/gutenberg/fetch_terms',
      array($this, 'ajax_fetch_terms')
    );
    add_filter(
      'render_block',
      array($this, 'render_block_compatible'),
      10, 2
    );
    add_filter(
      'render_block',
      array($this, 'render_block_nunjucks'),
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
    include_once('includes/core/core-video.php');

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
   * Render VF Nunjuck template blocks
   */
  public function render_block_nunjucks($html, $block) {
    if (preg_match('/^vf\//', $block['blockName'])) {
      if (isset($block['attrs']['render'])) {
        return $block['attrs']['render'];
      }
    }
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
   * Action: `block_categories`
   */
  // function block_categories($categories, $post) {
  //   return array_merge(
  //     array(
  //       array(
  //         'slug'  => 'vf/core',
  //         'title' => __('EMBL – Visual Framework', 'vfwp'),
  //         'icon'  => null
  //       )
  //     ),
  //     $categories
  //   );
  // }

  function block_categories( $categories, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {
      array_push(
        $categories,
        array(
          'slug'  => 'vf/core',
                  'title' => __('EMBL – Visual Framework', 'vfwp'),
                  'icon'  => null
        )
      );
    }
    return $categories;
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
      array('wp-editor', 'wp-blocks', 'vf-plugin'),
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
      'postId'       => $post->ID,
      'nonce'        => wp_create_nonce("vf_nonce_{$post->ID}")
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
   * Callback for `acf_register_block_type` to render within iframe
   * $args = array($block, $content, $is_preview, $post_id)
   */
  static function acf_render_template($args, $template, $acf_id = false) {
    $block = $args[0];
    $is_preview = $args[2];
    $is_jsx = isset($block['supports']['jsx']) && $block['supports']['jsx'];
    if ( ! $acf_id) {
      $acf_id = $block['id'];
    }
    // Capture the block template
    ob_start();
    // Output head include for preview
    if ($is_preview) {
      // Append iframe stylesheet for preview fixes
      $wp_head = function() {
        $path = get_template_directory_uri();
        $path = "{$path}/assets/assets/vfwp-gutenberg-iframe/vfwp-gutenberg-iframe.css";
        echo '<link rel="stylesheet" href="' . esc_url($path) . '">';
      };
      add_action('wp_head', $wp_head, 20);
      get_template_part('partials/head');
      remove_action('wp_head', $wp_head, 20);
    }
    // Load template
    if (is_callable($template)) {
      call_user_func($template, $block, '', $is_preview, $acf_id);
    } else {
      if (file_exists($template)) {
        include($template);
      } else if ($is_preview) {
?>
<div class="vf-banner vf-banner--alert vf-banner--danger">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('Block template missing.', 'vfwp'); ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
      }
    }

    if ($is_preview) {
      return;
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
<div class="vf-block" data-acf-id="<?php echo esc_attr($acf_id); ?>" data-editing="false" data-loading="false">
  <div class="vf-block__view">
    <?php /*
    <iframe
      class="vf-block__iframe"
      id="<?php echo $id; ?>"
      onLoad="<?php echo "setTimeout(()=>{{$id}();}, 1);"; ?>"
      scrolling="no"></iframe>
      */ ?>
  </div>
  <?php if ($is_jsx) { ?>
  <div class="vf-block__inner-blocks">
    <InnerBlocks />
  </div>
  <?php } ?>
</div>
<script>
(function() {

window.<?php echo $id; ?> = function() {
  const iframe = document.getElementById('<?php echo $id; ?>');
  iframe.vfActive = true;
  var doc = iframe.contentWindow.document;
  doc.body.innerHTML = <?php echo json_encode($html); ?>;
  doc.body.style.position = 'absolute';
  doc.body.style.width = '100%';
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.innerHTML = `
if (ResizeObserver) {
  const observer = new ResizeObserver(entries => {
    entries.forEach(entry => {
      window.parent.postMessage({
          id: '<?php echo $id; ?>',
          height: entry.contentRect.height
        }, '*'
      );
    });
  });
  observer.observe(document.body);
} else {
  const vfResize = () => {
    window.parent.postMessage({
        id: '<?php echo $id; ?>',
        height: document.documentElement.scrollHeight
      }, '*'
    );
  };
  window.addEventListener('resize', vfResize);
  setTimeout(vfResize, 100);
  vfResize();
}
`;
  doc.body.appendChild(script);
  // doc.body.classList.add('ebi-vf1-integration');
};

const parent = document.querySelector('[data-acf-id="<?php echo esc_attr($acf_id); ?>"]');
let iframe = document.getElementById('<?php echo $id; ?>');
const onLoad = () => {
  if (typeof window[iframe.id] === 'function') {
    window[iframe.id]();
  }
};
if (iframe) {
  onLoad();
} else {
  iframe = document.createElement('iframe');
  iframe.id = '<?php echo $id; ?>';
  iframe.className = 'vf-block__iframe';
  iframe.setAttribute('scrolling', 'no');
  iframe.onload = onLoad;
  parent.insertBefore(iframe, parent.firstChild);
}

})();
</script>
<?php
  // Toggle the Gutenberg editor block style for containers
  $is_container = get_field('is_container', $acf_id);
  $is_container = (bool) $is_container;
  if ($is_container) {
?>
<script>
(function($){
  // Callback to update the block inline style
  function updateBlock(isContainer) {
    var $el = $('.vf-block[data-acf-id="<?php echo $acf_id; ?>"]');
    var $block = $el.closest('.wp-block');
    if ($block.length) {
      if (isContainer) {
        $block[0].style.maxWidth = 'none';
      } else {
        $block[0].style.removeProperty('max-width');
      }
    }
  }
  // TODO: move to component scripts / fix?
  // Trigger first update
  updateBlock(true);
  // Add event for live field changes
  acf.addAction('append_field/name=is_container', function(field) {
    field.on('change', 'input[type="checkbox"]', function(ev) {
      if (ev.target.id.indexOf('<?php echo $acf_id; ?>')) {
        updateBlock(field.val());
      }
    });
  });
})(window.jQuery);
</script>
<?php
    }
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
