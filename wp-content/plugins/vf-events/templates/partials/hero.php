<?php

$title = get_the_title($post->post_parent);

$start_date = get_field('vf_event_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_location', $post->post_parent);
$location = strtoupper($location);

$submission_closing = get_field('vf_event_submission_closing', $post->post_parent);
$registration_closing = get_field('vf_event_registration_closing', $post->post_parent);

$event_type = get_field('vf_event_event_type', $post->post_parent);
$event_type_custom = get_field('vf_event_event_type_custom', $post->post_parent);
$event_topic = get_field('vf_event_event_topic', $post->post_parent);

$social_media_container = get_field('vf_event_social_media', $post->post_parent);

$registration_link = get_field('vf_event_registration_link', $post->post_parent);

$logo_image = get_field('vf_event_logo', $post->post_parent);
$logo_image = wp_get_attachment_image($logo_image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$hero_image = get_field('vf_event_hero', $post->post_parent);
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$theme = get_field('vf_event_theme', $post->post_parent);
$variant = 'vf-hero-theme--' . $theme;
if ($theme == 'none') {
  $variant = '';
}
?>

<section class="vf-hero vf-hero--hard <?php echo ($variant); ?> | vf-u-margin__bottom--800">
    <style>
  .vf-hero {
    <?php 
    if ($hero_image) { ?>
      --vf-hero-bg-image: url('<?php echo esc_url($hero_image); ?>');
   <?php }; ?>
    --vf-hero-grid__row--initial: 275px;
  }
  </style>
        <div class="vf-hero__content">
    <h2 class="vf-hero__heading"> 
      <?php
      if ($event_type['value'] === 'custom') {
        echo esc_html($event_type_custom);
      }
      else {
      echo esc_html($event_type['label']); 
      }
      ?>   </h2>
    <p class="vf-hero__text" style="font-size: 32px;">
    <?php echo $title; ?>
    </p>
  </div>
</section>

