<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_internal_start_date',$post_id);
$start_time = get_field('vf_event_internal_start_time',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf_event_internal_end_date',$post_id);
$end_time = get_field('vf_event_internal_end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('j M Y', $end_date);
$end_date_format = DateTime::createFromFormat('j M Y', $end_date);
$locations = get_field('vf_event_internal_location',$post_id);
$event_type = get_field('vf_event_internal_event_type'); 
$venue = get_field('vf_event_internal_venue'); 
$has_page = get_field('vf_event_internal_has_page'); 
$customDateSorting = DateTime::createFromFormat('Ymd', $start_time);
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
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date" data-eventtime="<?php echo $customDateSorting; ?>">
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
     &nbsp;&nbsp;
     <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;">
     <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:" target="_blank" rel="nofollow">Add to calendar</a>
    </span>
  </p>
  <?php } ?>
  <h3 class="vf-summary__title | vf-u-margin__bottom--100 | name ">
    <?php if ($has_page == 1) { ?>
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php } ?>
      <?php the_title(); ?>
      <?php if ($has_page == 1) { ?>
    </a>
    <?php } ?>
  </h3>
  <?php if (is_post_type_archive('events')) { ?>
    <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <?php } 
  if (($locations)) { ?>
  <p class="vf-text-body vf-text-body--5
 location vf-u-margin__top--200">
    <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
  </p>  
  <?php } ?>
</article>
