<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
?>

<style>
  .category-link-color a.vf-link:visited,
  .category-link-color a.vf-link:hover,
  .category-link-color a.vf-link {
    color: <?php the_field('link_color');
    ?>
  }
</style>

<article class="vf-summary vf-summary--article vf-u-margin__bottom--xl"
  style="background-color: <?php the_field('color'); ?>; min-height: 100%; display: block;">
  <!-- <div class="post-image"> -->
  <a href="<?php the_permalink(); ?>">
    <?php the_post_thumbnail(); ?>
  </a>
  <!-- <div class="overlay">
			  <p class="vf-u-margin--0">
			  </p>
		</div> -->
  </div>
  <div class="article-summary" style="background-color: <?php the_field('color'); ?>; padding: 0 24px 16px 24px;">
    <h2 class="vf-summary__title | vf-u-margin__top--sm">
      <a href="<?php the_permalink(); ?>" class="vf-link vf-link--secondary | vf-text vf-text-heading--2"
        style="color: white;"><?php echo $title; ?></a>
    </h2>
    <p class="vf-summary__text vf-u-text-color--ui--white"><?php echo get_the_excerpt(); ?></p>
    <time class="vf-summary__date vf-u-text-color--ui--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    <span class="vf-summary__meta | vf-u-margin__bottom--xs vf-u-margin__top--xs">
      <p class="vf-summary__meta vf-u-text-color--ui--white">By&nbsp;</p>
      <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"
        style="color: <?php the_field('link_color'); ?>"><?php the_author(); ?></a>
    </span>
    <span class="vf-summary__meta | category-link-color">
      <a class="vf-summary__meta"
        style="color: <?php the_field('link_color'); ?>"><?php echo get_the_category_list(','); ?></a>

    </span>
  </div>
</article>
