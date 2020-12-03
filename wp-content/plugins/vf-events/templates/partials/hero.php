<?php

$title = get_the_title($post->post_parent);
$event_type = get_field('vf_event_event_type', $post->post_parent);

$hero_image = get_field('vf_event_hero', $post->post_parent);
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed">
  <style>
    .vf-hero {
      <?php if ($hero_image) {
        ?>--vf-hero--bg-image: url('<?php echo esc_url($hero_image); ?>');
        <?php
      }
      else {
        ?>--vf-hero--bg-image-size: auto 28.5rem;
        <?php
      }
      ?>
    }
  </style>

  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading" style="font-size: 24px;">
      <?php echo $title; ?>
      <span class="vf-hero__heading--additional">
        <?php
      if (!empty ($event_type)) {
        echo esc_html($event_type->name);
      }
      ?>
      </span>
    </h2>
  </div>
</section>
