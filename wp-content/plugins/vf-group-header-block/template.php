<?php

$is_minimal = false;

global $vf_plugin;
if ($vf_plugin) {
  $is_minimal = $vf_plugin->is_minimal();
}

$acf_id = isset($acf_id) ? $acf_id : false;

$bdrid = get_field('field_bdr_id', 'option');

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
  /*
  $heading = preg_replace(
    '#</p>#',
    ' <a class="vf-link" href="'.home_url('/about/').'">' . __('Read more', 'vfwp') . '</a>.</h1>',
    $heading
  );
  */
  return $heading;
};

$heading = $heading_html();
$gtl_limit = get_field('vf_group_header_limit', $acf_id);

$gtl_limit = intval($gtl_limit);
$gtl_limit = $gtl_limit < 1 || $gtl_limit > 5 ? 5 : $gtl_limit;

$vars = array(
  'source'                    => 'contenthub',
  'filter-content-type'       => 'person',
  'pattern'                   => 'vf-profile-inline',
  'limit'                     => $gtl_limit,
  'hide[team,mobile,phones]'  => 1,
  'sort-field-value[changed]' => 'DESC',
  'filter-ref-entity[field_person_positions][title]' => "",
  'filter-field-value[field_person_positions.entity.field_position_membership]' => 'leader'
);

if ($is_minimal) {
  $vars['pattern'] = 'vf-profile-inline';
}

$key = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.title]';
$key_bdrid = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.field_foreignid]';

if (function_exists('embl_taxonomy_get_term')) {
  // Use specified term
  $term = null;
  $term_id = get_field('embl_taxonomy_term_what', 'option');
  if (is_numeric($term_id)) {
    $term = embl_taxonomy_get_term(intval($term_id));
  }
  // Use default
  if ( ! $term instanceof WP_Term) {
    $term_id = get_field('embl_taxonomy_term_what', 'option');
    $term = embl_taxonomy_get_term($term_id);
  }

  if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
    if ($bdrid === 'null' || $bdrid ==='') {
      $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
    }
    else {
      $vars[$key_bdrid] = $bdrid;
    }
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

// Query to override the profile:
// Get search value
$value = get_field('vf_group_header_profile', $acf_id);

$value = trim($value);
// Setup base API URL
$url_profile = VF_Cache::get_api_url();
$url_profile .= '/pattern.html';
$url_profile = add_query_arg(array(
  'source'              => 'contenthub',
  'pattern'             => "vf-profile-inline",
  'filter-content-type' => 'person',
  'limit'               => 1,
  'hide[orcid,mobile,phones,email]' => 1,
  'filter-ref-entity[field_person_positions][title]' => "",
  
), $url_profile);

// Add search field query var
$url_profile = add_query_arg(array(
  'filter-field-contains[field_person_full_name]' => $value
), $url_profile);

// Request HTML from the Content Hub API
$content_profile = VF_Cache::fetch($url_profile);
$hash_profile = VF_Cache::hash(
  esc_url_raw($url_profile)
);


?>
<?php if ( ! $is_minimal) { ?>
<div class="vf-grid vf-grid__col-3 | vf-u-margin__bottom--800">
  <?php if ( ! vf_cache_empty($heading)) { ?>
    <div class="vf-grid__col--span-2">
      <?php echo $heading; ?>
    </div>
  <?php } ?>
    <div >
<?php } // is_minimal ?>

<!-- <style>
  .vf-content-hub-html {
    --vf-stack-margin--custom: unset !important;
  }
</style> -->

    <?php
$content = preg_replace(
  '#^(\s*<[^>]+?vf-content-hub-html)#',
  '$1 vf-stack vf-stack--600',
  $content );



    if (!empty($value)) {
      $content_profile = preg_replace(
        '#^\s*<([^>]+?)>#',
        '<$1 data-cache="' . esc_attr($hash_profile) . '">',
        $content_profile
      );
      echo $content_profile;
    }
    
    else if ( ! vf_cache_empty($content)) {
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
