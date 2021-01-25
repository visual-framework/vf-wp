<?php

get_header();

?>
<section class="vf-grid vf-grid__col-4">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">
    </h1>
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }

          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
    <div class="vf-grid"> <?php vf_pagination();?></div>

  </div>

  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>

  </div>
</section>
<?php

get_footer();

?>
