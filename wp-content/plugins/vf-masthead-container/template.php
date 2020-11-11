<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$heading = get_field('vf_masthead_heading');
$heading_link = get_field('vf_masthead_heading_link');
$heading_additional = get_field('vf_masthead_additional');
$subheading = get_field('vf_masthead_subheading');
$image = get_field('vf_masthead_image');
$variant = get_field('vf_masthead_variant');
$block_color = get_field('vf_masthead_block_bg');

$variant_class = '';

if ($variant == 'default') {
  $variant_class = '';
}
else if ($variant == 'primary') {
  $variant_class = 'vf-masthead-theme--primary';
}
else if ($variant == 'secondary') {
  $variant_class = 'vf-masthead-theme--secondary';
}
else if ($variant == 'tertiary') {
  $variant_class = 'vf-masthead-theme--tertiary';
}
else if ($variant == 'has_image') {
  $variant_class = 'vf-masthead--has-image';
}
else if ($variant == 'with_title_block') {
  $variant_class = 'vf-masthead--with-title-block';
}
else if ($variant == 'with_title_block_and_image') {
  $variant_class = 'vf-masthead--with-title-block';
}

$theme_class = '';
if ($variant == 'with_title_block')
$theme_class = 'vf-masthead-theme--' . $block_color
?>

<?php if ($variant == 'has_image' || $variant == 'with_title_block_and_image') { ?>
<style>
    .vf-masthead {
    --vf-masthead__bg-image: url(<?php echo esc_url($image['url']) ?>);
    background-image: var(--vf-masthead__bg-image);
    }
</style>
<?php } ?>

<div class="vf-masthead <?php echo($variant_class) ?> <?php echo($theme_class) ?> | vf-u-fullbleed | vf-u-margin__bottom--800"
 data-vf-js-masthead>
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
<!--/vf-masthead-->
