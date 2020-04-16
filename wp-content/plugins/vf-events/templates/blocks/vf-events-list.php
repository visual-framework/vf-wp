<?php

$archive = get_field('vf_event_archive');
$count = get_field('vf_event_count');

$is_past = $archive === 'past';

$query = VF_Events::get_events(array(
  'posts_per_page' => $count,
  'is_past'        => $is_past
));

?>
<h3 class="vf-text vf-text-heading--3">
  <?php echo esc_html(VF_Events::get_archive_title($is_past)); ?>
</h3>
<?php
while ($query->have_posts()) {
  $query->the_post();
  $post_id = get_the_ID();
  $start_date = get_field(
    'vf_event_start_date',
    $post_id
  );
?>
<article class="vf-summary vf-summary--event">
  <?php if ( ! empty($start_date)) { ?>
  <p class="vf-summary__date">
    <?php echo esc_html($start_date); ?>
  </p>
  <?php } ?>
  <h3 class="vf-summary__title">
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php the_title(); ?>
    </a>
  </h3>
</article>
<!--/vf-summary-->
<?php
}
wp_reset_postdata();
?>
