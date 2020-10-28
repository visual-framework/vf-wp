<?php

$is_minimal = false;

global $vf_plugin;
if ($vf_plugin) {
  $is_minimal = $vf_plugin->is_minimal();
}

$acf_id = isset($acf_id) ? $acf_id : false;

$heading_html = function() use ($acf_id) {
  $heading = get_field('vf_group_header_heading', $acf_id);
  $heading = esc_html($heading);
  $heading = trim($heading);
  $heading = "<h1 class=\"vf-lede\">{$heading}</h1>";
  if ( ! vf_html_empty($heading) || ! class_exists('VF_Cache')) {
    return $heading;
  }
  // if heading is empty, use the contenthub description
  $term_id = get_field('embl_taxonomy_term_what', 'option');
  $uuid = embl_taxonomy_get_uuid($term_id);
  if ( ! $uuid) {
    return $heading;
  }
  $url = VF_Cache::get_api_url();
  $url .= '/pattern.html';
  $url = add_query_arg(array(
    'filter-uuid'         => $uuid,
    'filter-content-type' => 'profiles',
    'pattern'             => 'node-teaser',
    'source'              => 'contenthub',
  ), $url);
  $heading = VF_Cache::fetch($url);
  $heading = preg_replace(
    '#<p>#',
    '<h1 class="vf-lede">',
    $heading
  );
  $heading = preg_replace(
    '#</p>#',
    ' <a class="vf-link" href="'.home_url('/about/').'">' . __('Read more', 'vfwp') . '</a>.</h1>',
    $heading
  );
  return $heading;
};

$heading = $heading_html();

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'person',
  'pattern'                   => 'vf-profile-inline',
  'hide[orcid,mobile,phones]' => 1,
  'limit'                     => 1,
  'sort-field-value[changed]' => 'DESC',
  'filter-field-value[field_person_positions.entity.field_position_membership]' => 'leader'
);

if ($is_minimal) {
  $vars['pattern'] = 'vf-profile-inline';
}

if (function_exists('embl_taxonomy_get_term')) {
  $term_id = get_field('embl_taxonomy_term_what', 'option');
  $term = embl_taxonomy_get_term($term_id);
  $key = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.title]';
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

?>
<?php if ( ! $is_minimal) { ?>
<div class="vf-grid vf-grid__col-3 | vf-u-margin__bottom--800">
  <?php if ( ! vf_cache_empty($heading)) { ?>
    <div class="vf-grid__col--span-2">
      <?php echo $heading; ?>
    </div>
  <?php } ?>
    <div>
<?php } // is_minimal ?>

    <?php
    if ( ! vf_cache_empty($content)) {
      $content = preg_replace(
        '#^\s*<([^>]+?)>#',
        '<$1 data-cache="' . esc_attr($hash) . '">',
        $content
      );
      echo $content;
    }
    ?>

<?php if ( ! $is_minimal) { ?>
  </div>
</div>
<?php } // is_minimal ?>
