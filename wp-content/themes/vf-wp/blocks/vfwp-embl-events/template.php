<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Get search values
$limit = get_field('limit');
$type = get_field('type');
$embl_terms = get_field('embl_terms');
$location = get_field('location');
$time_from = get_field('time_from');
$time_to = get_field('time_to');
$ids = get_field('ids');
$sort_by = get_field('sort_by');
$custom_date = get_field('custom_date');

$variation = get_field('design_variant');
$suffix = '';
if ( $variation == 'easy') {
  $suffix = "-easy";
}

// Validate values
$limit = intval($limit);
$limit = $limit < 1 || $limit > 20 ? 3 : $limit;
$ids = explode(',', $ids);
$ids = array_map('trim', $ids);
// Ensure single term is array (for later multi-term support)
if (is_int($embl_terms)) {
  $embl_terms = array($embl_terms);
}

// Setup base API URL
$url = VF_Cache::get_api_url();
$url .= '/pattern.html';
$url = add_query_arg(array(
  'source'                    => 'contenthub',
  'pattern'                   => "vf-summary-event{$suffix}",
  'filter-content-type'       => 'event',
  'sort-field-value[field_event_start_date_time.value]' => $sort_by,
), $url);

// Add event type filter query var
if (! empty($type)) {
  $url = add_query_arg(array(
    'filter-field-value[field_event_type]' => $type
  ), $url);
}
// Add event location filter query var
if (! empty($location)) {
  $url = add_query_arg(array(
    'filter-field-value[field_event_location]' => $location
  ), $url);
}

// Add event 'from' filter query var
if (! empty($time_from)) {
  $url = add_query_arg(array(
    'filter-field-date-after[field_event_start_date_time]' => $time_from
  ), $url);
}
else {
  $url = add_query_arg(array(
    'filter-field-date-after[field_event_start_date_time]' => 'today'
  ), $url);

}

// Add event 'to' filter query var
if ($time_to == 'next week' || $time_to == 'next month' || $time_to == 'next year') {
  $url = add_query_arg(array(
    'filter-field-date-before[field_event_start_date_time]' => $time_to
  ), $url);
}
else if ($time_to == 'custom_date') {
  $url = add_query_arg(array(
    'filter-field-date-before[field_event_start_date_time]' => $custom_date
  ), $url);
}


// Add limit query var
$url = add_query_arg(array(
  'limit' => $limit
), $url);

// Add EMBL Taxonomy filter query var
if (! empty($embl_terms)
  && is_array($embl_terms)
  && function_exists('embl_taxonomy_get_term')
) {
  $key = EMBL_Taxonomy::META_IDS;
  $term = embl_taxonomy_get_term(intval($embl_terms[0]));
  if ($term && array_key_exists($key, $term->meta)) {
    $id = array_pop($term->meta[$key]);
    $url = add_query_arg(array(
      'filter-field-value[field_embl_taxonomy_terms.entity.uuid]' => $id
    ), $url);
  }
}

// Add IDs filter query var
if (! empty($ids)) {
  $url = add_query_arg(array(
    'filter-id' => implode(',', $ids)
  ), $url);
}

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
      <?php esc_html_e('No articles were found.', 'vfwp'); ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
  }
  return;
}

// Add grid layout classes to wrapping element
$columns = get_field('columns', $acf_id);
if (empty($columns)) {
  $columns = 3;
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
