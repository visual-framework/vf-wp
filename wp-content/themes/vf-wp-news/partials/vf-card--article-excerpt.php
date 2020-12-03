<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

?>
<article class="vf-card vf-card--normal" style="background-color: <?php the_field('color'); ?>;">
<?php

if( get_field( 'youtube_url' ) ) {
    $videoid = get_field( 'youtube_url' );
    echo '<div class="embed-container embed-padding-hero vf-card__image"><iframe src="' . $videoid . '" frameborder="0" allowfullscreen></iframe></div>';
} 

else if ( get_field( 'mp4_url' ) ) { 
  $mp4url = get_field( 'mp4_url' );
  echo '<div><video muted="muted" class="vf-card__image" autoplay loop><source src="' . $mp4url . '" type="video/mp4"></video></div>';
}

else { ?>
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
    </a>
    <?php
}
?>
  <div class="vf-card__content">
    <h3 class="vf-card__title" style="background-color: <?php the_field('color'); ?>;">
      <a href="<?php the_permalink(); ?>" class="vf-link"><?php echo $title; ?></a>
    </h3>
    <p class="vf-card__text">
    <?php echo get_the_excerpt(); ?></p>
    <time class="vf-summary__date vf-u-text-color--ui--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    <span class="vf-summary__meta | vf-u-margin__bottom--100 ">
      <p class="vf-summary__meta vf-u-margin__bottom--100 vf-u-margin__top--100">By&nbsp;<a
          class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></p>
      <p class="vf-summary__meta vf-u-margin__bottom--100"><a
          class="vf-link"><?php echo get_the_category_list(','); ?></a></p>
    </span>
  </div>
</article>
