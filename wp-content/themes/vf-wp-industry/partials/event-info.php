<?php

$title = get_the_title($post->post_parent);

$start_date = get_field('vf_event_industry_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_industry_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_industry_location', $post->post_parent);
$time = get_field('vf_event_industry_start_time', $post->post_parent);
$venue = get_field('vf_event_industry_venue', $post->post_parent);

$abstract_closing = get_field('vf_event_industry_submission_closing', $post->post_parent);
$application_closing = get_field('vf_event_industry_application_deadline', $post->post_parent);
$registration_closing = get_field('vf_event_industry_registration_closing', $post->post_parent);
$registration_open = get_field('vf_event_industry_registration_opening', $post->post_parent);

$event_topic = get_field('vf_event_industry_event_topic', $post->post_parent);

$registration_link = get_field('vf_event_industry_registration_link', $post->post_parent);
$contact = get_field('vf_event_industry_contact', $post->post_parent);
$contact_name = get_field('vf_event_industry_contact_name', $post->post_parent);
$hashtag = get_field('vf_event_industry_hashtag', $post->post_parent);
$abstract_link = get_field('vf_event_industry_abstract_link', $post->post_parent);
$poster_file = get_field('vf_event_industry_poster_file', $post->post_parent);
$abstract_button = get_field('vf_event_industry_abstract_submission_button_text', $post->post_parent);
$abstract_button = ucfirst(strtolower($abstract_button));
$register_button = get_field('vf_event_industry_registration_button_text', $post->post_parent);
$register_button = ucfirst(strtolower($register_button));
$info_text = get_field('vf_event_industry_info_text', $post->post_parent);
$registration_type = get_field('vf_event_industry_registration_type', $post->post_parent);

$social_url = get_the_permalink();


$logo_image = get_field('vf_event_industry_logo', $post->post_parent);
$logo_image = wp_get_attachment_image($logo_image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$poster_image = get_field('vf_event_industry_poster', $post->post_parent);
$poster_image = wp_get_attachment_image($poster_image['ID'], 'large', false, array(
    'style'    => 'max-width: 175px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$now = new DateTime();
$application_date = new DateTime($application_closing);
$registration_date = new DateTime($registration_closing);
$abstract_date = new DateTime($abstract_closing);

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
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration opens date:</span> <span class="vf-u-text-color--grey">
<?php
        echo esc_html($registration_open);
      ?>
      </span></p>
      <?php } 
if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration deadline:</span> <span class="vf-u-text-color--grey">
      <?php if ($registration_date < $now) {
        echo 'Closed';
      }  
      else if ($registration_closing) {
        echo esc_html($registration_closing);
      } ?>
      </span></p>
      <?php } ?>
      
      <?php if ( ! empty($venue)) { ?>
      <p class="vf-text-body vf-text-body--3 vf-u-margin__bottom--100"><span style="font-weight: 600;">Venue:</span></p> <p class="vf-text-body vf-text-body--3"><span
          class="vf-u-text-color--grey"><?php echo esc_html($venue); ?></span></p>
      <?php } ?>

    <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">

    <?php 

    // Organisers
    if( have_rows('vf_event_industry_organisers_event_template') ): ?>
      <p class="vf-text-body vf-text-body--3 vf-u-margin__bottom--100"><span style="font-weight: 600;">Organisers: </span></p>
      <?php
      while( have_rows('vf_event_industry_organisers_event_template') ) : the_row();
        $organiser_name = get_sub_field('vf_event_industry_organisers_name');
        $organiser_affiliation = get_sub_field('vf_event_industry_organisers_affiliation'); ?>
        <ul class="vf-list vf-u-margin__bottom--sm">
					<li class="vf-list__item vf-u-margin--0 vf-u-margin__top--md">
            <?php echo esc_html($organiser_name); ?>
						<br>
						<span class="vf-text-body vf-text-body--5 vf-u-text-color--grey" style="text-transform: uppercase;"><?php echo esc_html($organiser_affiliation); ?>
						</span>
					</li>
      <?php
      endwhile; ?>
      </ul>
      <?php
  else :
  endif; ?>

    </div>
  </div>

</div>
