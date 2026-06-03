<?php
get_header();

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">School ambassadors</h1>
    <p>The biography-style profiles of our EMBL School Ambassadors provide insights into the diverse biographies and career paths in life sciences research. 
</p>
    <p>The Ambassadors are staff and students of EMBL who share their experience of working as scientists with schools across the world by meeting them in person and discussing their research. Their profiles offer a great tool to show students the opportunities and careers available in the life sciences; they illustrate the diverse backgrounds and different personal motivations of becoming a scientist,  and highlight the international and interdisciplinary nature of life sciences research.

</p>
</div>
</div>

<section class="vf-content">
  <h3>Browse or filter all our Ambassadors</h3>
  <?php
  $archive_browser = array(
    'id' => 'ambassadors-browser',
    'post_type' => 'ambassadors',
    'summary_template' => 'partials/vf-summary--ambassadors.php',
    'items_per_page' => 10,
    'result_label' => 'ambassadors',
    'filters' => array(
      array(
        'key' => 'country',
        'label' => 'Country',
        'all_label' => 'All countries',
        'taxonomy' => 'country',
        'acf_field' => 'amb_country',
        'options' => vf_wp_ells_term_options('country'),
      ),
    ),
  );
  include(locate_template('partials/archive-browser.php', false, false));
  ?>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
