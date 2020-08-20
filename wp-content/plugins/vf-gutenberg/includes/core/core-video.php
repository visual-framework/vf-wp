<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Video') ) :

class VF_Gutenberg_Core_Video {

  /**
   * Filter `core/Video` default Gutenberg block
   * Add `vf-figure` markup
   */
  static function render($html, $block) {
    if (preg_match('#<figure([^>]*?)class="(.*?)"([^>]*?)>#', $html, $matches)) {
      $classes = $matches[2];
      $attr = array(
        'class="vf-figure ' .  esc_attr($classes) . '"',
        trim($matches[1]),
        trim($matches[3])
      );
      $html = str_replace(
        $matches[0],
        '<figure ' . implode(' ', array_filter($attr)) . '>',
        $html
      );
      // Replace the caption element
      if (preg_match('#<figcaption([^>]*?)>#', $html, $matches)) {
        $html = str_replace(
          $matches[0],
          '<figcaption class="vf-figure__caption">',
          $html
        );
      }
      // Add classes to Video element - allow existing attributes to persist
      if (preg_match('#<video([^>]*)/?>#', $html, $matches)) {
        $attr = array(
          'style="max-width: 100%;"',
          trim($matches[1])
        );
        $html = str_replace(
          $matches[0],
          '<video ' . implode(' ', array_filter($attr)) . '>',
          $html
        );
      }
    }
    return $html;
  }

} // VF_Gutenberg_Core_Video

vf_gutenberg()->add_compatible(
  'core/video',
  array('VF_Gutenberg_Core_Video', 'render')
);

endif;

?>
