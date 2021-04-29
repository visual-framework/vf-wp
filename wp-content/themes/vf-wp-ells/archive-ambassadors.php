<?php
get_header();

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">School ambassadors</h1>
    <p>The EMBL School Ambassadors are EMBL scientists who have chosen careers in the interdisciplinary life sciences. They are visiting schools in Europe and share their experiences of working as a scientist. The EMBL School Ambassadors give an idea of the type of opportunities and careers that are out there, talk about exciting developments in life science research and how they contribute to the world we live in.
The visits of the EMBL School Ambassadors are arranged on a case-by-case basis and are depending on availability of time and funding.
Internal page for EMBL School Ambassadors: please log in here.  </p>
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
