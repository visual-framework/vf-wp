<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

?>

<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>

  <?php /*

if( get_field( 'youtube_url' ) ) {
    $videoid = get_field( 'youtube_url' );
    echo '<div class="embed-container embed-padding-latest"><iframe src="' . $videoid . '" frameborder="0" allowfullscreen></iframe></div>';
} 

else if ( get_field( 'mp4_url' ) ) { 
  $mp4url = get_field( 'mp4_url' );
  echo '<div><video muted="muted" class="vf-card__image" controls><source src="' . $mp4url . '" type="video/mp4"></video></div>';
}

else { ?>
  <?php
}
*/?>

  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <span class="vf-summary__meta | vf-u-margin__bottom--100 ">
    <p class="vf-summary__meta vf-u-margin__bottom--100 vf-u-margin__top--100">By&nbsp;<a
        class="vf-summary__author vf-summary__link"
        href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> in
      <a><?php echo get_the_category_list(','); ?></a></p>
  </span>
</article>
