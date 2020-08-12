<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$social_url = get_the_permalink();


get_header();

the_post();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--xxl | vf-u-margin__bottom--0">
  <div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--xxl">
    <p class="vf-summary__date"><time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
    <h1><?php the_title(); ?></h1>
    <p class="vf-lede | vf-u-padding__top--md | vf-u-padding__bottom--xxl">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </p>
    <figure class="vf-figure | vf-u-float__right--md | vf-u-padding__left--xl">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image', 'style' => 'width: 300px;')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>

    <?php the_content(); ?>

    <p class="vf-text-body vf-text-body--3 | tags-inline">Tags:
     <?php
     $tags = get_the_tags($post->ID);
     if ($tags) {
     $tagslist = array();
     foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary' . $tag->term_id . '">' . $tag->name . '</a>';
     }
     echo implode(', ', $tagslist);
     } ?>
    </p>
  </div>
</section>

<?php get_footer(); ?>
