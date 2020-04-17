<?php
/*
Template Name: Archives
*/
get_header();

the_post();
?>

<section class="vf-inlay | vf-u-margin__bottom--sm ">
  <div class="vf-inlay__content vf-u-background-color-ui--off-white | vf-u-margin__bottom--sm | vf-u-padding__top--md">
    <main class="vf-inlay__content--full-width | vf-u-margin__bottom--0">
      <div>
        <h3 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--xl" style="font-weight: 400;">
          <?php wp_title(''); ?></h3>
      </div>
      <div class="vf-grid vf-grid__col-2">
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
    </main>
  </div>

  <?php include(locate_template('partials/archive-container.php', false, false)); ?>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_footer(); ?>
