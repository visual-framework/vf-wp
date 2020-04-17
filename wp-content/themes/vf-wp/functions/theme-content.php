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

  public function __construct() {
    // Add content hooks
    add_filter(
      'vf/theme/content/is_block_wrapped',
      array($this, 'is_block_wrapped'),
      9, 4
    );
    add_filter(
      'vf/theme/content/open_block_wrap',
      array($this, 'open_block_wrap'),
      9, 2
    );
    add_filter(
      'vf/theme/content/close_block_wrap',
      array($this, 'close_block_wrap'),
      9, 2
    );
  }

  /**
   * Return the block name prefixed as HTML comment
   * <!--[core-embed/youtube]-->
   */
  static public function get_block_name($html) {
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
   * Filter: `vf/theme/content/is_block_wrapped`
   * Return true if block should be wrapped
   * e.g. with `<div class="vf-content"> [...] </div>`
   */
  public function is_block_wrapped($is_wrap, $block_name, $blocks, $i) {
    // Ignore non `vf` blocks
    if ( ! preg_match('#^vf/(.+)#', $block_name)) {
      return true;
    }
    // Don't wrap standalone VF blocks
    if (class_exists('VF_Gutenberg')) {
      if (VF_Gutenberg::is_block_standalone($block_name)) {
        return false;
      }
    }
    return true;
  }

  /**
   * Filter: `vf/theme/content/open_block_wrap`
   * Return default block opening wrapper
   */
  public function open_block_wrap($html, $block_name) {
    $html = "<!--[vf/content]-->\n<div class=\"vf-content\">\n{$html}";
    return $html;
  }

  /**
   * Filter: `vf/theme/content/close_block_wrap`
   * Return default block closing wrapper
   */
  public function close_block_wrap($html, $block_name) {
    $html = "{$html}</div>\n";
    return $html;
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
     * STEP 1:
     * Apply a render block filter to add open/close comments
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
     * STEP 2:
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
    /**
     * STEP 3:
     * Recombine page content and then split into top-level blocks
     */
    $main_html = implode("\n", $main_lines);
    $blocks = explode(
      $final,
      $main_html
    );
    // Filter and then render the top-level blocks
    $blocks = VF_Theme::apply_filters(
      'vf/theme/content/blocks',
      $blocks,
      $post
    );
    $this->render_blocks($blocks);
  }

  public function render_blocks($blocks) {
    $block_name = '';
    $is_open = false;
    $is_wrap = false;
    foreach ($blocks as $i => $block_html) {
      // Apply before filter
      $block_name = VF_Theme_Content::get_block_name($block_html);
      $block_html = VF_Theme::apply_filters(
        'vf/theme/content/render_block_before',
        $block_html, $block_name, $i
      );
      if (preg_match('#\S#', $block_html) !== 1) {
        continue;
      }
      $is_wrap = (bool) VF_Theme::apply_filters(
        'vf/theme/content/is_block_wrapped',
        false, $block_name, $blocks, $i
      );
      $is_wrap = (bool) VF_Theme::apply_filters(
        "vf/theme/content/is_block_wrapped/name={$block_name}",
        $is_wrap, $blocks, $i
      );
      $before  = '';
      $prefix = '';
      $suffix = '';
      if ($is_wrap) {
        // Open wrapper if closed
        if ( ! $is_open) {
          $before = VF_Theme::apply_filters(
            'vf/theme/content/open_block_wrap',
            '', $block_name
          );
          $is_open = true;
        }
      } else {
        // Close wrapper if open
        if ($is_open) {
          $before = VF_Theme::apply_filters(
            'vf/theme/content/close_block_wrap',
            '', $block_name
          );
        }
        $is_open = false;
      }

      // Optional prefix
      $prefix = VF_Theme::apply_filters(
        'vf/theme/content/block_prefix',
        '', $block_name, $is_open
      );

      // Optional suffix
      $suffix = VF_Theme::apply_filters(
        'vf/theme/content/block_suffix',
        '', $block_name, $is_open
      );

      // Apply after filter
      $block_html = "{$prefix}{$block_html}{$suffix}";
      $block_html = VF_Theme::apply_filters(
        'vf/theme/content/render_block_after',
        $block_html, $block_name, $i, $is_open
      );

      // Render block
      echo "{$before}{$block_html}";
    }
    // Close final wrapper if open
    if ($is_open) {
      echo VF_Theme::apply_filters(
        'vf/theme/content/close_block_wrap',
        '', $block_name
      );
    }
  }

} // VF_Theme_Content

endif;

?>
