<?php
get_header();

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1 class="vf-text vf-text-heading--1">Teacher training
    </h1>
    <p>Our professional development opportunities for teachers aim to empower educators to share the latest developments in life sciences with their students. Our courses are open to science educators teaching at secondary schools in Europe and beyond and held in English unless stated otherwise. All of our trainings are <strong>free of charge</strong>. 
    </p>
    <p>The ELLS <strong>LearningLABs</strong> are teacher training courses held face-to-face over 2–3 days or virtually across several weeks. All courses provide an update on current scientific research and offer teaching and learning materials that bring real-life science into the classroom. Face-to-face courses include training in laboratory or bioinformatics practices. 
    </p>
    <p>The ELLS <strong>TeachingBASE</strong> workshops are 60-120 minute long teacher training sessions that provide hands-on training on using classroom-ready teaching and learning materials developed by ELLS. All TeachingBASE workshops take place live on Zoom. 
    </p>
  </div>
  <div>
  <?php
 $current_date = date('Ymd');
 $args = array(
  'post_type' => 'learninglab',
  'posts_per_page' => 1,
  'orderby' => 'labs_start_date',
  'order' => 'DSC',
  'meta_query' => array( array(
    'key' => 'labs_start_date',
    'value' => $current_date,
    'compare' => '>=',
    'type' => 'numeric'
) )
);
// The Query
$the_query = new WP_Query( $args );
// The Loop
if ( $the_query->have_posts() ) { ?>
    <h3>Upcoming training</h3>
    <?php
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        include(locate_template('partials/vf-card-llabs-upcoming.php', false, false));
    }
} else {
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();
?>
  </div>
</div>


<section class="vf-content">
  <h3>Browse or filter all courses</h3>
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
      <p><a href="https://www.embl.org/ells/past-ells-learninglabs/">See the list of past LearningLABs</a></p>
    </div>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
