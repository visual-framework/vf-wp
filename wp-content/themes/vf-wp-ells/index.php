<?php
/*
Template Name: Archives
*/
get_header();

the_post();
?>

<section class="vf-grid vf-grid__col-1">
    <div>
      <div>
        <h3 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600" style="font-weight: 400;">
          Latest updates</h3>
      </div>
      <div class="vf-grid vf-grid__col-4">
        <?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page' => 10,
    'paged' => $page,);
query_posts($args);?>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php include(locate_template('partials/vf-card--article.php', false, false)); ?>
        <?php endwhile; endif; ?>
      </div>
      <div class="vf-grid" style="margin: 4%"> <?php vf_pagination();
      ?>
      </div>
      </div>


</section>

<?php get_footer(); ?>
