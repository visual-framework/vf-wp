<?php

$id = false;

// Use plugin defaults for ACF blocks if not customized
if (class_exists('VF_Plugin')) {
  global $vf_plugin;
  if (isset($block) && get_field('defaults')) {
    $name = $block['name'];
    $name = str_replace('-', '_', $name);
    $name = str_replace('acf/', '', $name);
    $vf_plugin = VF_Plugin::get_plugin($name);
  }
  if ($vf_plugin) {
    $id = $vf_plugin->post()->ID;
  }
}

$limit = get_field('vf_members_limit', $id);
$order = get_field('vf_members_order', $id);
$varition = get_field('vf_members_variation', $id);
$leader = get_field('vf_members_leader', $id);
$team = get_field('vf_members_team', $id);
$term_id = get_field('vf_members_term', $id);
$keyword = get_field('vf_members_keyword', $id);

$limit = intval($limit);
$limit = $limit < 1 || $limit > 50 ? 50 : $limit;

if (empty($order)) {
  $order = 'DESC';
}

if (empty($varition)) {
  $varition = 'l';
}

if (is_array($varition)) {
  $varition = $varition[0];
}

$leader = boolval($leader);

$keyword = trim($keyword);

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'person',
  'pattern'                   => "vf-summary-profile-{$varition}",
  'limit'                     => $limit,
  'sort-field-value[changed]' => $order,
  'filter-fields-empty'       => 'field_person_visible_internally',
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
$content = preg_replace(
  '#^(\s*<[^>]+?vf-content-hub-html)#',
  '$1 vf-grid vf-grid__col-2',
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
