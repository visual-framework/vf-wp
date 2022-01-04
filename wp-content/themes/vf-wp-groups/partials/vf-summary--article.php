<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>
<article class="vf-summary vf-summary--news">
  <?php if (has_post_thumbnail()) { ?>
    <span class="vf-summary__meta | vf-u-margin__bottom--200">
      <a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"><?php the_author(); ?></a>
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      </span>
      <?php 
      the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); }
    else { ?>
    <span class="vf-summary__meta | vf-u-margin__bottom--200" style="grid-column: 2/-1;">
      <a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"><?php the_author(); ?></a>
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    </span>
    <?php }
    ?>
    <h2 class="vf-summary__title">
      <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
    </h2>
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
</article>
<!--/vf-summary-->
