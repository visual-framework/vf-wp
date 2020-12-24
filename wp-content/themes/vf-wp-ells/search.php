
<?php get_header(); 

$total_results = $wp_query->found_posts;

?>

<section class="vf-hero vf-hero--primary vf-hero--block vf-hero--800 | vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/Ells_Masthead_1000x600.png');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      ELLS
    </h2>
    <p class="vf-hero__subheading">European Learning Laboratory for the Life Sciences</p>
  </div>
</section>
<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

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


<?php get_footer(); ?>