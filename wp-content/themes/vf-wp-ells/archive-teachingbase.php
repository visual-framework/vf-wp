<?php
get_header();

?>

<section class="vf-hero vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image-size: auto 28.5rem">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <h2 class="vf-hero__heading">
    ELLS</h2>
    <p class="vf-hero__subheading">European Learning Laboratory for the Life Sciences</p>
    <p class="vf-hero__text">Our inspiring educational experiences share the scientific discoveries of EMBL with young learners aged 10-19 years and teachers in Europe and beyond.</p>
  </div>
</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">TeachingBASE</h1>
    <p>ELLS TeachingBASE is a collection of molecular biology teaching modules designed for teachers and students, developed by ELLS staff members and EMBL scientists.</p> 
    <p>The materials are freely available but each module carries a creative commons copyright.</p>
  </div>
</div>

<section class="vf-content">
  <h3>Browse or filter all TeachingBASEs</h3>
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
