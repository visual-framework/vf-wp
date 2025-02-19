<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_start_date',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$end_date = get_field('vf_event_end_date',$post_id);
$end = DateTime::createFromFormat('j M Y', $end_date);
$location = get_field('vf_event_pb_location',$post_id);
$venue = get_field('vf_event_venue',$post_id);
$registration = get_field('vf_event_trec_registration',$post_id); 
$registrationInfo = get_field('vf_event_info_text',$post_id); 
$additionalInfo = get_field('vf_event_summary',$post_id); 
$startTime = get_field('vf_event_start_time',$post_id); 
$endTime = get_field('vf_event_end_time',$post_id); 

?>

<article class="vf-summary vf-summary--event | vf-u-margin__bottom--400" data-jplist-item>
  <p class="vf-summary__date">
    <?php if ( ! empty($start_date)) { ?>
    <?php       // Event dates
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
           } 
        else {
          echo $start->format('j F Y'); 
        } } 
        if ($startTime) {
          echo ', ' . $startTime;
        } 
        if ($endTime) {
          echo ' - '. $endTime . ' CET';
        }
    ?>


  </p>
  <h3 class="vf-summary__title name | vf-u-margin__bottom--200">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>

  <div class="vf-u-margin__bottom--400">
    <div class="vf-content | wysiwyg | vf-u-margin__bottom--200">
      <?php echo $additionalInfo; ?>
    </div>
    <?php if ( ! empty($location)) { ?>
    <p class="vf-summary__location location location-<?php echo strtolower($location); ?>"><?php echo $location; ?>
    </p>
    <?php } ?>
  </div>
  <?php if ($forthcomingLoop->current_post +1 < $forthcomingLoop->post_count) {
    echo '  <hr class="vf-divider">';

} ?>
</article>
