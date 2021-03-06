<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Box') ) :

class VF_Widget_Box extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_box',
      __('Box', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {

    
// widget ID with prefix for use in ACF API functions
$widget_id = 'widget_' . $args['widget_id'];

$heading = get_field('heading', $widget_id);
$text = get_field('text', $widget_id);
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-box__text">', $text);
$link = get_field('link', $widget_id);


$style = get_field('style', $widget_id);

if (empty($style)) {
  $style = 'easy';
}
if (is_array($style)) {
  $style = $style[0];
}

$theme_easy = get_field('theme_easy', $widget_id);
if (empty($theme_easy)) {
  $theme_easy = 'none';
}
if (is_array($theme_easy)) {
  $theme_easy = $theme_easy[0];
}

$theme_normal = get_field('theme_normal', $widget_id);
if (empty($theme_normal)) {
  $theme_normal = 'none';
}
if (is_array($theme_normal)) {
  $theme_normal = $theme_normal[0];
}

$theme_none = get_field('theme_none', $widget_id);
if (empty($theme_none)) {
  $theme_none = 'very-easy';
}
if (is_array($theme_none)) {
  $theme_none = $theme_none[0];
}

$classes = "vf-box";

if ($link) {
  $classes .= " vf-box--is-link";
}

if ($style === 'none') {
  $classes .= " vf-box--{$theme_none}";
}
  
if ($style === 'normal') {
$classes .= " vf-box--{$style}";
if ($style !== 'easy') {
  $classes .= " vf-box-theme--{$theme_normal}";
} }

if ($style === 'easy') {
$classes .= " vf-box--{$style}";
if ($style !== 'normal') {
  $classes .= " vf-box-theme--{$theme_easy}";
} }?>

<div class="<?php echo esc_attr($classes); ?>">
  <?php if (! empty($heading)) { ?>
    <h3 class="vf-box__heading">
      <?php } if ($link) { ?>
        <a class="vf-box__link" href="<?php echo esc_url($link['url']); ?>">
          <?php } ?>
          <?php echo esc_html($heading); ?>
        <?php if ($link) { ?>
        </a>
        <?php }  ?>
      <?php if (! empty($heading)) { ?>
    </h3> 
      <?php } ?>
  <?php echo ($text); ?>
</div>

<?php
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Box

endif;

/**
 * Register Box Widget
 */
function register_box_widget()
{
  register_widget( 'VF_Widget_Box' );
}
add_action( 'widgets_init', 'register_box_widget' ); ?>