<?php

$title = esc_html(get_the_title());
$file_type = get_field('file_type');
$file = get_field('upload_file');
$locations = get_field('embl_location');
$update = get_field('latest_update');
$annexes = get_field('annexes');
$customDateSorting = get_the_time('Ymd');

?>
<article class="vf-summary newsItem vf-u-margin__bottom--600" data-jplist-item>
  <span class="vf-summary__date | vf-u-margin__bottom--100" data-eventtime="<?php echo $customDateSorting; ?>">
    <time class="added" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </span>
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>

  <?php if( have_rows('annexes') ): ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    Available in language(s):&nbsp;
    <?php while( have_rows('annexes') ): the_row();
        $language = get_sub_field('language');
        $language_file = get_sub_field('file');?>
    <a class="vf-link" href="<?php echo $language_file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
    <?php endwhile; ?>
  </p>
  <?php endif; ?>
<div>

  <p class="vf-summary__meta vf-u-margin__bottom--400">EMBL site: 
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
<?php } ?>
<?php if ($update) { ?>
<span class="vf-summary__meta | vf-u-margin__bottom--200 vf-u-margin__right--600">Updated: 
  <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></span>&nbsp;&nbsp;
<?php }   ?>
<span>
<?php
if (is_array($file) && isset($file['url'])) {
  echo '<a href="' . esc_url($file['url']) . '" class="vf-badge vf-badge--primary customBadgeDarkBlue">Download</a>';
} else {
  echo '';
} ?>
</span>
</p>
</div>


  <!--/only for sorting-->
  <p class="update" style="display: none;">
  <?php 
  if (empty($update)) {
    $update = the_time(get_option('date_format'));
  }
  else {
    echo esc_html($update); }?> </p>

    <?php if ($mainQuery->current_post +1 < $mainQuery->post_count) {
      echo '<hr class="vf-divider">';
    } ?>
</article>
<!--/vf-summary-->
