<?php

$title = esc_html(get_the_title());
$file_type = get_field('file_type');
$file = get_field('upload_file');
$locations = get_field('embl_location');
$update = get_field('latest_update');
$annexes = get_field('annexes');
$customDateSorting = get_the_time('Ymd');

?>
<article class="vf-summary" data-jplist-item>
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

  <?php if (($locations)) { ?>
  <p class="vf-text-body vf-text-body--5
 location vf-u-margin__top--0">
    <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          $allEMBLsites = array("Barcelona", "EMBL-EBI", "Grenoble", "Hamburg", "Heidelberg", "Rome");
          if ($allEMBLsites == $location_list) {
            echo 'All EMBL sites';
          }
          else {
          echo implode(', ', $location_list); 
          } ?>
  </p>
  <?php } ?>

  <?php if ($update) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200"><strong>Updated: </strong>
    <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></p>
  <?php }   ?>

  <!--/only for sorting-->
  <p class="update" style="display: none;">
  <?php 
  if (empty($update)) {
    $update = the_time(get_option('date_format'));
  }
  else {
  echo esc_html($update); }?> </p>

  <p class="vf-summary__meta">
    <a href="<?php echo $file['url']; ?>"
      class="vf-button vf-button--primary vf-button--outline vf-button--sm">Download</a>
  </p>
</article>
<!--/vf-summary-->
