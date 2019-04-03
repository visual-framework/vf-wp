<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>
<article class="vf-summary vf-summary--article">
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <span class="vf-summary__meta">
    <a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"><?php the_author(); ?></a>
    <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
<?php if (comments_open()) { ?>
    <a class="vf-summary__link" href="<?php the_permalink(); ?>#respond"><?php _e('Leave a comment', 'vfwp'); ?></a>
<?php } ?>
  </span>
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
</article>
<!--/vf-summary-->
