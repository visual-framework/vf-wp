<?php

$title = esc_html(get_the_title());
$file_type = get_field('file_type');
$file = get_field('upload_file');
$locations = get_field('embl_location');
$update = get_field('latest_update');
$annexes = get_field('annexes');

?>
<article class="vf-summary" data-jplist-item>
  <span class="vf-summary__date | vf-u-margin__bottom--100">
    <time title="<?php the_time('c'); ?>"
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
        $file = get_sub_field('file');?>
    <a class="vf-link" href="<?php echo $file['url']; ?>"><?php echo esc_html($language) ?></a>&nbsp;
    <?php endwhile; ?>
  </p>
  <?php endif; ?>

  <?php if (($locations)) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    <span>EMBL site:</span>&nbsp;
    <span class="vf-u-text-color--grey | location">
      <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
  </p>
  <?php } ?>

  <?php if ($update) { ?>
  <p class="vf-summary__meta | vf-u-margin__bottom--200"><strong>Updated: </strong>
    <span class="vf-u-text-color--grey"><?php echo esc_html($update); ?></span></p>
  <?php }   ?>

  <p class="vf-summary__meta">
    <a href="<?php echo $file['url']; ?>"
      class="vf-button vf-button--primary vf-button--outline vf-button--sm">Download</a>


  </p>
</article>
<!--/vf-summary-->
