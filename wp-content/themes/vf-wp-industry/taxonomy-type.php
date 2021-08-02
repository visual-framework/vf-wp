<?php

get_header();

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">Industry events</h1>
    <?php echo get_the_term_list( $post->ID, 'type', '', ', ' ); ?>

  </div>
</div>

<section class="vf-content">
  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>
      <?php include(locate_template('partials/filter-year.php', false, false)); ?>
    </div>
    <div class="vf-grid__col--span-3">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-event-list.php', false, false)); 
            if (($wp_query->current_post + 1) < ($wp_query->post_count)) {
              echo '<hr class="vf-divider">';
           }
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } 
       ?>
      <div class="vf-grid"> <?php vf_pagination();?></div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
