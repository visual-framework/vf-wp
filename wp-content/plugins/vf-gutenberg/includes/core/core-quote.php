<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Quote') ) :

class VF_Gutenberg_Core_Quote {

  /**
   * Filter `core/quote` default Gutenberg block
   * Add `vf-blockquote` markup
   */
  static function render($html, $block) {
    // Replace the blockquote element
    if (preg_match('#<blockquote([^>]*?)>#', $html, $matches)) {
      $html = str_replace(
        $matches[0],
        '<blockquote class="vf-blockquote">',
        $html
      );
      // Wrap the citation element in a paragraph
      if (preg_match('#<cite>(.*?)</cite>#', $html, $matches)) {
        $html = str_replace(
          $matches[0],
          '<p><cite>' . $matches[1] . '</cite></p>',
          $html
        );
      }
    }
    return $html;
  }

} // VF_Gutenberg_Core_Quote

vf_gutenberg()->add_compatible(
  'core/quote',
  array('VF_Gutenberg_Core_Quote', 'render')
);

endif;

?>
