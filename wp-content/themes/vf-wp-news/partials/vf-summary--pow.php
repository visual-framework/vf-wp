<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));

?>

<article class="vf-summary vf-summary--article">
  <div class="post-image">
    <a style="display: grid;" href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail(); ?>
    </a>
  </div>

  <div class="article-summary">
    <h2 class="vf-summary__title">
      <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
    </h2>
    <span class="vf-summary__meta">
      <a class="vf-summary__author vf-summary__link" href="<?php echo $author_url; ?>"><?php the_author(); ?></a>
      <time class="vf-summary__date" title="<?php the_time('c'); ?>"
        datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    </span>
    <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>

  </div>
</article>