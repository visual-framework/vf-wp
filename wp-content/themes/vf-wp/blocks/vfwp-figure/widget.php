<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Figure') ) :

class VF_Widget_Figure extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_figure',
      __('Figure', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {
    
    $widget_id = 'widget_' . $args['widget_id'];
    $caption = get_field('caption', $widget_id, false, false);
    $image = get_field('image', $widget_id);
    $link = get_field('link', $widget_id);

    
      $image = wp_get_attachment_image($image['ID'], 'full', false, array(
        'class'    => 'vf-figure__image',
        'loading'  => 'lazy',
        'itemprop' => 'image',
      ));
    
    ?>

    <figure class="vf-figure">

    <?php if( ($link) ) { ?>
    <a href="<?php echo ($link); ?>">
    <?php }
    
    echo ($image); 

    if( ($link) ) { ?>
    </a>
    <?php }
        
    if( ! empty($caption) ) { ?>
    <figcaption class="vf-figure__caption"><?php echo ($caption); ?></figcaption>
    <?php } ?>

    </figure>

    <?php  
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Figure

endif;

/**
 * Register Links List Widget
 */
function register_Figure_widget()
{
  register_widget( 'VF_Widget_Figure' );
}
add_action( 'widgets_init', 'register_figure_widget' ); ?>