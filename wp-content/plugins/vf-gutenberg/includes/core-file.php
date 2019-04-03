<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_File') ) :

class VF_Gutenberg_Core_File {

  /**
   * Filter `core/file` default Gutenberg block
   * Add `vf-file` markup
   */
  static function render($html, $block) {

    $classes = array(
      'wp-block-file__button',
      'vf-button',
      'vf-button--primary',
      'vf-button--pill',
      'vf-button--s'
    );

    $html = str_replace($classes[0], implode(' ', $classes), $html);

    $html = preg_replace(
      '#<a([^>]*?)download([^>]*?)>(.*?)</a>#',
      '<a $1download$2 style="margin:0 0 0 .75em;">$3</a>',
      $html
    );

    return "<div class=\"vf-content\">{$html}</div>";
  }

} // VF_Gutenberg_Core_File

vf_gutenberg()->add_compatible(
  'core/file',
  array('VF_Gutenberg_Core_File', 'render')
);

endif;

?>
