<?php

get_header();

?>

<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
      <h2 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600" style="font-weight: 400;">
      Posts by: <?php the_author() ?></h2>
    <?php
  while (have_posts()) {
    the_post();
    ?>
    <article class="vf-summary">
     <span class="vf-summary__meta vf-u-margin__bottom--100">
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time> 
      in <?php echo get_the_category_list(','); ?></a>
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
  vf_pagination();
  ?>
    <div class="vf-grid" style="margin: 4%">
      <?php vf_pagination(); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
