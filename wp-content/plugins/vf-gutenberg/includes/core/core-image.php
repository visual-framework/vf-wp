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
      $classes = $matches[2];
      $classes = str_replace(
        'alignleft',
        ' | vf-figure--float vf-figure--float-inline-start  ',
        $classes
      );
      $classes = str_replace(
        'alignright',
        ' | vf-figure--float vf-figure--float-inline-end ',
        $classes
      );
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
