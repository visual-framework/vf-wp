<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$heading = get_field('vf_jobs_heading', $acf_id);
$heading = trim($heading);

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
  if ($was_no_result_found < 1) {
    if ( ! empty($heading)) {
    ?>
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
    </div>
    <?php
    }
  } else {
    echo '<!-- As no content was found for this query, the jobs header has also been hidden. -->';
  }
  echo $content . PHP_EOL;
  if ($was_no_result_found < 1) {
    echo '<p><a href="vf-link" href="//www.embl.org/jobs">View all EMBL jobs</a></p>' . PHP_EOL;
  }
?>
