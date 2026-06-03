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
    <link rel="import" href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=190885&pattern=node-body&source=contenthub" data-target="self" data-embl-js-content-hub-loader>
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
  <?php
  $archive_browser = array(
    'id' => 'teacher-training-browser',
    'post_type' => 'learninglab',
    'summary_template' => 'partials/vf-summary-llab.php',
    'items_per_page' => 10,
    'result_label' => 'courses',
    'filters' => array(
      array(
        'key' => 'type',
        'aliases' => array('llabs-type'),
        'label' => 'Type',
        'all_label' => 'All types',
        'taxonomy' => 'llabs-type',
        'acf_field' => 'labs_type',
        'options' => vf_wp_ells_term_options('llabs-type'),
      ),
      array(
        'key' => 'format',
        'aliases' => array('llabs-format'),
        'label' => 'Format',
        'all_label' => 'All formats',
        'taxonomy' => 'llabs-format',
        'acf_field' => 'labs_format',
        'options' => vf_wp_ells_term_options('llabs-format'),
      ),
      array(
        'key' => 'location',
        'aliases' => array('llabs-location'),
        'label' => 'Location',
        'all_label' => 'All locations',
        'taxonomy' => 'llabs-location',
        'acf_field' => 'labs_location',
        'options' => vf_wp_ells_term_options('llabs-location'),
      ),
    ),
  );
  include(locate_template('partials/archive-browser.php', false, false));
  ?>
  <p><a href="https://www.embl.org/ells/past-ells-learninglabs/">See the list of past Teacher training courses</a></p>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
