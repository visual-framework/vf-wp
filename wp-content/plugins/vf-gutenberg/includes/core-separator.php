<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Core_Separator') ) :

class VF_Gutenberg_Core_Separator {

  /**
   * Filter `core/separator` default Gutenberg block
   */
  static function render($html, $block) {
    return "<hr class=\"vf-divider\">";
  }

} // VF_Gutenberg_Core_Separator

vf_gutenberg()->add_compatible(
  'core/separator',
  array('VF_Gutenberg_Core_Separator', 'render')
);

endif;

?>
