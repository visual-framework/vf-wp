<?php
get_header();

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">

    <h2 class="vf-hero__heading">
    ELLS TeachingBase    </h2>

    <p class="vf-hero__text">ELLS TeachingBASE is a collection of molecular biology teaching modules designed for teachers and students, developed by ELLS staff members and EMBL scientists. The materials are freely available but each module carries a creative commons copyright.</p>

  </div>

</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
   <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent cursus lectus vel velit commodo, at accumsan elit lacinia. Nullam tincidunt, nulla ut vehicula euismod, est nibh consectetur velit, sed faucibus diam lectus in ex. Phasellus auctor velit et velit maximus suscipit. Maecenas id egestas nulla.</p>
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
  <div>
        Filters here
  </div>
</section>


    <!--/vf-grid-->

<?php

get_footer();

?>
