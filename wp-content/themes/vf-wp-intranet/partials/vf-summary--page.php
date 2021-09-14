<?php

$title = esc_html(get_the_title());

?>
<article class="vf-summary">
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <div class="vf-summary__meta"><a href="<?php the_permalink(); ?>"
      class="vf-summary__author vf-summary__link"><?php the_permalink(); ?></a></div>

</article>


<!--/vf-summary-->