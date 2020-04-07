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

  // // Default wrapped blocks
  // public const WRAPPED = array(
  //   'core/heading',
  //   'core/list',
  //   'core/paragraph'
  // );

  // // List of Gutenberg blocks to wrap (populated by filter)
  // private $wrapped = array();

  public function __construct() {
    // Add content hooks
    // add_filter(
    //   'vf/theme/content/wrapped_blocks',
    //   array($this, 'wrapped_blocks'),
    //   9, 1
    // );
    add_filter(
      'vf/theme/content/is_block_wrapped',
      array($this, 'is_block_wrapped'),
      9, 4
    );
    add_filter(
      'vf/theme/content/open_block_wrap',
      array($this, 'open_block_wrap'),
      9, 3
    );
    add_filter(
      'vf/theme/content/close_block_wrap',
      array($this, 'close_block_wrap'),
      9, 2
    );
    // // Setup defaults
    // $this->wrapped = VF_Theme::apply_filters(
    //   'vf/theme/content/wrapped_blocks',
    //   array()
    // );
  }

  /**
   * Filter: `vf/theme/content/wrapped_blocks`
   * Add default wrapped block list
   */
  // public function wrapped_blocks($wrapped) {
  //   $wrapped = array_merge($wrapped, VF_Theme_Content::WRAPPED);
  //   return array_unique($wrapped);
  // }

  /**
   * Filter: `vf/theme/content/is_block_wrapped`
   * Return true if block should be wrapped
   * e.g. with `<div class="vf-content"> [...] </div>`
   */
  public function is_block_wrapped($is_wrap, $block_name, $blocks, $i) {
    // return in_array($block_name, $this->wrapped);
    // var_dump($block_name);

    // Ignore non `vf` blocks
    if ( ! preg_match('#^vf/(.+)#', $block_name)) {
      return true;
    }
    // Check if block is a `VF_Plugin`
    $post_name = VF_Gutenberg::name_block_to_post($block_name);
    $plugin = VF_Plugin::get_plugin($post_name);
    if ( ! $plugin) {
      return true;
    }
    if ($plugin) {
      if ($plugin->is_template_standalone()) {
        return false;
      }
    }
    return true;
  }

  /**
   * Filter: `vf/theme/content/open_block_wrap`
   * Return content block prefixed HTML
   */
  public function open_block_wrap($html, $is_wrap, $block_name) {
    if ($is_wrap) {
      $html = "<!--[vf/content]-->\n<div class=\"vf-content\">\n{$html}";
    }
    return $html;
  }

  /**
   * Filter: `vf/theme/content/close_block_wrap`
   * Return content block suffixed HTML
   */
  public function close_block_wrap($html, $is_wrap) {
    if ($is_wrap) {
      $html = "{$html}</div>\n";
    }
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
      $block_name = $this->get_block_name($block_html);
      $block_html = VF_Theme::apply_filters(
        'vf/theme/content/render_block',
        $block_html, $block_name, $i
      );
      if (preg_match('#\S#', $block_html) !== 1) {
        continue;
      }
      $was_wrap = $is_wrap;
      $is_wrap = (bool) VF_Theme::apply_filters(
        'vf/theme/content/is_block_wrapped',
        false, $block_name, $blocks, $i
      );
      $prefix = '';
      $suffix = '';
      if ($is_wrap) {
        // Open wrapper if closed
        if ( ! $is_open) {
          $prefix = VF_Theme::apply_filters(
            'vf/theme/content/open_block_wrap',
            '', true, $block_name
          );
          $is_open = true;
        }
      } else {
        // Close wrapper if open
        if ($is_open) {
          $prefix = VF_Theme::apply_filters(
            'vf/theme/content/close_block_wrap',
            '', $was_wrap, $block_name
          );
        }
        // Optional suffix for no-wrap
        $prefix .= VF_Theme::apply_filters(
          'vf/theme/content/open_block_wrap',
          '', false, $block_name
        );
        // Optional suffix for no-wrap
        $suffix = VF_Theme::apply_filters(
          'vf/theme/content/close_block_wrap',
          '', false, $block_name
        );
        $is_open = false;
      }
      $block_html = "{$prefix}{$block_html}{$suffix}";
      echo $block_html;
    }
    // Close final wrapper if open
    if ($is_open) {
      // echo $this->get_close_container();
      echo VF_Theme::apply_filters(
        'vf/theme/content/close_block_wrap',
        '', true, $block_name
      );
    }
  }

} // VF_Theme_Content

endif;

?>
