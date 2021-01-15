<?php

$title = get_the_title($post->post_parent);
$event_type = get_field('vf_event_event_type', $post->post_parent);
$ss_event_name = get_field('vf_event_ss_event_name', $post->post_parent);
$displayed = get_field('vf_event_displayed', $post->post_parent);
$embo_event_name = get_field('vf_event_embo_event_name');
$location = get_field('vf_event_location');

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
    <h2 class="vf-hero__heading" style="font-size: 30px;">
      <?php echo $title; ?>
      <span class="vf-hero__heading--additional">
        <?php
        if (!empty ($displayed)) {
          echo esc_html($displayed);
        }
        elseif (!empty ($embo_event_name) && (($location == "EMBO") || ($location == "Virtual"))) {
          echo esc_html($embo_event_name['label']);
        }
        elseif (($event_organiser == 'cco_hd') && (($event_type['label'] == 'Conference') || ($event_type['label'] == 'Course') || ($event_type['label'] == 'Webinar'))) {
          echo 'EMBL ' . esc_html($event_type['label']);
        }
        elseif (!empty ($ss_event_name) && $event_organiser == "science_society") {
          echo esc_html($ss_event_name['label']);
        }

        else {
           echo esc_html($event_type['label']);
        }

      ?>
      </span>
    </h2>
  </div>
</section>
