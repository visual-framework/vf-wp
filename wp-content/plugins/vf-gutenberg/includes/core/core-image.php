<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Image') ) :

class VF_Gutenberg_Core_Image {

  /**
   * Filter `core/image` default Gutenberg block
   * Add `vf-figure` markup
   */
  static function render($html, $block) {
    if (preg_match('#<figure([^>]*?)class="(.*?)"([^>]*?)>#', $html, $matches)) {
      $attr = array(
        'class="vf-figure ' .  $matches[2] . '"',
        trim($matches[1]),
        trim($matches[3])
      );
      $html = str_replace(
        $matches[0],
        '<figure ' . implode(' ', array_filter($attr)) . '>',
        $html
      );
      // right aligned images
      // we use utility classes for alignment and padding until we have a vf-figure__left/right
      // https://github.com/visual-framework/vf-core/issues/813
      $html = str_replace("alignright", "vf-u-float__right vf-u-margin__bottom--lg vf-u-margin__left--lg", $html);
      // right aligned images
      $html = str_replace("alignleft", "vf-u-float__left vf-u-margin__bottom--lg vf-u-margin__right--lg", $html);
      // Replace the caption element
      if (preg_match('#<figcaption([^>]*?)>#', $html, $matches)) {
        $html = str_replace(
          $matches[0],
          '<figcaption class="vf-figure__caption">',
          $html
        );
      }
      // Add classes to image element - allow existing attributes to persist
      if (preg_match('#<img([^>]*)/?>#', $html, $matches)) {
        $attr = array(
          'class="vf-figure__image"',
          trim($matches[1])
        );
        $html = str_replace(
          $matches[0],
          '<img ' . implode(' ', array_filter($attr)) . '>',
          $html
        );
      }
    }
    return $html;
  }

} // VF_Gutenberg_Core_Image

vf_gutenberg()->add_compatible(
  'core/image',
  array('VF_Gutenberg_Core_Image', 'render')
);

endif;

?>
