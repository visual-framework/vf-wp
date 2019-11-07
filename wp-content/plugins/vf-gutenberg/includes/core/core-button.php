<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Button') ) :

class VF_Gutenberg_Core_Button {

  /**
   * Filter `core/button` default Gutenberg block
   * Add `vf-button` markup
   */
  static function render($html, $block) {

    $is_default = strpos($html, 'is-style-default') !== false;
    $is_outline = strpos($html, 'is-style-outline') !== false;
    $is_squared = strpos($html, 'is-style-squared') !== false;

    $classes = array('vf-button');
    if ($is_outline) {
      $classes[] = 'vf-button--outline';
    } else {
      $classes[] = 'vf-button--primary';
    }
    if ( ! $is_squared) {
      $classes[] = 'vf-button--pill';
    }

    $href = '#';
    $label = 'Button';

    if (preg_match('#<a[^>]*?href="(.*?)"[^>]*?>(.*?)</a>#', $html, $matches)) {
      $href = $matches[1];
      $label = $matches[2];
    } else if (preg_match('#<a[^>]*?>(.*?)</a>#', $html, $matches)) {
      $label = $matches[1];
    }

    $html = '<a href="';
    $html .= esc_url($href);
    $html .= '" class="' . implode(' ', $classes) . '">';
    $html .= strip_tags($label);
    $html .= '</a>';

    return "<p>{$html}</p>";
  }

} // VF_Gutenberg_Core_Button

vf_gutenberg()->add_compatible(
  'core/button',
  array('VF_Gutenberg_Core_Button', 'render')
);

endif;

?>
