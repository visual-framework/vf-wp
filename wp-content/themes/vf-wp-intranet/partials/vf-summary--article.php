<?php

$title = esc_html(get_the_title());
$topic_terms = get_field('topic');


?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date">
    <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </span>
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
</article>


<!--/vf-summary-->