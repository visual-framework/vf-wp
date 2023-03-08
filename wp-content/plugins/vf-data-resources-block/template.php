<?php
$acf_id = isset($acf_id) ? $acf_id : false;

$limit = get_field('vf_data_resources_limit', $acf_id);
$order = get_field('vf_data_resources_order', $acf_id);
$heading = get_field('vf_data_resources_heading', $acf_id);

$limit = intval($limit);
$limit = $limit < 1 || $limit > 30 ? 30 : $limit;

if (empty($order)) {
  $order = 'DESC';
}

$heading = trim($heading);

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'data_resource',
  'pattern'                   => 'vf-summary-image',
  'limit'                     => $limit,
  'sort-field-value[changed]' => $order,
);

if (function_exists('embl_taxonomy_get_term')) {
  $term_id = get_field('embl_taxonomy_term_what', 'option');
  $term = embl_taxonomy_get_term($term_id);
  $key = 'filter-field-contains[field_resource_teams.entity.title]';
  if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
    $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
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
<div class="embl-grid embl-grid--has-centered-content | vf-u-margin__top--800">
  <?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
  <h2 class="vf-section-header__heading">
    <a class="vf-section-header__heading vf-section-header__heading--is-link" href="https://www.ebi.ac.uk/services"
      id="section-link"> <?php echo esc_html($heading); ?> <svg aria-hidden="true"
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a></h2></div>
  <?php }
else {
  echo '<div></div>';
} ?>
  <div>
    <?php echo $content; ?>
  </div>
</div>
