<?php
get_header();

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">

    <h2 class="vf-hero__heading">
    ELLS LearningLABs    </h2>

    <p class="vf-hero__text">ELLS LearningLABs are Continuing Professional Development courses for European secondary science teachers organised by the European Learning Laboratory for the Life Sciences (ELLS).</p>

  </div>

</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>
<div class="embl-grid embl-grid--has-centered-content | vf-content">
  <div></div>
  <div>
    <p>The 1-3 day courses provide a mix of scientific seminars, hands-on activities, lab experiments and new teaching resources, covering cutting-edge research and technologies and illustrate how these topics can be transferred to the classroom. 
      As of 2017, ELLS also offers virtual teacher training opportunities.</p>
    <p>Any upcoming ELLS LearningLABs will be advertised via this website and listed below.</p>
  </div>
  <div></div>
</div>
<section class="vf-card-container | vf-u-background-color-ui--grey--light | vf-u-fullbleed vf-u-margin__bottom--0">
  <div class="vf-card-container__inner">

  <div class="vf-section-header">
  <h2 class="vf-section-header__heading" id="section-text"> Upcoming Learning Labs </h2>
  <p class="vf-section-header__text">Ut congue, sapien ut placerat hendrerit, lectus ex convallis erat, eu volutpat dui quam non lectus.</p>
</div>
<?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-card--article-llabs.php', false, false)); 
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
</div>
</section>


    <!--/vf-grid-->

<?php

get_footer();

?>
