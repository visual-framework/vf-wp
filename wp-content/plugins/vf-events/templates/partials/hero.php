<?php

$event_post_id = isset($event_post_id) ? $event_post_id : (!empty($post->post_parent) ? $post->post_parent : get_the_ID());
$title = get_the_title($event_post_id);
$event_type = get_field('vf_event_event_type', $event_post_id);
$ss_event_name = get_field('vf_event_ss_subtype', $event_post_id);
$displayed = get_field('vf_event_displayed', $event_post_id);
$embo_event_name = get_field('vf_event_embo_subtype', $event_post_id);
$industry_event_type = get_field('vf_event_industry_type', $event_post_id);
$location = get_field('vf_event_location', $event_post_id);
$banner_text = get_field('vf_event_banner_text', $event_post_id);
$canceled = get_field('vf_event_canceled', $event_post_id);
$hero_image = get_field('vf_event_hero', $event_post_id);
$event_organiser = isset($event_organiser) ? $event_organiser : '';
$event_type_label = is_array($event_type) && !empty($event_type['label']) ? $event_type['label'] : '';
$embo_event_label = is_array($embo_event_name) && !empty($embo_event_name['label']) ? $embo_event_name['label'] : '';
$ss_event_label = is_array($ss_event_name) && !empty($ss_event_name['label']) ? $ss_event_name['label'] : '';
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
        if (!empty($hero_kicker_override)) {
          echo esc_html($hero_kicker_override);
        }
        elseif (!empty ($displayed)) {
          echo esc_html($displayed);
        }
        elseif (!empty($embo_event_label)) {
          echo esc_html($embo_event_label);
        }
        elseif (($event_organiser == 'cco_hd') && in_array($event_type_label, array('Conference', 'Course', 'Webinar'), true)) {
          echo 'EMBL ' . esc_html($event_type_label);
        }
        elseif (!empty($ss_event_label) && $event_organiser == "science_society") {
          echo esc_html($ss_event_label);
        }
        else {
           echo esc_html($event_type_label);
        }
      ?>
      </p>
    <h1 class="vf-hero__heading" style="font-size: 30px;">
      <?php echo $title; ?>
    </h1>
  </div>
</section>
