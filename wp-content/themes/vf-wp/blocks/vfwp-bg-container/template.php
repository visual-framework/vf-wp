<?php

$heading = get_field('heading');
$sub_heading = get_field('sub_heading');
$link = get_field('link');
$text = get_field('text');
$anchor = get_field('anchor');
$background_color = get_field('background_color');
$background_image = get_field('background_image');
$header_color = get_field('header_color');
$container_content = get_field('container_content');
$block_id = get_field('block_id');

$type = get_field('select_type');

if (empty($type)) {
  $type = 'default';
}
if (is_array($type)) {
  $type = $type[0];
}

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Block is container style
$is_container = (bool) get_field('is_container');

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};

?>
<style>
  <?php if ($header_color['label'] == 'White') { ?>
    <?php echo '#' . ($block_id); ?> .vf-section-header,
    <?php echo '#' . ($block_id); ?> .vf-section-header__heading--is-link,
    <?php echo '#' . ($block_id); ?> .vf-section-header__heading--is-link:visited {
   color: <?php echo ($header_color['value']) ?> !important;
 }
<?php }
else { echo '';} ?>
  <?php if ($background_color == 'image') { ?>
  <?php echo '#' . ($block_id); ?> .vf-card-container::before {
  background:url(<?php echo esc_url($background_image); ?>);
  background-position: 50%;
  background-size: cover; }
 <?php } ?>
</style>

<section id="<?php echo esc_attr($block_id); ?>">
<div class="vf-card-container | vf-u-fullbleed  
<?php if (($background_color == "-ui--grey--light") || ($background_color == "--grey--lightest") || ($background_color == "-ui--white")) { ?>
  | vf-u-background-color<?php echo esc_attr($background_color); ?> <?php } ?>


">
<?php if (get_field('container_content') == 'card') { ?>
<div class="vf-card-container__inner">
<?php } ?>
<?php if (!empty ($heading)) { ?>
<div class="vf-section-header | vf-u-margin__bottom--600">

  <?php 

  if ($type === 'default' || $type === 'has_sub-heading' || $type === 'has_text') { ?>

    <h2 class="vf-section-header__heading"
    
    <?php 
      if ($anchor) { 
       echo 'id="' . $anchor .'"';
      }

    ?>
    >
    <?php echo $heading; ?>
    
    </h2>

  <?php }

  if ($type === 'is_a_link' || $type === 'has_sub-heading_and_link' || $type === 'has_sub-heading_link_text' || $type === 'has_link_text') { ?>

    <a class="vf-section-header__heading vf-section-header__heading--is-link"
    <?php if ($anchor) { echo 'id="' . $anchor .'"';} ?>
    href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($heading); ?><svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>

  <?php } 

  if ($type === 'has_sub-heading' || $type === 'has_sub-heading_and_link' || $type === 'has_sub-heading_link_text') { ?>
    <p class="vf-section-header__subheading"><?php echo esc_html($sub_heading); ?></p>

  <?php } 

  if ($type === 'has_text' || $type === 'has_sub-heading_link_text' || $type === 'has_link_text') { ?>
    <p class="vf-section-header__text"><?php echo esc_html($text); ?></p>

  <?php } }?>

</div>

    <InnerBlocks />
    <?php if (get_field('container_content') == 'card') { ?>
    </div>
<?php } ?>
</div>
</section>