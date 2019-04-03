<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Factoid') ) :

class VF_Widget_Factoid extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_factoid',
      __('Factoid (Content Hub)', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {
    $block = VF_Plugin::get_plugin('vf_factoid');
    $fields = get_fields("widget_{$this->id}");
    if (is_array($fields)) {
      echo '<div class="widget widget_vf_factoid">';
      VF_Plugin::render($block, $fields);
      echo '</div>';
    }
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Factoid

endif;

?>
