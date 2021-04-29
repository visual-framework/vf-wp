<?php
get_header();

?>

<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">Learning LABs
    </h1>
    <p>The 1-3 day courses provide a mix of scientific seminars, hands-on activities, lab experiments and new teaching
      resources, covering cutting-edge research and technologies and illustrate how these topics can be transferred to
      the classroom. As of 2017, ELLS also offers virtual teacher training opportunities.
    </p>
    <p>Any upcoming ELLS LearningLABs will be advertised via this website and listed below.</p>
  </div>
</div>

<section class="vf-card-container | vf-u-background-color-ui--grey--light | vf-u-fullbleed | vf-u-padding__bottom--600 vf-u-padding__top--600 vf-u-margin__bottom--600">
<div class="vf-card-container__inner">  
    <div class="vf-section-header">
     <h2 class="vf-section-header__heading">Upcoming LearningLabs</h2>
    </div>  
    <?php
 $current_year = date('Y') . '0101';
 $args = array(
  'post_type' => 'learninglab',
  'posts_per_page' => 3,
  'orderby' => 'labs_start_date',
  'order' => 'ASC',
  'meta_query' => array( array(
    'key' => 'labs_start_date',
    'value' => $current_year,
    'compare' => '>=',
    'type' => 'numeric'
) )
);
// The Query
$the_query = new WP_Query( $args );
// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        include(locate_template('partials/vf-card-llabs.php', false, false));
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
  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>
      <?php include(locate_template('partials/llabs-filter.php', false, false)); ?>
    </div>
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
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
