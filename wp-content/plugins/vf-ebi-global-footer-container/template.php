<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$id = get_field('vf_ebi_global_footer_node_id', $acf_id);
$id = intval($id);
$id = $id ? $id : 6683;

$vars = array(
  'source'              => 'contenthub',
  'pattern'             => 'node-body',
  'filter-content-type' => 'article',
  'filter-id'           => $id,
);

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

// Replace wrapping element class with grid class
$content = preg_replace(
  '#^((\s*<[^>]+?)vf-content-hub-html)#',
  '$2 vf-u-grid--reset',
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
