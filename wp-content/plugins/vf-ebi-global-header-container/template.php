<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_EBI_Global_Header) return;

$content = $vf_plugin->api_html();
$hash = VF_Cache::hash(
  esc_url_raw($vf_plugin->api_url())
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
