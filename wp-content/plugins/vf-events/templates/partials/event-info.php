<?php

$title = get_the_title($post->post_parent);

$start_date = get_field('vf_event_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_location', $post->post_parent);
$time = get_field('vf_event_start_time', $post->post_parent);
$venue = get_field('vf_event_venue', $post->post_parent);

$abstract_closing = get_field('vf_event_submission_closing', $post->post_parent);
$application_closing = get_field('vf_event_application_deadline', $post->post_parent);
$registration_closing = get_field('vf_event_registration_closing', $post->post_parent);

$event_topic = get_field('vf_event_event_topic', $post->post_parent);

$registration_link = get_field('vf_event_registration_link', $post->post_parent);
$contact = get_field('vf_event_contact', $post->post_parent);
$contact_name = get_field('vf_event_contact_name', $post->post_parent);
$hashtag = get_field('vf_event_hashtag', $post->post_parent);
$abstract_link = get_field('vf_event_abstract_link', $post->post_parent);
$poster_file = get_field('vf_event_poster_file', $post->post_parent);
$abstract_button = get_field('vf_event_abstract_submission_button_text', $post->post_parent);
$register_button = get_field('vf_event_registration_button_text', $post->post_parent);

$social_url = get_the_permalink();


$logo_image = get_field('vf_event_logo', $post->post_parent);
$logo_image = wp_get_attachment_image($logo_image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$poster_image = get_field('vf_event_poster', $post->post_parent);
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
  <?php if ($event_organiser == "cco_hd") { ?>
  <figure class="vf-figure vf-figure--align vf-figure--align-centered | vf-u-margin__bottom--400">
    <?php echo($logo_image); ?>
  </figure>
  <?php } ?>
  <div>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Date:</span>
    <span class="vf-u-text-color--grey">
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
     </span>   
    </p>
    <?php if ( ! empty($location)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Location:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($location); ?></span></p>
    <?php } ?>
    
    <?php if ( ! empty($time)) { ?>
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
      style="font-weight: 600;">Time:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($time); ?></span></p>
      <?php } ?>
      
      <?php if ( ! empty($venue)) { ?>
        <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Venue:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($venue); ?></span></p>
        <?php } ?>
        
    <?php if ( ! empty(($abstract_closing) || ($application_closing) || ($registration_closing))) { ?>
      <hr class="vf-divider | vf-u-margin__bottom--400">
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Deadline(s):</span></p>
    <?php } ?>

    <?php if ( ! empty($abstract_closing)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        >Abstract submission:</span> <span class="vf-u-text-color--grey">        <?php if ($abstract_date < $now) {
          echo 'Closed';
        }  
        else {
          echo esc_html($abstract_closing);
        }
      ?></span></p>
    <?php } ?>

    <?php if ( ! empty($application_closing)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        >Application:</span> <span class="vf-u-text-color--grey">
        <?php if ($application_date < $now) {
          echo 'Closed';
        }  
        else {
          echo esc_html($application_closing);
        }
      ?></span></p>
    <?php } ?>

    <?php if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span >Registration:</span> <span class="vf-u-text-color--grey">
    <?php if ($registration_date < $now) {
          echo 'Closed';
        }  
        else {
          echo esc_html($registration_closing);
        }
      ?>
    </span></p>
    <?php } ?>
    
        
        <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
          <?php if ( !empty($registration_link)) { 
            if (
              (($registration_closing) && ($registration_date > $now)) 
              || 
              (($application_closing) && ($application_date > $now))) { ?>
              <div style="display: inline-block;">
                <a href="<?php echo esc_url($registration_link); ?>"><button
                class="vf-button vf-button--primary vf-button--sm"><?php echo($register_button); ?></button></a>
              </div>
              <?php }} ?>
              
              <?php if ( ! empty($abstract_link)) { 
                if (($abstract_closing) && ($abstract_date > $now)) {?>
                <div style="display: inline-block;">
                  <a href="<?php echo esc_url($abstract_link); ?>"><button
                  class="vf-button vf-button--tertiary vf-button--sm"><?php echo($abstract_button); ?></button></a>
                </div>
                <?php }} ?>
              </div>
              <hr class="vf-divider | vf-u-margin__bottom--400">
              <?php if ( ! empty($contact)) { ?>
                <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
                style="font-weight: 600;">Contact: </span><a href="mailto:<?php echo esc_attr($contact); ?>"><?php echo esc_html($contact_name); ?></a></p>
                <?php } ?>
              <div class="vf-grid">
                <?php if ( ! empty($poster_image) && $event_organiser != "science_society") { ?>
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
