<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Links_List') ) :

class VF_Widget_Links_List extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_links_list',
      __('Links List', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {

    
// widget ID with prefix for use in ACF API functions
$widget_id = 'widget_' . $args['widget_id'];

// Block preview in Gutenberg editor

$heading = get_field('heading', $widget_id);
$list_items = get_field('list_items', $widget_id);
$meta_information = get_field('meta_information', $widget_id);
$widget_id = 'widget_' . $args['widget_id'];


$type = get_field('select_type', $widget_id);
if (empty($type)) {
  $type = 'default';
}
if (is_array($type)) {
  $type = $type[0];
}

$class_div = "vf-links";
$class_ul = "vf-links__list | vf-list";

if ($type === 'tight' || $type === 'very-easy') {
  $class_div .= ' vf-links--tight vf-links__list--s';
  $class_ul .= ' vf-links__list--secondary';
}

if ($type === 'easy' || $type === 'very-easy' || $type === 'has-image') {
  $class_div .= " vf-links__list--{$type}";
}

echo $before_widget;


?>
<div class="<?php echo esc_attr($class_div); ?>">
  <?php if ( ! empty($heading)) { ?>
  <h3 class="vf-links__heading"><?php echo $heading; ?></h3>
  <?php } ?>
  <?php if (have_rows('list_items', $widget_id)) { ?>
    <ul class="<?php echo esc_attr($class_ul); ?>">

    <?php
    while (have_rows('list_items', $widget_id)) {
      the_row();
      $text = get_sub_field('text', $widget_id);
      $url = get_sub_field('url', $widget_id);
      $badge_text = get_sub_field('badge_text', $widget_id);
      $badge_style = get_sub_field('badge_style', $widget_id);
      $meta_information = get_sub_field('meta_information', $widget_id);
      $image = get_sub_field('image', $widget_id);
      if ( ! is_array($image)) {
        $image = null;
      } else {
        $image = wp_get_attachment_image($image['ID'], 'thumbnail', false, array(
          'class'    => 'vf-list__image',
          'loading'  => 'lazy',
          'itemprop' => 'image',
        ));
      }
    ?>

    <li class="vf-list__item">
      <a class="vf-list__link" href="<?php echo esc_url($url); ?>">

      <?php
      if ($type === 'has-image') {
        // add placeholder if no image to avoid visual bugs
        echo $image ? $image : '<div class="vf-list__image"></div>';
      }
      ?>

      <?php echo esc_html($text); ?>

      <?php if ($type === 'easy') { ?>
        <svg class="vf-icon vf-icon__arrow--down | vf-list__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>arrow-button-down</title><path d="M.249,7.207,11.233,19.678h0a1.066,1.066,0,0,0,1.539,0L23.751,7.207a.987.987,0,0,0-.107-1.414l-1.85-1.557a1.028,1.028,0,0,0-1.438.111L12.191,13.8a.25.25,0,0,1-.379,0L3.644,4.346A1.021,1.021,0,0,0,2.948,4a1,1,0,0,0-.741.238L.356,5.793A.988.988,0,0,0,0,6.478.978.978,0,0,0,.249,7.207Z"/></svg>
      <?php } ?>

      </a>

      <?php if( ! empty($badge_text)) { ?>
        <span class="vf-badge vf-badge--<?php echo esc_attr($badge_style); ?>">
          <?php echo esc_html($badge_text); ?>
        </span>
      <?php } ?>

      <?php if( ! empty($meta_information) ) { ?>
        <p class="vf-links__meta"><?php echo esc_html($meta_information); ?></p>
      <?php } ?>

    </li>
    <!--/vf-list-item-->
      <?php 	echo $before_widget;
 } ?>
  </ul>
</div>
<!--/vf-links-list-->
<?php }

  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Links_List

endif;

/**
 * Register Links List Widget
 */
function register_links_list_widget()
{
  register_widget( 'VF_Widget_Links_List' );
}
add_action( 'widgets_init', 'register_links_list_widget' ); ?>