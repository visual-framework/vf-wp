<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Embed') ) :

class VF_Widget_Embed extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_embed',
      __('Embed', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) { 
    
    $widget_id = 'widget_' . $args['widget_id'];
    $caption = get_field('caption', $widget_id, false, false);
    $url = get_field('url', $widget_id);
    $ratio = get_field('ratio', $widget_id);
  
    if ($ratio === '16 x 9') {
      $class = "vf-embed--16x9";
    }

    if ($ratio === '4 x 3') {
      $class = "vf-embed--4x3";
    }

    if ($ratio === '16 x 9 max width') {
      $class = "vf-embed--16x9";
      $style = "--vf-embed-max-width: 400px";
    }
    ?>

    <div class="vf-embed <?php echo ($class); ?>" style="<?php echo ($style); ?>
    "><iframe src="<?php echo ($url); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>

    <?php if( ! empty($caption) ) { ?>  
    <figcaption class="vf-figure__caption vf-u-margin__top--sm"><?php echo ($caption); ?></figcaption>

    <?php }
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Embed

endif;

/**
 * Register Embed Widget
 */
function register_Embed_widget()
{
  register_widget( 'VF_Widget_Embed' );
}
add_action( 'widgets_init', 'register_embed_widget' ); ?>