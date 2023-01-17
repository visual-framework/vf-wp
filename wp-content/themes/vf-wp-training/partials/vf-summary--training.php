<?php
$category = get_the_terms( $post->ID , 'training-category' );
$location = get_the_terms( $post->ID , 'training-location' );
$post_id = get_the_ID();
$start_date = get_field('vf-wp-training-start_date',$post_id);
$start_time = get_field('vf-wp-training-start_time',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf-wp-training-end_date',$post_id);
$end_time = get_field('vf-wp-training-end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('j M Y', $end_date);
$end_date_format = DateTime::createFromFormat('j M Y', $end_date);


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
     &nbsp;&nbsp;
     <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;">
     <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:" target="_blank" rel="nofollow">Add to calendar</a>
    </span>
  </p>
  <?php } ?>

  <h4 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
  </h4>
  <div>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
  <?php if (($category)) { ?>
    <span>Category:</span>&nbsp;
    <span class="vf-u-text-color--grey | category">
      <?php $cat_list = [];
        foreach( $category as $cat ) { 
          $cat_list[] = $cat->name; }
          echo implode(', ', $cat_list); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
  <?php } ?>
  <?php if (($location)) { ?>
    <span>Location:</span>&nbsp;
    <span class="vf-u-text-color--grey | location">
      <?php $loc_list = [];
        foreach( $location as $loc ) { 
          $loc_list[] = $loc->name; }
          echo implode(', ', $loc_list); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
  <?php } ?>
          </p>
  </div>
</article>
<!--/vf-summary-->
