<?php
get_header();

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">School ambassadors</h1>
    <p>The biography-style profiles of our EMBL School Ambassadors provide insights into the diverse biographies and career paths in life sciences research. 
</p>
    <p>The Ambassadors are staff and students of EMBL who share their experience of working as scientists with schools across the world by meeting them in person and discussing their research. Their profiles offer a great tool to show students the opportunities and careers available in the life sciences; they illustrate the diverse backgrounds and different personal motivations of becoming a scientist,  and highlight the international and interdisciplinary nature of life sciences research.

</p>
</div>
</div>

<section class="vf-content">
  <h3>Browse or filter all our Ambassadors</h3>
<div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
  <div>
    <?php include(locate_template('partials/ambassadors-filter.php', false, false)); ?>
  </div>
  <div class="vf-grid__col--span-3">
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--ambassadors.php', false, false)); 
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
