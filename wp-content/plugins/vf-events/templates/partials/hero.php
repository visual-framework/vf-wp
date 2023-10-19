<?php

$title = get_the_title($post->post_parent);
$event_type = get_field('vf_event_event_type', $post->post_parent);
$ss_event_name = get_field('vf_event_ss_subtype', $post->post_parent);
$displayed = get_field('vf_event_displayed', $post->post_parent);
$embo_event_name = get_field('vf_event_embo_subtype');
$industry_event_type = get_field('vf_event_industry_type');
$location = get_field('vf_event_location');
$banner_text = get_field('vf_event_banner_text');
$canceled = get_field('vf_event_canceled');
$hero_image = get_field('vf_event_hero', $post->post_parent);
if (is_array($hero_image)) {
  $hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}
?>
<?php if (($canceled == 'yes') || ($canceled == 'postponed') ) { ?>
<div class="vf-banner vf-banner--alert vf-banner--<?php if ($canceled == 'yes') { echo 'danger'; } else if ($canceled == 'postponed') { echo 'info'; } ?>">
    <div class="vf-banner__content">
        <p class="vf-banner__text"><?php echo ($banner_text); ?></p>
    </div>
</div>
<?php } ?>

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
        elseif (!empty ($embo_event_name)) {
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
      </p>
    <h1 class="vf-hero__heading" style="font-size: 30px;">
      <?php echo $title; ?>
    </h1>
  </div>
</section>
