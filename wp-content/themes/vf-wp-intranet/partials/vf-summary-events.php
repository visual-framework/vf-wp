<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_internal_start_date',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$end_date = get_field('vf_event_internal_end_date',$post_id);
$end = DateTime::createFromFormat('j M Y', $end_date);
$locations = get_field('vf_event_internal_location',$post_id);
$event_type = get_field('vf_event_internal_event_type'); 
$venue = get_field('vf_event_internal_venue'); 
$has_page = get_field('vf_event_internal_has_page'); 
?>

<article class="vf-summary vf-summary--event" data-jplist-item>
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date">
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
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <?php if ( ! empty($locations)) { ?>
  <p class="vf-summary__location | location">
    <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
  </p>
  <?php } ?>
</article>
