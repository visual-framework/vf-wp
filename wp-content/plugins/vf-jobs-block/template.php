<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$heading = get_field('vf_jobs_heading', $acf_id);
$heading = trim($heading);

$site = get_field('vf_jobs_site', $acf_id);

$limit = get_field('vf_jobs_limit', $acf_id);
$limit = intval($limit);
$limit = $limit < 1 || $limit > 50 ? 50 : $limit;

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'jobs',
  'pattern'                   => 'vf-jobs-snippet',
  'limit'                     => $limit,
);

$sort_key = 'sort-field-value[created]';
$sort_order = 'DESC';
$vars[$sort_key] = $sort_order;

$filter_key = 'filter-all-fields';

$keyword = isset($_GET['filter_keyword']) ? vf_search_keyword($_GET['filter_keyword']) : '';

// Prioritize user keyword search if defined
if ( ! empty($keyword)) {
  $vars[$filter_key] = $keyword;
// Otherwise use defaults
} else if (function_exists('embl_taxonomy')) {
  $term = null;
  $filter = get_field('vf_jobs_filter', $acf_id);
  switch ( $filter ) {
    case 'cluster':
      $term = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_what')
      );
      $parent_count = count($term->meta['embl_taxonomy_ids']) - 2;
      if ($parent_count > 0) {
        $term = embl_taxonomy_get_term($term->meta['embl_taxonomy_ids'][$parent_count]);
      }
      $filter_key = 'filter-all-fields';
      break;
    case 'what':
      $term = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_what')
      );
      $filter_key = 'filter-field-contains[field_jobs_group]';
      break;
    case 'where':
      $term = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_where')
      );
      $filter_key = 'filter-field-contains[field_jobs_duty_station]';
      break;
    case 'term':
      $term = get_field('vf_jobs_term', $acf_id);
      $term = intval($term);
      if (is_int($term)) {
        $term = embl_taxonomy_get_term($term);
      }
      break;
  }
  if ($term instanceof WP_Term) {
    $vars[$filter_key] = $term->meta[EMBL_Taxonomy::META_NAME];
  }
}

// Setup base API URL
$url = VF_Cache::get_api_url();
$url .= '/pattern.html';
$url = add_query_arg($vars, $url);

// Request HTML from the Content Hub API
$content = VF_Cache::fetch($url);
$hash = VF_Cache::hash(
  esc_url_raw($url)
);

if (vf_cache_empty($content)) {
  return;
}

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

?>

<?php
  $was_no_result_found = strpos($content,'Unfortunately no content was found for this query');
  // @todo: this method to detecting no results found no longer works due to how caching is handeled,
  //        but maybe that's ok and we should be fine with showing the header + more jobs link
  if ($was_no_result_found < 1) { ?>
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__top--800">
    <?php if ( ! empty($heading)) {
    ?>
  <div class="vf-section-header">
    <a class="vf-section-header__heading vf-section-header__heading--is-link" 
    <?php 
    if ($site == 'embl-ebi') {
      echo 'href="https://www.ebi.ac.uk/careers/jobs"';
    } 
    else {
      echo 'href="https://embl.org/jobs"';
    }
    ?>
        id="section-link"> <?php echo esc_html($heading); ?> <svg aria-hidden="true"
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a></div>
    <?php
    } 
    else {
      echo '<div></div>';
    } 
    ?>
    <div>
  <?php } else {
    echo '<!-- As no content was found for this query, the jobs header has also been hidden. -->';
  }
  echo $content . PHP_EOL;
?>
</div>
</div>
