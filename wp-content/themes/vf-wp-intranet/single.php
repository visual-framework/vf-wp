<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$intro = get_field('intro');


get_header();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--800 | vf-u-margin__bottom--0">
  <div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800 | title-container">
    <p class="vf-summary__date | vf-u-margin__bottom--0">
      <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
    <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
    <?php 
    if ($intro) { ?>
    <p class="vf-lede | vf-u-padding__bottom--800">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </p>
    <?php } ?>
    <figure class="vf-figure | vf-u-float__right--md | vf-u-padding__left--600">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image', 'style' => 'width: 300px;')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>

    <?php the_content(); ?>

  </div>
</section>

<?php get_footer(); ?>
