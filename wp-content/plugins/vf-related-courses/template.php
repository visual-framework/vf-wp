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
$post_id = get_the_ID();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $relatedCourse->ID , 'training-organiser' );
$location = get_the_terms( $relatedCourse->ID , 'event-location' );
$start_date = get_field('vf-wp-training-start_date',$relatedCourse);
$start_time = get_field('vf-wp-training-start_time',$relatedCourse);
$start = DateTime::createFromFormat('Ymd', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf-wp-training-end_date',$relatedCourse);
$end_time = get_field('vf-wp-training-end_time',$relatedCourse);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('Ymd', $end_date);
$registrationStatus = get_field('vf-wp-training-registration-status',$relatedCourse); 
$registrationDeadline = get_field('vf-wp-training-registration-deadline',$relatedCourse); 
$deadlineDate = new DateTime($registrationDeadline);
$registrationDeadlineFormatted = $deadlineDate->format('Ymd');
$venue = get_field('vf-wp-training-venue',$relatedCourse);
$fee = get_field('vf-wp-training-fee',$relatedCourse);
$feeSlug = strtolower(str_replace(' ', '_', $fee));
$format = get_field('vf-wp-training-format',$relatedCourse);
$category = get_field('vf-wp-training-category',$relatedCourse);
$categorySlug = strtolower(str_replace(' ', '_', $category));
$additionalInfo = get_field('vf-wp-training-info',$relatedCourse); 
$audience = get_field('vf-wp-training-audience',$relatedCourse); 
$keywords = get_field('keyword',$relatedCourse); 
$relatedCoursePermalink = get_permalink( $relatedCourse->ID );
$relatedCourseTitle = get_the_title($relatedCourse->ID );



?>
<article class="vf-summary vf-summary--event | vf-u-margin__bottom--400" data-jplist-item>
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date">
    <?php       // Event dates
        if ($end_date) { 
          if ($start->format('d') == $end->format('d')) {
            echo $start->format('j F Y');
          }
          else if ($start->format('m') == $end->format('m')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
          } 
        else {
          echo $start->format('j F Y'); 
        }  
        // if ($start_time) {
        //   echo ', ' . $start_time;
        // } 
        // if ($end_time) {
        //   echo ' - '. $end_time . ' CET';
        // }     ?>
  </p>
  <?php } ?>

  <h3 class="vf-summary__title | vf-u-margin__bottom--200 vf-u-margin__top--200 | search-data">
  <a href="<?php echo $relatedCoursePermalink; ?>" class="vf-summary__link"><?php echo $relatedCourseTitle ?></a>  </h3>
  <div>
    <div class="vf-content | wysiwyg-training-info | search-data">
      <?php
      $limitStr = 170;
      if (strlen($additionalInfo) > $limitStr) {
        $limitedString = substr($additionalInfo, 0, $limitStr - 3) . '...';
        echo '<p>' . $limitedString . '</p>';
      } else {
        echo $additionalInfo;
      }
    ?>
    </div>
    <p class="vf-summary__meta | vf-u-margin__bottom--200" id="trainingMeta">
      <?php if (($organiser)) { 
        /*?>
      <span class="vf-u-text-color--grey | vf-u-margin__right--600 | organiser | organiser-<?php $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = strtolower(str_replace(' ', '-', $org->name)); }
          echo implode(', ', $org_list); ?>">
        <?php $org_list = [];
        foreach( $organiser as $org ) { 
          $org_list[] = strtoupper($org->name); }
          echo implode(', ', $org_list); ?></span>
      <?php */ } ?>
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
      <?php 
      if (empty($registrationDeadline)) { ?>
      <span>Registration:</span>&nbsp;
      <?php
        if ($registrationStatus == 'Open') {
          echo '<span class="vf-u-text-color--grey | status-open  | vf-u-margin__right--600">Open</span>';
        }
        else if ($registrationStatus == 'Closed') {
          echo '<span class="vf-u-text-color--grey | status-closed  | vf-u-margin__right--600">Closed</span>';
        }
        else if ($registrationStatus == 'Waiting list only') {
          echo '<span class="vf-u-text-color--grey | vf-u-margin__right--600">Waiting list only</span>';
        }
        else {
        echo '<span class="vf-u-text-color--grey">' . $registrationStatus . '</span>';
        }
      }
      else {
        echo '<span>Registration:</span>&nbsp;';
        if ($registrationDeadlineFormatted >= $current_date) {
          echo '<span class="vf-u-text-color--grey | status-open  | vf-u-margin__right--600">Open</span>';
        }
        else {
          echo '<span class="vf-u-text-color--grey | status-closed  | vf-u-margin__right--600">Closed</span>';
        }
      }
      ?>
      <?php if (($audience)) { ?>
      <span>Audience:</span>&nbsp;
      <span class="vf-u-text-color--grey"><?php echo $audience; ?></span>
      <?php } ?>
    </p>
  </div>
  <div>
    <?php if (!empty($category)) { ?>
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--0"><span
        class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadge"><?php echo $category; ?></span>
      <?php } ?>
      <?php if (!empty($format)) { ?>
      <span class="customFormat"><?php echo $format; ?></span></p>
  </div>
  <?php } 
      else { echo '</p>'; }?>
  <!-- for filtering -->
  <div class="vf-u-display-none">
    <span class="year year-<?php echo $start->format('Y');?>"><?php echo $start->format('Y'); ?></span>
    <span class="fee-<?php echo $feeSlug; ?>"><?php echo $fee; ?></span>
    <span class="category-<?php echo $categorySlug; ?>"><?php echo $category; ?></span>
    <span class="keywords | search-data"><?php echo $keywords; ?></span>
    <?php
    if ($registrationStatus == 'Waiting list only') { echo '<span class="status-open">Open</span>'; } ?>
  </div>

</article>
<hr class="vf-divider">
  <?php endforeach; ?>
  <?php endif; ?>

</div>
