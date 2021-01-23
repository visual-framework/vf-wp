<?php
get_header();

?>

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      Educators' Training</h2>
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
    <h3>Any upcoming ELLS LearningLABs will be advertised via this website and listed below.
    </h3>
    <p>The 1-3 day courses provide a mix of scientific seminars, hands-on activities, lab experiments and new teaching
      resources, covering cutting-edge research and technologies and illustrate how these topics can be transferred to
      the classroom. As of 2017, ELLS also offers virtual teacher training opportunities.
    </p>
  </div>
</div>
<section
  class="vf-content | vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--800 vf-u-padding__top--100 vf-u-margin__bottom--100 | training-container">
  <h3> Upcoming LearningLabs</h3>
  <div class="vf-grid vf-grid__col-3">
    <?php
 $current_year = date('Y') . '0101';
 $args = array(
  'post_type' => 'llabs-event',
  'order' => 'ASC',
  'meta_query' => array( array(
    'key' => 'labs_start_date',
    'value' => $current_year,
    'compare' => '>=',
    'type' => 'date'
) )
);
// The Query
$the_query = new WP_Query( $args );
 $year = date('Y');

// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        include(locate_template('partials/vf-card--article-llabs.php', false, false));
    }

} else {
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>

  </div>
</section>
<section class="vf-content">
  <h3>Browse or filter all LearningLabs</h3>

  <div class="vf-grid vf-grid__col-4 | vf-content">
    <div class="vf-grid__col--span-3">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-llab.php', false, false)); 
            if (($wp_query->current_post + 1) < ($wp_query->post_count)) {
              echo '<hr class="vf-divider">';
           }

          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
      <div class="vf-grid"> <?php vf_pagination();?></div>
    </div>
    <div class="vf-content">
      <?php include(locate_template('partials/llabs-filter.php', false, false)); ?>
    </div>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
