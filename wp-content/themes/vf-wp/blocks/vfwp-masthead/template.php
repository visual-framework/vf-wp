<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$heading = get_field('vf_masthead_heading');
$heading_link = get_field('vf_masthead_heading_link');
$heading_additional = get_field('vf_masthead_additional');
$subheading = get_field('vf_masthead_subheading');
$image = get_field('vf_masthead_image');
$variant = get_field('vf_masthead_variant');
$fg_color = get_field('vf_masthead_fg_color');
$bg_color = get_field('vf_masthead_bg_color');

$class = '';

if ($variant == 'default') {
  $class = '';
}
else if ($variant == 'primary') {
  $class = 'vf-masthead-theme--primary';
}
else if ($variant == 'secondary') {
  $class = 'vf-masthead-theme--secondary';
}
else if ($variant == 'tertiary') {
  $class = 'vf-masthead-theme--tertiary';
}
else if ($variant == 'has_image') {
  $class = 'vf-masthead--has-image';
}
else if ($variant == 'with_title_block') {
  $class = 'vf-masthead--with-title-block';
}
else if ($variant == 'with_title_block_and_image') {
  $class = 'vf-masthead--with-title-block';
}
?>


<style>
    .vf-masthead {
    --vf-masthead__bg-image: url();
    <?php if ($variant == 'has_image' || $variant == 'with_title_block_and_image' || $variant == 'with_title_block') { ?>
    --global-theme-fg-color: <?php echo ($fg_color) ?>;
    <?php } ?>
  }
    <?php if ($variant == 'with_title_block' || $variant == 'with_title_block_and_image') { ?>
      .vf-masthead--with-title-block .vf-masthead__title:before {
    background-color: <?php echo ($bg_color) ?>;
      }
    <?php } ?>

</style>
<div class="vf-masthead <?php echo($class) ?> | vf-u-margin__bottom--xxl" 
<?php if ($variant == 'has_image' || $variant == 'with_title_block_and_image') {?>
style="background-image: url(<?php echo esc_url($image['url']) ?>);" data-vf-js-masthead>
         <?php } ?>
  <div class="vf-masthead__inner">
    <div class="vf-masthead__title">
      <h1 class="vf-masthead__heading">
        <a href="
        <?php
          if (!empty ($heading_link)) {
            echo ($heading_link);
          }
          else {
            echo home_url();
          } 
          ?>" class="vf-masthead__heading__link">
          <?php
          if (!empty ($heading)) {
            echo ($heading);
          }
          else {
            echo esc_html(get_bloginfo('name'));
          } 
          ?>
        </a>
        <span class="vf-masthead__heading--additional">
       <?php
       if (!empty($heading_additional)) { ?>
          <?php echo ($heading_additional); ?>
       <?php } 
       else {
        echo get_bloginfo('description'); }?>
        </span>
      </h1>
      <?php
      if ($subheading) { ?>
      <h2 class="vf-masthead__subheading">
        <span class="vf-masthead__location">
        <?php echo ($subheading); ?>
        </span>
      </h2>
    <?php } ?>
    </div>
  </div>
</div>
<!--/vf-masthead-->
