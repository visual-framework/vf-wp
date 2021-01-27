<?php

$title = esc_html(get_the_title());
$file_type = get_field('file_type');


?>
<article class="vf-summary">
  <span class="vf-summary__date | vf-u-margin__bottom--100">
    <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </span>
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__meta"> File type:
  <?php echo ($file_type); ?>
  </p>

</article>
<!--/vf-summary-->