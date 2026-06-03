<?php

get_header();

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-4 | vf-content">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">TeachingBASE</h1>
    <p>The EMBL TeachingBASE is a collection of STEM teaching modules designed for teachers and young learners, developed by EMBL science education experts and scientists.</p> 
    <p>The materials are freely available but each module carries a creative commons copyright.</p>
  </div>
</div>

<section class="vf-content">
  <h3>Browse or filter all resources</h3>
  <?php
  $age_group_options = vf_wp_ells_term_options('age-group');

  $teachingbase_filters = array(
    array(
      'key' => 'age-group',
      'label' => 'Age group',
      'all_label' => 'All age groups',
      'taxonomy' => 'age-group',
      'acf_field' => 'tb_age_group',
      'options' => array_merge(
        $age_group_options,
        array(array(
          'value' => 'all',
          'label' => 'All',
        ))
      ),
      'all_selected_value' => 'all',
      'all_selected_option_values' => wp_list_pluck($age_group_options, 'value'),
    ),
    array(
      'key' => 'topic-area',
      'label' => 'Topic area',
      'all_label' => 'All topic areas',
      'taxonomy' => 'topic-area',
      'acf_field' => 'tb_topic_area',
      'options' => vf_wp_ells_term_options('topic-area'),
    ),
    array(
      'key' => 'year',
      'label' => 'Publication year',
      'all_label' => 'All years',
      'control' => 'select',
      'options' => vf_wp_ells_archive_year_options('teachingbase'),
    ),
  );

  $language_options = vf_wp_ells_archive_language_options();
  $wpml_available = vf_wp_ells_is_wpml_active();
  if ( $wpml_available ) {
    $teachingbase_filters[] = array(
      'key' => 'lang',
      'label' => 'Language',
      'all_label' => 'All languages',
      'control' => 'select',
      'options' => $language_options,
    );
  }

  $archive_browser = array(
    'id' => 'teachingbase-browser',
    'post_type' => 'teachingbase',
    'summary_template' => 'partials/vf-summary--teachingbase.php',
    'items_per_page' => 10,
    'result_label' => 'resources',
    'query_args' => array(
      'post_parent' => 0,
      'suppress_filters' => $wpml_available,
    ),
    'filters' => $teachingbase_filters,
  );
  include(locate_template('partials/archive-browser.php', false, false));
  ?>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
