<?php

$title = get_the_title($post->post_parent);

$start_date = get_field('vf_event_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field('vf_event_location', $post->post_parent);

$submission_closing = get_field('vf_event_submission_closing', $post->post_parent);
$registration_closing = get_field('vf_event_registration_closing', $post->post_parent);

$event_topic = get_field('vf_event_event_topic', $post->post_parent);

$registration_link = get_field('vf_event_registration_link', $post->post_parent);
$contact = get_field('vf_event_contact', $post->post_parent);
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
    'style'    => 'max-width: 175px; height: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

?>

<div>
  <figure class="vf-figure vf-figure--align vf-figure--align-centered | vf-u-margin__bottom--400">
    <?php echo($logo_image); ?>
  </figure>
  <div>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Date:</span>

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
    </p>

    <?php if ( ! empty($submission_closing)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Abstract submission deadline:</span> <?php echo esc_html($submission_closing); ?></p>
    <?php } ?>

    <?php if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Registration
        deadline:</span> <?php echo esc_html($registration_closing); ?></p>
    <?php } ?>

    <?php if ( ! empty($location)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Location:</span> <?php echo esc_html($location); ?></p>
    <?php } ?>

    <?php if ( ! empty($contact)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Contact: </span><a href="#"><?php echo esc_html($contact); ?></a></p>
    <?php } ?>

    <div class="vf-grid">
      <?php if ( ! empty($registration_link)) { ?>
      <div style="text-align: center;">
        <a href="<?php echo esc_url($registration_link); ?>"><button
            class="vf-button vf-button--primary vf-button--sm | vf-u-width__100 | vf-u-margin__top--400"><?php echo($register_button); ?></button></a>
      </div>
      <?php } ?>
      
      <?php if ( ! empty($abstract_link)) { ?>
      <div style="text-align: center;">
        <a href="<?php echo esc_url($abstract_link); ?>"><button
            class="vf-button vf-button--tertiary vf-button--sm | vf-u-width__100 | vf-u-margin__top--400"><?php echo($abstract_button); ?></button></a>
      </div>
      <?php } ?>
    </div>

    <div class="vf-grid | vf-u-margin__top--800">
      <?php if ( ! empty($poster_image)) { ?>
      <div>
        <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Download event poster</span></p>
        <a href="<?php echo $poster_file['url']; ?>">
          <figure class="vf-figure | vf-u-margin__top--400">
            <?php echo($poster_image); ?>
          </figure>
        </a>
      </div>
      <?php } ?>

      <svg aria-hidden="true" display="none" class="vf-icon-collection vf-icon-collection--social">
        <defs>
          <g id="vf-social--linkedin">
            <rect xmlns="http://www.w3.org/2000/svg" width="5" height="14" x="2" y="8.5" rx=".5" ry=".5" />
            <ellipse xmlns="http://www.w3.org/2000/svg" cx="4.48" cy="4" rx="2.48" ry="2.5" />
            <path xmlns="http://www.w3.org/2000/svg"
              d="M18.5,22.5h3A.5.5,0,0,0,22,22V13.6C22,9.83,19.87,8,16.89,8a4.21,4.21,0,0,0-3.17,1.27A.41.41,0,0,1,13,9a.5.5,0,0,0-.5-.5h-3A.5.5,0,0,0,9,9V22a.5.5,0,0,0,.5.5h3A.5.5,0,0,0,13,22V14.5a2.5,2.5,0,0,1,5,0V22A.5.5,0,0,0,18.5,22.5Z" />
          </g>
          <g id="vf-social--facebook">
            <path xmlns="http://www.w3.org/2000/svg"
              d="m18.14 7.17a.5.5 0 0 0 -.37-.17h-3.77v-1.41c0-.28.06-.6.51-.6h3a.44.44 0 0 0 .35-.15.5.5 0 0 0 .14-.34v-4a.5.5 0 0 0 -.5-.5h-4.33c-4.8 0-5.17 4.1-5.17 5.35v1.65h-2.5a.5.5 0 0 0 -.5.5v4a.5.5 0 0 0 .5.5h2.5v11.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-11.5h3.35a.5.5 0 0 0 .5-.45l.42-4a.5.5 0 0 0 -.13-.38z" />
          </g>
          <g id="vf-social--twitter">
            <path xmlns="http://www.w3.org/2000/svg"
              d="M23.32,6.44a.5.5,0,0,0-.2-.87l-.79-.2A.5.5,0,0,1,22,4.67l.44-.89a.5.5,0,0,0-.58-.7l-2,.56a.5.5,0,0,1-.44-.08,5,5,0,0,0-3-1,5,5,0,0,0-5,5v.36a.25.25,0,0,1-.22.25c-2.81.33-5.5-1.1-8.4-4.44a.51.51,0,0,0-.51-.15A.5.5,0,0,0,2,4a7.58,7.58,0,0,0,.46,4.92.25.25,0,0,1-.26.36L1.08,9.06a.5.5,0,0,0-.57.59,5.15,5.15,0,0,0,2.37,3.78.25.25,0,0,1,0,.45l-.53.21a.5.5,0,0,0-.26.69,4.36,4.36,0,0,0,3.2,2.48.25.25,0,0,1,0,.47A10.94,10.94,0,0,1,1,18.56a.5.5,0,0,0-.2,1,20.06,20.06,0,0,0,8.14,1.93,12.58,12.58,0,0,0,7-2A12.5,12.5,0,0,0,21.5,9.06V8.19a.5.5,0,0,1,.18-.38Z" />
          </g>
        </defs>
      </svg>
      <div class="vf-social-links">
        <p class="vf-text-body vf-text-body--3" style="font-weight: 600;">
          Share this event
        </p>
        <ul class="vf-social-links__list">
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $social_url; ?>&amp;via=embl">
              <span class="vf-u-sr-only">
                twitter
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--twitter">
                </use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_url; ?>">
              <span class="vf-u-sr-only">
                facebook
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--facebook">
                </use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_url; ?>&title=<?php echo $title; ?>">
              <span class="vf-u-sr-only">
                linkedin
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--linkedin">
                </use>
              </svg>
            </a>
          </li>
        </ul>

        <?php if ( ! empty($hashtag)) { ?>
        <p class="vf-text-body vf-text-body--2 vf-u-text-color--grey"> <?php echo esc_html($hashtag); ?> </p>
        <?php } ?>

      </div>
    </div>
  </div>

</div>
