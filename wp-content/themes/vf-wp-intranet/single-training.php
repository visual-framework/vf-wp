<?php
get_header();


$now = new DateTime();
$current_date = $now->format('Y-m-d');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$locations = get_the_terms( $post->ID , 'event-location' );
$post_id = get_the_ID();
$start_date = get_field('vf-wp-training-start_date',$post_id);
$start_time = get_field('vf-wp-training-start_time',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf-wp-training-end_date',$post_id);
$end_time = get_field('vf-wp-training-end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('j M Y', $end_date);
$end_date_format = DateTime::createFromFormat('j M Y', $end_date);
$registrationLink = get_field('vf-wp-training-registration-link',$post_id); 
$registrationStatus = get_field('vf-wp-training-registration-status',$post_id); 
$registrationRemark = get_field('vf-wp-training-remark',$post_id); 
$registrationDeadline = get_field('vf-wp-training-registration-deadline',$post_id); 
$deadlineDate = new DateTime($registrationDeadline);
$registrationDeadlineFormatted = $deadlineDate->format('Y-m-d');
$venue = get_field('vf-wp-training-venue',$post_id);
$contact = get_field('vf-wp-training-contact',$post_id, false, false);
$additionalInfo = get_field('vf-wp-training-info',$post_id); 



if (!empty($start_time)) {
  $calendar_start_time = 'T' . $start_time_format->format('Hi') . '00';
}
else {
  $calendar_start_time = '';
}

if (!empty($end_time)) {
  $calendar_end_time = 'T' . $end_time_format->format('Hi') . '00';
}
elseif (empty($end_time) && !empty($start_time)) {
  $calendar_end_time = 'T' . $start_time_format->format('Hi') . '00';
}
else {
  $calendar_end_time = '';
}

if (!empty($end_date)) {
  $calendar_end_date = '/' . $end->format('Ymd');
}
else {
  $calendar_end_date = '/' . $start->format('Ymd');
}
?>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <div>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <?php the_content(); ?>
  </div>
  <div>
  <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
    </figure>

  <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span style="font-weight: 600;">Date:</span>
      <span class="vf-u-text-color--grey">
        <?php 
      // Date
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
      </span> |
      <span style="text-transform: none;">
        <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:"
          target="_blank" rel="nofollow">Add to calendar</a>
      </span>
    </p>

    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span style="font-weight: 600;">Time:</span>
      <span class="vf-u-text-color--grey">
        <?php 
        // Time
      if ( ! empty($start_time)) { ?>
        <?php        
         if ($start_time) {
          echo $start_time;
        } 
        if ($end_time) {
          echo ' - '. $end_time . ' CET';
        } ?>
      </span></p>
    <?php } 
         else {
           echo 'See the course description</span>';
        }
      ?>

    <?php 
      // Location
      if (($locations)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span style="font-weight: 600;">Location:</span>
      <span class="vf-u-text-color--grey">
        <?php $location_list = [];
              foreach( $locations as $location ) { 
                $location_list[] = $location->name; }
                echo implode(', ', $location_list); ?>
      </span>
    </p>
    <?php } ?>

    <?php 
      // Venue
      if (($venue)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Venue:</span>
      <span class="vf-u-text-color--grey">
        <?php echo $venue; ?>
      </span>
    </p>
    <?php } ?>

    <hr class="vf-divider | vf-u-margin__bottom--400">
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span
        style="font-weight: 600;">Registration:</span></p>

    <?php 
      if ($registrationRemark) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><?php echo $registrationRemark; ?></p>
    <?php } ?>

    <p class="vf-text-body vf-text-body--3">Status:
      <?php 
      if (empty($registrationDeadline)) {
        if ($registrationStatus == 'Open') {
          echo '<span class="vf-u-text-color--green">Open</span>';
        }
        else if ($registrationStatus == 'Closed') {
          echo '<span class="vf-u-text-color--red">Closed</span>';
        }
        else if ($registrationStatus == 'Waiting list only') {
          echo '<span class="vf-u-text-color--orange">Waiting list only</span>';
        }
        else {
        echo '<span class="vf-u-text-color--grey">' . $registrationStatus . '</span>';
        }
      }
      else {
        if ($registrationDeadlineFormatted >= $current_date) {
          echo '<span class="vf-u-text-color--green">Open</span>';
        }
        else {
          echo '<span class="vf-u-text-color--red">Closed</span>';
        }
      } ?>
    </p>

    <?php 
      // register button
      if ( !empty($registrationLink)) { 
        if ((($registrationDeadlineFormatted >= $current_date)) || (($registrationStatus == 'Open'))) { ?>
    <div style="display: inline-block;" data-vf-google-analytics-region="registration-training">
      <a href="<?php echo esc_url($registrationLink); ?>" target="_blank"><button
          class="vf-button vf-button--primary vf-button--sm vf-u-margin__bottom--600">Register</button></a>
    </div>
    <?php }} ?>

    <hr class="vf-divider | vf-u-margin__bottom--400">
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span style="font-weight: 600;">Organiser:</span>
    </p>
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100">Organised by: <span
        class="vf-u-text-color--grey | vf-u-margin__right--600 | organiser">
        <?php $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = $org->name; }
          echo implode(', ', $org_list); ?></span> </p>

    <p class="vf-text-body vf-text-body--3">Contact: <?php echo $contact; ?> </p>
    <?php

// alternative dates
if( have_rows('vf-wp-training-alternative') ): ?>
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--100"><span style="font-weight: 600;">Alternative
        dates:</span></p>
    <ul class="vf-list vf-list--default | vf-list--tight">
      <?php
    while( have_rows('vf-wp-training-alternative') ) : the_row();
        $alternativeDate = get_sub_field('vf-wp-training-alternative-date'); ?>
      <li class="vf-list__item "> <span class="vf-u-text-color--grey"><?php  echo $alternativeDate; ?></span></li>
      <?php endwhile;?>
    </ul>
    <?php endif; ?>

    <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
    </div>
  </div>


  <?php get_footer(); ?>
