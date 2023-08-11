<?php

$relatedCourses = get_field('vf_related_courses');


// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
}; ?>

<?php

// alternative dates

if( $relatedCourses ): ?>
<div class="vf-u-margin__top--400">
  <?php foreach( $relatedCourses as $relatedCourse ): 
      $now = new DateTime();
      $current_date = $now->format('Y-m-d');
      $organiser = get_the_terms( $relatedCourse->ID , 'training-organiser' );
      $location = get_the_terms( $relatedCourse->ID , 'event-location' );
      $start_date = get_field('vf-wp-training-start_date',$relatedCourse->ID);
      $start_time = get_field('vf-wp-training-start_time',$relatedCourse->ID);
      $start = DateTime::createFromFormat('j M Y', $start_date);
      $start_time_format = DateTime::createFromFormat('H:i', $start_time);
      $end_date = get_field('vf-wp-training-end_date',$relatedCourse->ID);
      $end_time = get_field('vf-wp-training-end_time',$relatedCourse->ID);
      $end_time_format = DateTime::createFromFormat('H:i', $end_time);
      $end = DateTime::createFromFormat('j M Y', $end_date);
      $end_date_format = DateTime::createFromFormat('j M Y', $end_date);
      $registrationStatus = get_field('vf-wp-training-registration-status',$relatedCourse->ID); 
      $registrationDeadline = get_field('vf-wp-training-registration-deadline',$relatedCourse->ID); 
      $deadlineDate = new DateTime($registrationDeadline);
      $registrationDeadlineFormatted = $deadlineDate->format('Y-m-d');
      $venue = get_field('vf-wp-training-venue',$relatedCourse->ID);
      $additionalInfo = get_field('vf-wp-training-info',$relatedCourse->ID); 
      $relatedCoursePermalink = get_permalink( $relatedCourse->ID );
      $relatedCourseTitle = get_the_title($relatedCourse->ID );
    
      
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
  <article class="vf-summary vf-summary--event | vf-u-margin__bottom--400">
    <?php if ( ! empty($start_date)) { ?>
    <p class="vf-summary__date">
      <?php       // Event dates
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
           } 
        else {
          echo $start->format('j F Y'); 
        }  ?>
    </p>
    <?php } ?>

    <h3 class="vf-summary__title | vf-u-margin__bottom--100 vf-u-margin__top--200 | search-data">
      <a href="<?php echo $relatedCoursePermalink; ?>" class="vf-summary__link"><?php echo $relatedCourseTitle ?></a>
    </h3>
    <div>
      <div class="vf-content | wysiwyg-training-info | search-data">
        <?php echo $additionalInfo; ?>
      </div>
      <p class="vf-summary__meta | vf-u-margin__bottom--600" id="trainingMeta">
        <?php if (($organiser)) { ?>
        <span class="vf-u-text-color--grey | vf-u-margin__right--600 | organiser | organiser-<?php $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = strtolower(str_replace(' ', '-', $org->name)); }
          echo implode(', ', $org_list); ?>">
          <?php $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = strtoupper($org->name); }
          echo implode(', ', $org_list); ?></span>
        <?php } ?>
        <?php if (($location)) { ?>
        <span>Location:</span>&nbsp;
        <span class="vf-u-text-color--grey | vf-u-margin__right--600 | location | 
      <?php $loc_list = [];
        foreach( $location as $loc ) { 
          $locClass = 'location-' . strtolower($loc->name);;
          $loc_list[] = $locClass; }
          echo implode(' ', $loc_list); ?>">
          <?php // if (!empty($venue)) {
       // echo esc_html($venue) . ', '; } ?>
          <?php $loc_list = [];
        foreach( $location as $loc ) { 
          $loc_list[] = $loc->name; }
          echo implode(', ', $loc_list); ?></span>
        <?php } ?>
        <span>Registration:</span>&nbsp;
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
      }
      ?>
      </p>
    </div>
  </article>

  <?php endforeach; ?>
  <?php endif; ?>

</div>
