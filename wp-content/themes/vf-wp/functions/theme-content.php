<?php
/**
 * Theme Content
 * Sub-class initiated by `VF_Theme`
 * Provides the global `$vf_theme->the_content()` wrapper for `the_content()`.
 * This applies several custom filters for Gutenberg blocks.
 * By default headings, lists, and paragraphs are wrapped.
 */

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Theme_Content') ) :

class VF_Theme_Content {

  // List of Gutenberg blocks to wrap (populated by filter)
  private $wrapped = array();

  public function __construct() {
    // Add content hooks
    add_filter(
      'vf/theme/content/wrapped_blocks',
      array($this, 'block_wrapped'),
      9, 1
    );
    add_filter(
      'vf/theme/content/is_block_wrapped',
      array($this, 'block_is_wrapped'),
      9, 4
    );
    add_filter(
      'vf/theme/content/open_block_wrap',
      array($this, 'block_open_wrap'),
      9, 3
    );
    add_filter(
      'vf/theme/content/close_block_wrap',
      array($this, 'block_close_wrap'),
      9, 3
    );
    // Setup defaults
    $this->wrapped = apply_filters(
      'vf/theme/content/wrapped_blocks',
      array()
    );
  }

  /**
   * Filter: `vf/theme/content/wrapped_blocks`
   * Add default wrapped block list
   */
  public function block_wrapped($wrapped) {
    $wrapped = array_merge($wrapped, array(
      'core/heading',
      'core/list',
      'core/paragraph'
    ));
    return array_unique($wrapped);
  }

  /**
   * Filter: `vf/theme/content/is_block_wrapped`
   * Return true if block should be wrapped
   * e.g. with `<div class="vf-content"> [...] </div>`
   */
  public function block_is_wrapped($is_wrap, $block_name, $blocks, $i) {
    return in_array($block_name, $this->wrapped);
  }

  /**
   * Filter: `vf/theme/content/open_block_wrap`
   * Return content block prefixed HTML
   */
  public function block_open_wrap($html) {
    $html = "<div class=\"vf-content\">\n";
    return $html;
  }

  /**
   * Filter: `vf/theme/content/close_block_wrap`
   * Return content block suffixed HTML
   */
  public function block_close_wrap($html) {
    $html = "</div>\n<!--/vf-content-->\n";
    return $html;
  }

  /**
   * Return the block name prefixed as HTML comment
   * <!--[core-embed/youtube]-->
   */
  public function get_block_name($html) {
    $open = preg_quote('<!--[');
    $close = preg_quote(']-->');
    if (preg_match(
      "#^\s*{$open}([^\]]*){$close}#",
      $html, $match
    ) === 1) {
      return $match[1];
    }
    return '';
  }

  /**
   * Parse the post content and wrap blocks
   */
  public function the_content() {
    global $post;
    // Temporary markers
    $open = '<!--[VF_BLOCK]-->';
    $close = '<!--[/VF_BLOCK]-->';
    $final = '<!--[/VF_CONTENT]-->';
    /**
     * Apply a render block filter to add markers
     * Also prefix each block with its name in a comment
     * Capture the complete page content
     */
    ob_start();
    $render_block = function($html, $block) use ($open, $close) {
      if ( ! is_string($block['blockName'])) {
        return;
      }
      $prefix = "\n{$open}\n";
      $prefix .= "<!--[{$block['blockName']}]-->\n";
      $suffix = "\n{$close}\n";
      return "{$prefix}{$html}{$suffix}";
    };
    add_filter('render_block', $render_block, PHP_INT_MAX, 2);
    the_content();
    remove_filter('render_block', $render_block, PHP_INT_MAX);
    $main_html = ob_get_contents();
    ob_end_clean();

    /**
     * Parse the captured page content line-by-line
     * Add close comments for only top-level blocks (ignore nested)
     * Remove the other open/close comments
     */
    $all_lines = explode("\n", $main_html);
    $main_lines = array();
    $line_cache = array();
    $depth = 0;
    foreach ($all_lines as $line) {
      if (preg_match('#\S#', $line) !== 1) {
        continue;
      }
      if (preg_match('#^' . preg_quote($open) . '#', $line)) {
        $depth++;
      } else if (preg_match('#^' . preg_quote($close) . '#', $line)) {
        $depth--;
      } else {
        $line_cache[] = $line;
      }
      if ($depth === 0) {
        $line_cache[] = $final;
        $main_lines = array_merge($main_lines, $line_cache);
        $line_cache = array();
      }
    }
    // Recombine page content and then split into top-level blocks
    $main_html = implode("\n", $main_lines);
    $blocks = explode(
      $final,
      $main_html
    );
    // Filter and then render the top-level blocks
    $blocks = apply_filters(
      'vf/theme/content/blocks',
      $blocks,
      $post
    );
    $this->render_blocks($blocks);
  }

  /**
   * Render blocks by combining adjacent content in one wrapper
   */
  public function render_blocks($blocks) {
    $is_wrap = false;
    $was_wrap = false;
    // Iternate over top-level blocks
    foreach ($blocks as $i => $block_html) {
      if (preg_match('#\S#', $block_html) !== 1) {
        continue;
      }
      $block_name = $this->get_block_name($block_html);
      $is_wrap = (bool) apply_filters(
        'vf/theme/content/is_block_wrapped',
        false, $block_name, $blocks, $i
      );
      // Close open wrapper if block is standalone
      if ( ! $is_wrap && $was_wrap) {
        echo apply_filters('vf/theme/content/close_block_wrap', '');
      }
      // Open new wrapper if not already open
      if ($is_wrap && ! $was_wrap) {
        echo apply_filters('vf/theme/content/open_block_wrap', '');
      }
      $was_wrap = $is_wrap;
      echo $block_html;
    }
    // Close final block if left open
    if ($was_wrap) {
      echo apply_filters('vf/theme/content/close_block_wrap', '');
    }
  }

} // VF_Theme_Content

endif;

?>
