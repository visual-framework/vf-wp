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
$topic_terms = get_field('vf_event_internal_events_topic',$post_id);
$customDateSorting = DateTime::createFromFormat('Ymd', $start_time);

?>
<article class="vf-summary vf-summary--event newsItem articleBottomBorder vf-u-padding__bottom--800" data-jplist-item>
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date" data-eventtime="<?php echo $start->format('Ymd'); ?>">
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
    
    <?php if (is_page_template('template-past-events.php')): ?>
  <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;">&nbsp;|&nbsp;
    <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:"
      target="_blank" rel="nofollow">Add to calendar</a>
  </span>
<?php endif; ?>

  </p>
  <?php } ?>
  <h3 class="vf-summary__title | vf-u-margin__bottom--200">
    <?php if ($has_page == 1) { ?>
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php } ?>
      <?php the_title(); ?>
      <?php if ($has_page == 1) { ?>
    </a>
    <?php } ?>
  </h3>
  <?php if (is_post_type_archive('events')) { ?>
  <p class="vf-summary__text" style="margin-bottom: 1rem !important;"><?php echo get_the_excerpt(); ?></p>
  <?php } ?>
  <?php if (($topic_terms)) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue ' . esc_attr( $term->slug ) . '"style="color: #373a36; text-decoration: none;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode('', $topics_list); }?>
    </span>
  </p>
  <?php } ?>
  <p class="vf-summary__meta vf-u-margin__bottom--600">Location:
    <?php 
if (($locations)) { ?>
    <span class="vf-u-text-color--grey | vf-u-margin__right--600
  location vf-u-margin__top--0 
  <?php foreach($locations as $location) { echo 'location-' . $location->slug . ' '; } ?>">
      <?php $location_list = [];
    foreach( $locations as $location ) { 
      $location_list[] = $location->name; }
      echo implode(', ', $location_list); ?>
    </span>&nbsp;&nbsp;
    <?php }?>
  </p>
</article>