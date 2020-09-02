<?php

get_header();

global $vf_theme;

?>


<!--/embl-grid-->
<section class="embl-grid embl-grid--has-centered-content">
 <div class="vf-section-header">
 <h2 class="vf-section-header__heading">
  Latest news 
 </h2>
 </div>  

 <div>
  <?php
  while (have_posts()) {
    the_post();

    $title = esc_html(get_the_title());
    $author_url = get_author_posts_url(get_the_author_meta('ID'));
    
    ?>
    <article class="vf-summary">
     <span class="vf-summary__meta vf-u-margin__bottom--xs">
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time> 
      in <?php echo get_the_category_list(','); ?>
      by <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
     </span>
      <h2 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
      </h2>
      <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
    </article>
    <!--/vf-summary-->    
    <?php if ( ! $vf_theme->is_last_post()) {
      echo '<hr class="vf-divider">';
    }
  }
  vf_pagination();
  ?>
  </div>
  <div></div>
</section>
<!--/vf-intro-->

<?php

get_footer();

?>
