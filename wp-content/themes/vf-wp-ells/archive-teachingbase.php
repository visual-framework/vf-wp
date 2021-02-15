<?php
get_header();

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      ELLS TeachingBase </h2>
    <p class="vf-hero__text">Morbi dictum purus sit amet purus blandit, quis facilisis mauris semper</p>
  </div>
</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<div class="vf-grid vf-grid__col-4 | vf-content | vf-u-margin__bottom--800">
  <div class="vf-grid__col--span-3">
    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus, diam quis pharetra euismod, leo tortor
      eleifend eros, sit amet suscipit erat lectus eu mi. </h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus, diam quis pharetra euismod, leo tortor
      eleifend eros, sit amet suscipit erat lectus eu mi.</p>
  </div>
</div>

<section class="vf-content">
  <h3>Browse or filter all LearningLabs</h3>
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
