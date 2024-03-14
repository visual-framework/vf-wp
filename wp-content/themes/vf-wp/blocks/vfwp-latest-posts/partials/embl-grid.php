<?php
 include(locate_template('blocks/vfwp-latest-posts/partials/loops/loops.php', false, false)); 

$ids = array();
while ($loopPost->have_posts()) : $loopPost->the_post();
$ids[] = get_the_ID();
$post_id = get_the_ID();

?>

<article class="vf-summary vf-summary--news">
  <?php if (get_post_type() === 'events') { 
  $start_date = get_field('vf_event_internal_start_date',$post_id);
  $start_time = get_field('vf_event_internal_start_time',$post_id);
  $start = DateTime::createFromFormat('j M Y', $start_date);
  $start_time_format = DateTime::createFromFormat('H:i', $start_time);
  $end_date = get_field('vf_event_internal_end_date',$post_id);
  $end_time = get_field('vf_event_internal_end_time',$post_id);
  $end_time_format = DateTime::createFromFormat('H:i', $end_time);
  $end = DateTime::createFromFormat('j M Y', $end_date);
  $end_date_format = DateTime::createFromFormat('j M Y', $end_date);
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
      <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:"
        target="_blank" rel="nofollow">Add to calendar</a>
    </span>
  </p>
  <?php } }
  else {
  ?>

  <span class="vf-summary__date" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?>
  </span>
  <?php } ?>
  <?php if ($show_image == 1) { ?>
  <?php 
  if ( has_post_thumbnail() ) {
    the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); 
  }
  else { 
    if ( 'community-blog' == get_post_type() ) { ?>
  <img class="vf-summary__image"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
  <?php } } } ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <?php if ($show_excerpt) { ?>
  <p class="vf-summary__text">
    <?php
     echo get_the_excerpt(); ?>
  </p>
  <?php
   } ?>
  <?php if ($show_categories == 1) { ?>
  <p class="vf-summary__text">
    <span class="vf-summary__category">
      <?php echo get_the_category_list(', '); ?>
    </span></p> <?php } ?>
  <?php if ($show_topics == 1) { ?>
  <p class="vf-summary__text | vf-u-margin__bottom--0">
    <span class="vf-summary__meta">
      <?php if (get_post_type() === 'insites') { 
  $topic_terms = get_the_terms( $post->ID, 'topic' );
  if( $topic_terms ) {
    $topics_list = array(); 
    foreach( $topic_terms as $term ) {
      $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
      echo implode(', ', $topics_list); }?> </span>
    <?php } ?>
    <?php if (get_post_type() === 'community-blog') { 
  $topic_terms = get_the_terms( $post->ID, 'updates-topic' );
  if( $topic_terms ) {
    $topics_list = array(); 
    foreach( $topic_terms as $term ) {
      $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
      echo implode(', ', $topics_list); }?> </span>
    <?php } ?>
    <?php if (get_post_type() === 'events') { 
  $topic_terms = get_the_terms( $post->ID, 'events-topic' );
  if( $topic_terms ) {
    $topics_list = array(); 
    foreach( $topic_terms as $term ) {
      $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
      echo implode(', ', $topics_list); }?> </span>
    <?php } ?>
  </p>
  <?php } 
  ?>
  <?php if ($show_location == 1) { ?>
  <p class="vf-summary__text">
    <?php if (get_post_type() === 'community-blog' || get_post_type() === 'insites') { 
      $locations = get_the_terms( $post->ID, 'embl-location' );
      if (($locations)) { ?>
    <span class="vf-text-body vf-text-body--5
       location vf-u-margin__top--0"><span style="font-weight: 500;">EMBL site:</span>
      <?php $location_list = [];
              foreach( $locations as $location ) { 
                switch ($location->name) {
                  case "Heidelberg":
                    $location->name = 'HD';
                    break;
                  case "Hamburg":
                    $location->name = 'HH';
                    break;
                  case "Rome":
                    $location->name = 'RM';
                    break;
                  case "Grenoble":
                    $location->name = 'GR';
                    break;
                  case "Barcelona":
                    $location->name = 'BCN';
                    break;
                  case "EMBL-EBI":
                    $location->name = 'EBI';
                     break;
                  }
                $location_list[] = $location->name; }
                echo implode(', ', $location_list); ?>
    </span>

    <?php      
     } } 
     if (get_post_type() === 'events') { 
      $event_location_terms = get_the_terms( $post->ID, 'event-location' );
      if( $event_location_terms ) { ?>
    <span class="vf-text-body vf-text-body--5 location vf-u-margin__top--0">
      <?php
        $event_locations_list = array(); 
        foreach( $event_location_terms as $term ) {
          $event_locations_list[] = esc_html( $term->name ); }
          echo implode(', ', $event_locations_list); }?>
    </span>
    <?php } ?>
  </p>
  <?php } ?>

</article>
<!--/vf-summary-->
<?php endwhile;?>
<?php wp_reset_postdata(); ?>
