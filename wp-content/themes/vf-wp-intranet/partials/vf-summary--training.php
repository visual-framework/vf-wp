<?php
$now = new DateTime();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$location = get_the_terms( $post->ID , 'event-location' );
$post_id = get_the_ID();
$start_date = get_field('vf-wp-training-start_date',$post_id);
$start_time = get_field('vf-wp-training-start_time',$post_id);
$start = DateTime::createFromFormat('Ymd', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf-wp-training-end_date',$post_id);
$end_time = get_field('vf-wp-training-end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('Ymd', $end_date);
$registrationStatus = get_field('vf-wp-training-registration-status',$post_id); 
$registrationDeadline = get_field('vf-wp-training-registration-deadline',$post_id); 
$deadlineDate = new DateTime($registrationDeadline);
$registrationDeadlineFormatted = $deadlineDate->format('Ymd');
$venue = get_field('vf-wp-training-venue',$post_id);
$fee = get_field('vf-wp-training-fee',$post_id);
$category = get_field('vf-wp-training-category',$post_id);
$additionalInfo = get_field('vf-wp-training-info',$post_id); 



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

  <h3 class="vf-summary__title | vf-u-margin__bottom--100 vf-u-margin__top--200 | search-data">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
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
          echo '<span class="vf-u-text-color--green | status-open">Open</span>';
        }
        else if ($registrationStatus == 'Closed') {
          echo '<span class="vf-u-text-color--red | status-closed">Closed</span>';
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
          echo '<span class="vf-u-text-color--green | status-open">Open</span>';
        }
        else {
          echo '<span class="vf-u-text-color--red | status-closed">Closed</span>';
        }
      }
      ?>
    </p>
  </div>
  <!-- for filtering -->
  <div class="vf-u-display-none">
    <span class="year year-<?php echo $start->format('Y');?>"><?php echo $start->format('Y'); ?></span>
    <span class="fee-<?php echo $fee; ?>"><?php echo $fee; ?></span>
    <span class="category-<?php echo $category; ?>"><?php echo $category; ?></span>
  </div>
  <?php if ($forthcomingLoop->current_post +1 < $forthcomingLoop->post_count) {
    echo '<hr class="vf-divider">';
} ?>
</article>
