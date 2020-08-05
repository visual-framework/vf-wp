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

?>

<section class="vf-hero vf-hero--inlay vf-hero--hard | vf-hero-theme--<?php echo ($theme); ?> | vf-u-margin__bottom--xxl">
    <style>
  .vf-hero {
    <?php 
    if ($hero_image) { ?>
      --vf-hero-bg-image: url('<?php echo esc_url($hero_image); ?>');
       <?php } 
        else { ?>
          --vf-hero-bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/vf-hero-intense.png');
   <?php }; ?>
    --vf-hero-grid__row--initial: 384px;
  }

  </style>
        <div class="vf-hero__content">
    <h2 class="vf-hero__heading">
      EMBL <?php echo esc_html($event_type); ?>   </h2>
    <p class="vf-hero__text" style="font-size: 32px;">
    <?php echo $title; ?>
    </p>
  </div>
</section>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-u-padding--md | vf-u-padding__right--0">
    <p class="vf-text-body vf-text-body--2">

      <?php 
      if ( ! empty($start_date)) {
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
            ?> 
      <?php } 
        else {
          echo $start->format('j M Y'); 
        } }
      ?>
      &nbsp;&nbsp;
      <?php 
      if ( ! empty($location)) {
      echo esc_html($location); }?></p>

    <?php if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--4" style="font-weight: 400;">Application deadline: <?php echo esc_html($registration_closing); ?></p>
    <?php } ?>

    <?php if ( ! empty($submission_closing)) { ?>
    <p class="vf-text-body vf-text-body--4" style="font-weight: 400;">Abstract submission deadline: <?php echo esc_html($submission_closing); ?></p>
    <?php } ?>

  </div>

  <div class="vf-u-padding__top--md">
    <?php if ( ! empty($registration_link)) { ?>
      <a href="<?php echo esc_url($registration_link); ?>"><button class="vf-button vf-button--primary vf-button--sm">Register</button></a>
    <?php } ?>
  </div>
  <div>
    <figure class="vf-figure vf-figure--align vf-figure--align-centered">
    <?php echo($logo_image); ?> 
    </figure>
  </div>
</section>

<hr class="vf-divider">