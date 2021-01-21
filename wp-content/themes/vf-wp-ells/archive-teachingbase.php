<?php
get_header();

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">

    <h2 class="vf-hero__heading">
      ELLS TeachingBase </h2>

    <p class="vf-hero__text">ELLS TeachingBASE is a collection of molecular biology teaching modules designed for
      teachers and students, developed by ELLS staff members and EMBL scientists. The materials are freely available but
      each module carries a creative commons copyright.</p>

  </div>

</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>
    
    
    <div class="vf-grid vf-grid__col-4 | vf-content | vf-u-margin__bottom--800">
      <div class="vf-grid__col--span-3">
        <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus, diam quis pharetra euismod, leo tortor eleifend eros, sit amet suscipit erat lectus eu mi. </h3>
</div>
      </div>        
        <section class="vf-grid vf-grid__col-4 | vf-content">
          <div class="vf-grid__col--span-3">
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--news.php', false, false)); 
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
    <div class="vf-grid"> <?php vf_pagination();?></div>
  </div>
  <div class="vf-content">
    <?php include(locate_template('partials/teachingbase-filter.php', false, false)); ?>

  </div>
</section>


<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
