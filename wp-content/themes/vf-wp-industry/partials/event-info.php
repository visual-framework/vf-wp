<?php

$title = get_the_title($post->post_parent);

$start_date = get_field('vf_event_industry_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_industry_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_industry_location', $post->post_parent);
$time = get_field('vf_event_industry_start_time', $post->post_parent);
$venue = get_field('vf_event_industry_venue', $post->post_parent);

$registration_closing = get_field('vf_event_industry_registration_closing', $post->post_parent);
$registration_open = get_field('vf_event_industry_registration_opening', $post->post_parent);
$registration_link = get_field('vf_event_industry_registration_link', $post->post_parent);
$register_button = get_field('vf_event_industry_registration_button_text', $post->post_parent);
$register_button = ucfirst(strtolower($register_button));
$registration_type = get_field('vf_event_industry_registration_type', $post->post_parent);

$now = new DateTime();
$registration_date = new DateTime($registration_closing);

?>

<div>
  <div>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Date:</span>
      <span class="vf-u-text-color--grey">
        <?php 

      // Event dates
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
      </span>
    </p>

    <?php if ( ! empty($time)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Time:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($time); ?></span></p>
    <?php } ?>



    <?php 

// Registration dates
if ( ! empty($registration_open)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration opens date:</span> <span
        class="vf-u-text-color--grey">
        <?php echo esc_html($registration_open); ?>
      </span></p>
    <?php } 
if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration deadline:</span> <span
        class="vf-u-text-color--grey">
        <?php if ($registration_date < $now) {
        echo 'Closed';
      }  
      else if ($registration_closing) {
        echo esc_html($registration_closing);
      } ?>
      </span></p>
    <?php } ?>

    <?php if ( ! empty($venue)) { ?>
    <p class="vf-text-body vf-text-body--3 vf-u-margin__bottom--100"><span style="font-weight: 600;">Venue:</span></p>
    <p class="vf-text-body vf-text-body--3"><span class="vf-u-text-color--grey"><?php echo esc_html($venue); ?></span>
    </p>
    <?php } ?>

    <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">

      <?php 

    // Organisers
    if( have_rows('vf_event_industry_organisers_event_template') ): ?>
      <p class="vf-text-body vf-text-body--3 vf-u-margin__bottom--100"><span style="font-weight: 600;">Organisers:
        </span></p>
      <?php
      while( have_rows('vf_event_industry_organisers_event_template') ) : the_row();
        $organiser_name = get_sub_field('vf_event_industry_organisers_name');
        $organiser_affiliation = get_sub_field('vf_event_industry_organisers_affiliation'); ?>
      <ul class="vf-list vf-u-margin__bottom--sm">
        <li class="vf-list__item vf-u-margin--0 vf-u-margin__top--md">
          <?php echo esc_html($organiser_name); ?>
          <br>
          <span class="vf-text-body vf-text-body--5 vf-u-text-color--grey"
            style="text-transform: uppercase;"><?php echo esc_html($organiser_affiliation); ?>
          </span>
        </li>
        <?php
      endwhile; ?>
      </ul>
      <?php else : endif; ?>
    </div>
  </div>

</div>
