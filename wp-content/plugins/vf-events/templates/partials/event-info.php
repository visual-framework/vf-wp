<?php

$title = get_the_title();

$start_date = get_field('vf_event_start_date');
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_end_date');
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_location');
$other_location = get_field('vf_event_other_location');
$time = get_field('vf_event_start_time');
$venue = get_field('vf_event_venue');

$abstract_closing = get_field('vf_event_submission_closing');
$application_closing = get_field('vf_event_application_deadline');
$registration_closing = get_field('vf_event_registration_closing');

$event_topic = get_field('vf_event_event_topic');

$registration_link = get_field('vf_event_registration_link');
$contact = get_field('vf_event_contact');
$contact_name = get_field('vf_event_contact_name');
$hashtag = get_field('vf_event_hashtag');
$abstract_link = get_field('vf_event_abstract_link');
$poster_file = get_field('vf_event_poster_file');
$abstract_button = get_field('vf_event_abstract_submission_button_text');
$abstract_button = ucfirst(strtolower($abstract_button));
$register_button = get_field('vf_event_registration_button_text');
$register_button = ucfirst(strtolower($register_button));
$info_text = get_field('vf_event_info_text');
$registration_type = get_field('vf_event_registration_type');

$social_url = get_the_permalink();


$logo_image = get_field('vf_event_logo');
$logo_image = wp_get_attachment_image($logo_image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$poster_image = get_field('vf_event_poster');
$poster_image = wp_get_attachment_image($poster_image['ID'], 'large', false, array(
    'style'    => 'max-width: 175px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$current_date = new DateTime();
$now = $current_date->format('j M Y');
?>

<div>
  <?php if ($event_organiser == "cco_hd") { ?>
  <figure class="vf-figure vf-figure--align vf-figure--align-centered | vf-u-margin__bottom--400">
    <?php 
    // Event logo
    echo($logo_image); ?>
  </figure>
  <?php } ?>
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
    <?php 
    if (!empty($location)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Location:</span> <span class="vf-u-text-color--grey">
        <?php
        if (!empty($other_location)) {
        echo esc_html($other_location); 
        }
        else {
          echo esc_html($location);
        } ?>
    </span>
    </p>
    <?php } ?>

    <?php if ( ! empty($time)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Time:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($time); ?></span></p>
    <?php } ?>

    <?php if ( ! empty($venue)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Venue:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($venue); ?></span></p>
    <?php } ?>

    <?php if ( ! empty(($abstract_closing) || ($application_closing) || ($registration_closing) || ($info_text))) { ?>
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Deadline(s):</span></p>
    <?php } ?>

    <?php if ( ! empty($abstract_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>Abstract submission:</span> <span class="vf-u-text-color--grey"> <?php if ($abstract_closing < $now) {
          echo 'Closed';
        }  
        else {
          echo esc_html($abstract_closing);
        }
      ?></span></p>
    <?php } 
    
    /* Application date
    if ( ! empty($application_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>Application:</span> <span class="vf-u-text-color--grey">
        <?php if ($application_closing < $now) {
          echo 'Closed';
        }  
        else if ($application_closing) {
          echo esc_html($application_closing);
        }

      ?></span></p>
    <?php } 
    // Show info text
            else if (!empty($info_text)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>Application:</span> <span class="vf-u-text-color--grey">
        <?php echo esc_html($info_text); ?>
      </span></p>
    <?php  }
    ?> */

    // Registration dates
    if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>
        <?php if ($registration_type == 'registration') { ?>
        Registration:
        <?php } else if ($registration_type == 'application'){ ?>
        Application:
        <?php  } ?>
      </span> <span class="vf-u-text-color--grey">
        <?php if ($registration_closing < $now) {
          echo 'Closed';
        }  
        else if ($registration_closing) {
          echo esc_html($registration_closing);
        } ?>
      </span></p>
    <?php } 
    // Show info text
    else if (!empty($info_text)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>
        <?php if ($registration_type == 'registration') { ?>
        Registration:
        <?php } else if ($registration_type == 'application'){ ?>
        Application:
        <?php  } ?>
        <span class="vf-u-text-color--grey">
          <?php echo esc_html($info_text); ?>
        </span></p>
    <?php  }
    ?>

    <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
      <?php 
      // Buttons
      if ( !empty($registration_link)) { 
            if (
              (($registration_date > $now)) 
              || 
              (($application_closing) && ($application_closing > $now))) { ?>
      <div style="display: inline-block;">
        <a href="<?php echo esc_url($registration_link); ?>" target="_blank"><button
            class="vf-button vf-button--primary vf-button--sm"><?php echo($register_button); ?></button></a>
      </div>
      <?php }} ?>

      <?php if ( ! empty($abstract_link)) { 
                if (($abstract_closing > $now)) {?>
      <div style="display: inline-block;">
        <a href="<?php echo esc_url($abstract_link); ?>" target="_blank"><button
            class="vf-button vf-button--tertiary vf-button--sm"><?php echo($abstract_button); ?></button></a>
      </div>
      <?php }} ?>
    </div>
    <?php if ( ! empty(($abstract_closing) || ($application_closing) || ($registration_closing) || ($info_text))) { ?>
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <?php }

    // Organisers
    if( have_rows('vf_event_organisers_event_template') ): ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Organisers: </span></p>
    <?php
      while( have_rows('vf_event_organisers_event_template') ) : the_row();
        $organiser_name = get_sub_field('vf_event_organisers_name');
        $organiser_affiliation = get_sub_field('vf_event_organisers_affiliation'); ?>
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
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <?php
    else :
    endif;

    // Contact
    if ( ! empty($contact)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Contact: </span><a
        href="mailto:<?php echo esc_attr($contact); ?>"><?php echo esc_html($contact_name); ?></a></p>
    <?php } ?>
    <div class="vf-grid">
      <?php 
      
      // Poster image
      if ( ! empty($poster_image) && $event_organiser != "science_society") { ?>
      <div>
        <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Download event poster</span></p>
        <a href="<?php echo $poster_file['url']; ?>">
          <figure class="vf-figure | vf-u-margin__top--400">
            <?php echo($poster_image); ?>
          </figure>
        </a>
      </div>
      <?php }
      include( plugin_dir_path( __FILE__ ) . './social-media-icons.php'); 
      ?>
    </div>
  </div>
</div>
