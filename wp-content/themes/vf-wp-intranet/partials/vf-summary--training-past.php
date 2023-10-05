<?php
$now = new DateTime();
$post_id = get_the_ID();
$current_date = $now->format('Ymd');
$organiser = get_the_terms( $post->ID , 'training-organiser' );
$location = get_the_terms( $post->ID , 'event-location' );
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
$feeSlug = strtolower(str_replace(' ', '_', $fee));
$format = get_field('vf-wp-training-format',$post_id);
$category = get_field('vf-wp-training-category',$post_id);
$categorySlug = strtolower(str_replace(' ', '_', $category));
$additionalInfo = get_field('vf-wp-training-info',$post_id, false, false); 
$audience = get_field('vf-wp-training-audience',$post_id); 
$keywords = get_field('keyword',$post_id); 



?>
<article class="vf-summary vf-summary--event | vf-u-margin__bottom--400" data-jplist-item>
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date" data-eventtime="<?php echo $start->format('Ymd'); ?>">
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

  <h2 class="vf-summary__title | vf-u-margin__bottom--200 vf-u-margin__top--200 | search-data">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
  </h2>
  <div>
    <div class="vf-content | wysiwyg-training-info | search-data">
      <?php
      $limitStr = 175;
      if (strlen($additionalInfo) > $limitStr) {
        $limitedString = substr($additionalInfo, 0, $limitStr);
        $lastSpace = strrpos($limitedString, ' ');
        if ($lastSpace !== false) {
            $limitedString = substr($limitedString, 0, $lastSpace);
        }
        echo '<p>' . $limitedString . ' ...</p>';
      } else {
        echo '<p>' . $additionalInfo . '</p>';
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
      <?php if (($audience)) { ?>
      <span>Audience:</span>&nbsp;
      <span class="vf-u-text-color--grey"><?php echo $audience; ?></span>
      <?php } ?>
    </p>
  </div>
  <div>
    <?php if (!empty($category)) { ?>
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--800"><span
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
  <?php if ($forthcomingLoop->current_post +1 < $forthcomingLoop->post_count) {
    echo '<hr class="vf-divider">';
} ?>
</article>
