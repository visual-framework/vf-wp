<?php

$title = esc_html(get_the_title());
$start_date = get_field('il_start_date');

?>

<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php echo ($start_date); ?></span>
  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'width: 100%; height: auto; border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?></p>
</article>
