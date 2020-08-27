<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Details') ) :

class VF_Widget_Details extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_details',
      __('Details', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {

    
// widget ID with prefix for use in ACF API functions
$widget_id = 'widget_' . $args['widget_id'];

$title = get_field('title', $widget_id);
$summary = get_field('summary', $widget_id, false, false); 
?>

<details class="vf-details" open>
<summary class="vf-details--summary">
<?php echo esc_html($title); ?>
</summary>
<?php echo esc_html($summary); ?>
</details>

<?php
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Details

endif;

/**
 * Register details Widget
 */
function register_details_widget()
{
  register_widget( 'VF_Widget_Details' );
}
add_action( 'widgets_init', 'register_details_widget' ); ?>