<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Colors') ) :

class VF_Gutenberg_Core_Colors {

  /**
   * Filter compatible Gutenberg blocks
   * Add `vf-u-text-color--` utility class
   */
  static function render($html, $block) {
    // Remove unused class
    $html = preg_replace_callback(
      '#(<[p|h][^>]*?)class="(.*?)has-text-color(.*?)"([^>]*?>)#',
      function ($matches) {
        return
            $matches[1]
          . 'class="'
          . $matches[2]
          . $matches[3]
          . '"'
          . $matches[4];
      },
      $html
    );
    // Replace WordPress color classes
    $html = preg_replace_callback(
      '#(<[p|h][^>]*?)class="(.*?)has-([a-z-]+?)-color(.*?)"([^>]*?>)#',
      function ($matches) {
        // Generate new class attribute value
        $classes = array(
          trim($matches[2]),
          trim($matches[4]),
          "vf-u-text-color--{$matches[3]}"
        );
        // Return HTML with new class appended
        return
            $matches[1]
          . 'class="'
          . trim(implode(' ', $classes))
          . '"'
          . $matches[5];
      },
      $html
    );
    return $html;
  }

} // VF_Gutenberg_Core_Colors

vf_gutenberg()->add_compatible(
  array(
    'core/heading',
    'core/paragraph',
  ),
  array('VF_Gutenberg_Core_Colors', 'render')
);

endif;

?>
