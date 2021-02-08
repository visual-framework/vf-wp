<?php

$title = esc_html(get_the_title());
$start_date = get_field('il_start_date');

?>

<article class="vf-card vf-card--primary">

  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>

  <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__title">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?></a>
    </h3>
    <p class="vf-card__text">
    <?php echo get_the_excerpt(); ?></p>
    <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php echo ($start_date); ?></time>
  </div>
</article>
