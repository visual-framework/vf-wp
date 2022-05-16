<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');

?>

<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date vf-u-text-color--grey" title="<?php the_time('c'); ?>"datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></span>

  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <p class="vf-summary__text">
  <span class="vf-summary__category">
  <?php echo get_the_category_list(', '); ?>
    </span>
  <?php echo get_the_excerpt(); ?></p>
  <div class="vf-u-display-none">
    <p class="year a<?php the_time('Y'); ?>"><?php the_time('Y'); ?></p>
    <p class="category">
    <?php 
    foreach((get_the_category()) as $category){
        echo $category->slug;
        }
    ?>
    </p>
  </div>
  <?php
  if ( function_exists('icl_object_id') ) {
  wpml_post_languages_in_loop(); } ?>

</article>
