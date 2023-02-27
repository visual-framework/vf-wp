<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_start_date',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$end_date = get_field('vf_event_end_date',$post_id);
$end = DateTime::createFromFormat('j M Y', $end_date);
$location = get_field('vf_event_trec_location',$post_id);
$city = get_field('vf_event_trec_city',$post_id); 

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

<article class="vf-summary vf-summary--event" data-jplist-item>
  <p class="vf-summary__date">
    <?php if ( ! empty($start_date)) { ?>
    <?php       // Event dates
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
      ?>
    <?php } 
        else {
          echo $start->format('j F Y'); 
        } 
        
     ?>
  <?php } ?>
  <?php if ( ! empty($city)) { ?>
  <span class="vf-summary__location | city-<?php echo $city; ?>"><?php echo ' | ' . $city. ', '; ?>
  </span><?php } ?>
  <?php if ( ! empty($location)) { ?>
  <span class="vf-summary__location"><?php echo strtoupper($location); ?>
  </span>
  <?php } ?>
  </p>
  <h3 class="vf-summary__title | vf-u-margin__bottom--100 | name ">
      <?php the_title(); ?>
  </h3>
    <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>


</article>
