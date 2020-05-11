<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$heading = get_field('vf_publications_heading', $acf_id);
$heading = trim($heading);

$limit = get_field('vf_publications_limit', $acf_id);
$limit = intval($limit);
$limit = $limit < 1 || $limit > 100 ? 100 : $limit;

$order = get_field('vf_publications_order', $acf_id);
if (empty($order)) {
  $order = 'DESC';
}

$vars = array(
  'source'                    => 'contenthub',
  'limit'                     => $limit,
  'sort-field-value[changed]' => $order,
);

// Query the contentHub API, samples:
//   - Group: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-team-publications&title=Web%20Development
//   - ORCID: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&orcid=0000-0001-5454-2815
//   - Name: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&title=Maria-Jesus

$searchType = get_field('vf_publications_type', $acf_id);
if (empty($searchType)) {
  $searchType = 'team';
}

$searchQuery = get_field('vf_publications_query',$acf_id);
$searchQuery = trim($searchQuery);
$searchQuery = str_replace(' ', '-', $searchQuery);

if ($searchType == 'team' || $searchType == 'person_name') {
  $queryKey = 'title';
} else if ($searchType == 'orcid') {
  $queryKey = 'orcid';
}

if ($searchType == 'team' ) {
  $vars['pattern'] = 'embl-team-publications';
} else if ($searchType == 'orcid' || $searchType == 'person_name') {
  $vars['pattern'] = 'embl-person-publications';
}

// if a specific query has been given, use it
if ( ! empty($searchQuery)) {
  $vars[$queryKey] = $searchQuery;
// otherwise if query for team use the name of the site, if set
} else if ($searchType == 'team' && function_exists('embl_taxonomy_get_term')) {
  $term_id = get_field('embl_taxonomy_term_what', 'option');
  $term = embl_taxonomy_get_term($term_id);
  if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
    $vars['title'] = $term->meta[EMBL_Taxonomy::META_NAME];
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
<?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
  </div>
<?php } ?>
<?php echo $content; ?>
