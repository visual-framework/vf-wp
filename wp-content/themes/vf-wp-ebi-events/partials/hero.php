<?php

$hero_image = get_field('vf_event_hero');
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

 if ($canceled == 'yes') {
   $banner_text = "This event has been cancelled.";
 }
 elseif($canceled == 'postponed') {
   $banner_text = "This event has been postponed.";
}
 if (($canceled == 'yes') || ($canceled == 'postponed') ) { ?>
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
        else if ($event_type['label'] == "Public event"){
           echo esc_html($event_type['label']) . ' | ' . esc_html($public_type['label']);
        }
        else if ($event_type['label'] == "Seminar") {
           echo esc_html($event_type['label']) . ' | ' . esc_html($seminar_type['label']);
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
