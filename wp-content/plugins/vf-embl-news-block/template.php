<?php
/**

Query vars:
&pattern=vf-news-item-default&filter-content-type=article

EMBL.org terms:
&filter-field-value[field_embl_taxonomy_terms.entity.uuid]=b7081c83-c191-4492-99e7-99145c27fa3e

Keyword search:
&filter-all-fields=coronavirus

Specific ID(s):
&filter-id=27410,27376

*/

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Get search values
$limit = get_field('limit');
$variant = get_field('variant');
$type = get_field('type');
$embl_terms = get_field('embl_terms');
$keyword = get_field('keyword');
$ids = get_field('ids');
$tags = get_field('tags');
$display = get_field('display_publication');

if (empty($display)) {
  $display = 'EMBL News';
}
if (empty($variant)) {
  $variant = 'vf-news-item-default';
}

// Validate values
$limit = intval($limit);
$limit = $limit < 1 || $limit > 20 ? 3 : $limit;
$keyword = trim($keyword);
$ids = explode(',', $ids);
$ids = array_map('trim', $ids);
$tags = explode(',', $tags);
$tags = array_map('trim', $tags);
// Ensure single term is array (for later multi-term support)
if (is_int($embl_terms)) {
  $embl_terms = array($embl_terms);
}

// Setup base API URL
$url = VF_Cache::get_api_url();
$url .= '/pattern.html';
$url = add_query_arg(array(
  'source'                    => 'contenthub',
  'pattern'                   => $variant,
  'filter-content-type'       => 'article',
  'filter-field-value[field_display_publication]' => $display,
  'sort-field-value[created]' => 'DESC',
), $url);

// Add limit query var
$url = add_query_arg(array(
  'limit' => $limit
), $url);

// Add EMBL Taxonomy filter query var
if (
  $type === 'taxonomy'
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

// Add keyword filter query var
if (
  $type === 'keyword'
  && ! empty($keyword)
) {
  $url = add_query_arg(array(
    'filter-all-fields' => $keyword
  ), $url);
}

// Add IDs filter query var
if ($type === 'ids') {
  $url = add_query_arg(array(
    'filter-id' => implode(',', $ids)
  ), $url);
}

// (placeholder) Add tags filter query var
// TODO: replace once Content Hub API supports tags
if ($type === 'tags') {
  $url = add_query_arg(array(
    'filter-tag' => implode(',', $tags)
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

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

echo $content;

?>
