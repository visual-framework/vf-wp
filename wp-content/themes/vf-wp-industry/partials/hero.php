<?php

$title = get_the_title($post->post_parent);
$event_type = get_field('vf_event_industry_event_type', $post->post_parent);
$displayed = get_field('vf_event_industry_displayed', $post->post_parent);
$hero_image = get_field('vf_event_industry_hero', $post->post_parent);
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
?>

<section class="vf-hero | vf-u-fullbleed">
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

  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
      <p class="vf-hero__kicker">
        <?php
        if (!empty ($displayed)) {
          echo esc_html($displayed);
        }
        else {
           echo esc_html($event_type['label']);
        }
      ?>
      </p>
    <h2 class="vf-hero__heading" style="font-size: 30px;">
      <?php echo $title; ?>
    </h2>
  </div>
</section>
