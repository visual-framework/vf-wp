<?php

get_header();

$category_name = single_cat_title("", false);
$categories_list = preg_replace('/<a /', '<a class="vf-list__link"', get_the_category_list( ', ' ));

?>

<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
      <h2 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600">
      <?php echo esc_html($category_name) ?></h2>
    <?php
  while (have_posts()) {
    the_post();
    ?>
    <article class="vf-summary">
     <span class="vf-summary__meta vf-u-margin__bottom--100">
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time> 
      in <?php echo $categories_list; ?>
      by <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
     </span>
      <h2 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php the_title(); ?></a>
      </h2>
      <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
    </article>
    <!--/vf-summary-->    
    <?php  if ( ! $vf_theme->is_last_post()) {
      echo '<hr class="vf-divider">';
    }
  }
  ?>
    <div class="vf-grid" style="margin: 4%">
      <?php vf_pagination(); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
