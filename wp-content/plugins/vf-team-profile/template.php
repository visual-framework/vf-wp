<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Get search field and value (default `full_name`)
$field = get_field('vf_team_profile_search_by');
$value = get_field('vf_team_profile_team_name');


// Show default preview instruction
if (empty($value)) {
  if ($is_preview) {
?>
<div class="vf-banner vf-banner--alert vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('Enter a search query to find a team.', 'vfwp'); ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
  }
  return;
}


// Setup base API URL
$url = VF_Cache::get_api_url();
$url .= '/pattern.html';
$url = add_query_arg(array(
  'source'              => 'contenthub',
  'pattern'             => "vf-team-regular-inline",
  'filter-field-value[field_site_type_conceptual_field]' => 'embl_group_page',
  'limit'               => 1,
), $url);

$key_name = 'filter-field-contains[field_display_title]';
$key_bdrid = 'filter-field-contains[field_edr_mapping.entity.field_foreignid]';

// Search by uuid
if ($field == 'name') {
  $url = add_query_arg(array(
    $key_name => $value
  ), $url);}

// Search by bdrid
if ($field == 'bdrid') {
  $url = add_query_arg(array(
  $key_bdrid = $value
), $url);}

// Request HTML from the Content Hub API
$content = VF_Cache::fetch($url);
$hash = VF_Cache::hash(
  esc_url_raw($url)
);

// Escape and show error if nothing found
if (
  vf_cache_empty($content)
  || ( vf_html_empty($content) && $is_preview )
) {
  if ($is_preview) {
?>
<div class="vf-banner vf-banner--alert vf-banner--danger">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php esc_html_e('No team found.', 'vfwp'); ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
  }
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

