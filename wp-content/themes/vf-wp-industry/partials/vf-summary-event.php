<article class="vf-summary vf-summary--event">
  <h3 class="vf-summary__title | vf-u-margin__bottom--100">
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php the_title(); ?>
    </a>
  </h3>
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date">
    <?php       // Event dates
if ( $decide == '1') {
  echo $start->format('F Y'); 
}
else {
  if ($end_date) { 
    if ($start->format('M') == $end->format('M')) {
      echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
    else {
      echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
        ?>
  <?php } 
  else {
    echo $start->format('j F Y'); 
  } } 
        ?>
  </p>
  <?php } ?>
</article>
