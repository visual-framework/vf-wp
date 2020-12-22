<?php
$title = esc_html(get_the_title());
?>

<article class="vf-card">

  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>

  <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__title">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?></a>
    </h3>
    <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </div>
</article>