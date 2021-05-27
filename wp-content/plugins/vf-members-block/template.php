<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$limit = get_field('vf_members_limit', $acf_id);
$variation = get_field('vf_members_variation', $acf_id);
$leader = get_field('vf_members_leader', $acf_id);
$team = get_field('vf_members_team', $acf_id);
$term_id = get_field('vf_members_term', $acf_id);
$keyword = get_field('vf_members_keyword', $acf_id);

$limit = intval($limit);
$limit = $limit < 1 || $limit > 50 ? 50 : $limit;


if (empty($variation)) {
  $variation = 'inline';
}

if (is_array($variation)) {
  $variation = $variation[0];
}

$leader = boolval($leader);

$keyword = trim($keyword);

// Hide selected fields
$hide_fields = get_field('hide_fields');
$values = array();
if( $hide_fields ): 
 foreach( $hide_fields as $hide_field ): 
  array_push($values, $hide_field);
 endforeach;
endif; 
$hide_fields = implode(',', $values);

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'person',
  'pattern'                   => "vf-profile-{$variation}",
  'limit'                     => $limit,
  'sort-field-value[field_person_name_last]' => 'DSC',
  'filter-ref-entity[field_person_positions][title]' => "",
  'hide[team,' . $hide_fields . ']' => 1
);

if ($leader !== true) {
  $vars['filter-field-value-not[field_person_positions.entity.field_position_membership]'] = 'leader';
}

$key = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.title]';

// Search based on EMBL Taxonomy (default or specified)
if (function_exists('embl_taxonomy_get_term')) {
  // Use specified term
  $term = null;
  if ($team === 'taxonomy' && is_numeric($term_id)) {
    $term = embl_taxonomy_get_term(intval($term_id));
  }
  // Use default
  if ( ! $term instanceof WP_Term) {
    $term_id = get_field('embl_taxonomy_term_what', 'option');
    $term = embl_taxonomy_get_term($term_id);
  }
  if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
    $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
  }
}

// Search by keyword
if ($team === 'keyword' && ! empty($keyword)) {
  $vars[$key] = $keyword;
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

// Add grid layout classes to wrapping element
$columns = get_field('vf_members_columns', $acf_id);
if (empty($columns)) {
  $columns = 2;
};

$content = preg_replace(
  '#^(\s*<[^>]+?vf-content-hub-html)#',
  '$1 vf-grid vf-grid__col-' . $columns,
  $content
);


// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

echo $content;

?>
