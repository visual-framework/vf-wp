<?php

get_header();

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">TeachingBASE</h1>
    <p>The EMBL TeachingBASE is a collection of STEM teaching modules designed for teachers and young learners, developed by EMBL science education experts and scientists.</p> 
    <p>The materials are freely available but each module carries a creative commons copyright.</p>
  </div>
</div>

<section class="vf-content">
  <h3>Browse or filter all resources</h3>
<div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
  <div>
    <?php include(locate_template('partials/teachingbase-filter.php', false, false)); ?>
  </div>
  <div class="vf-grid__col--span-3">
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--teachingbase.php', false, false)); 
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

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
