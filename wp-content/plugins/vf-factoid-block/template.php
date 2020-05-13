<?php

$acf_id = isset($acf_id) ? $acf_id : false;
$limit = get_field('vf_factoid_limit', $acf_id);
$id = get_field('vf_factoid_id', $acf_id);

$limit = intval($limit);
$limit = $limit < 1 || $limit > 5 ? 1 : $limit;

$id = trim($id);

$vars = array(
  'source'              => 'contenthub',
  'filter-content-type' => 'factoid',
  'pattern'             => 'vf-factoid',
  'limit'               => $limit
);

if ( ! empty($id)) {
  $vars['filter-id'] = $id;
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

echo $content;

?>
