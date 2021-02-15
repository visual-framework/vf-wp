<?php
$title = esc_html(get_the_title());
?>

<article class="vf-summary 
<?php if (has_post_thumbnail($post->ID)){
  echo 'vf-summary--news';
}
else {
  echo '';
} ?>
">

  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
      'loading'  => 'lazy',
      'itemprop' => 'image' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <div class="vf-summary__meta"><a href="<?php the_permalink(); ?>"
      class="vf-summary__author vf-summary__link"><?php the_permalink(); ?></a></div>
</article>
