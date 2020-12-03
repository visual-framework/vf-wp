<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

$excerpt = get_the_excerpt();
$excerpt = substr($excerpt, 0, 150);
$excerpt = substr($excerpt, 0, strripos($excerpt, ' '));
$excerpt = "{$excerpt}&hellip;";


?>
<div class="vf-card | vf-u-margin__bottom--400">
  <a style="display: flex;" href="<?php the_permalink(); ?>">
    <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
  </a>
  <div class="vf-card__content">
    <h3 class="vf-card__title">
      <a href="<?php the_permalink(); ?>" class="vf-link"><?php echo $title; ?></a>
    </h3>
    <p class="vf-card__text">
    <?php echo $excerpt; ?>
    </p>
    <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    <span class="vf-summary__meta | vf-u-margin__bottom--100 ">
      <p class="vf-summary__meta vf-u-margin__bottom--100 vf-u-margin__top--100">By&nbsp;<a
          class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></p>
      <p class="vf-summary__meta vf-u-margin__bottom--100"><a
          class="vf-link"><?php echo get_the_category_list(','); ?></a></p>
    </span>
  </div>
</div>