<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Members) return;

$content = $vf_plugin->api_html();
$hash = VF_Cache::hash(
  esc_url_raw($vf_plugin->api_url())
);

if (vf_cache_empty($content)) {
  return;
}

// Add grid layout classes to wrapping element
$content = preg_replace(
  '#^(\s*<[^>]*?vf-content-hub-html)#',
  '$1 vf-grid vf-grid__col-2',
  $content
);

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]*?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

echo $content;

?>
