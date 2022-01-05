<?php
/*
Template Name: Archives
*/
get_header();

the_post();
?>

<div class="vf-u-margin__bottom--200 | vf-u-padding__top--400">
  <div>
    <h3 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600" style="font-weight: 400;">
      <?php wp_title(''); ?></h3>
  </div>
  <div class="vf-news-container vf-news-container--featured">
    <div class="vf-news-container__content vf-grid vf-grid__col-4">
      <?php 
      $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $args = array(
        'posts_per_page' => 12,
        'paged' => $page,
        'meta_query'    => array(
          'relation' => 'OR',
          array(
              'key'       => 'field_target_display',
              'value'     => 'embl-ebi',
              'compare' => 'NOT LIKE'
          ),
          array(
            'key' => 'field_target_display',
            'compare' => 'NOT EXISTS'
          )
      ));
    query_posts($args);?>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
      <?php endwhile; endif; ?>
  </div>
  <div class="vf-grid" style="margin: 4%"> <?php vf_pagination();
      ?>
  </div>
</div>
</div>

<?php include(locate_template('partials/archive-container.php', false, false)); ?>

<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
