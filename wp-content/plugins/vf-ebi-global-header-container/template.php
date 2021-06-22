<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

if ($is_preview) {
?>
<div class="vf-banner vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo esc_html_e('This is a placeholder for the EBI Global Header container.', 'vfwp'); ?>
    </p>
  </div>
</div>
<?php
  return;
}
?>
<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$id = get_field('vf_ebi_global_header_node_id', $acf_id);
$id = intval($id);
$id = $id ? $id : 6682;

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

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

// If EBI 1.x JS runs, disable the legacy cookie banner (2.0 will deliver the banner instead)
// https://stable.visual-framework.dev/components/ebi-header-footer/
$content .= '<div data-protection-message-disable="true" class="vf-u-display-none"></div>';

echo $content;

?>
