<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$limit = get_field('vf_embl_news_limit', $acf_id);
$limit = intval($limit);
$limit = $limit < 1 || $limit > 3 ? 3 : $limit;

$vars = array(
  'source'               => 'contenthub',
  'pattern'              => 'vf-news-item-default',
  'filter-content-type'  => 'article',
  'limit'                => $limit,
  'filter-field-value[field_article_type]' => 'article_timely',
  'sort-field-value[created]'              => 'DESC',
);

$vars['filter-fields-exists'] = implode(',', array(
  'field_display_title',
  'field_teaser',
  'field_canonical_location'
));

$filter_key = 'filter-field-contains[field_teaser]';

// Use "Keyword" filter
$keyword = get_field('vf_embl_news_keyword', $acf_id);
$keyword = vf_search_keyword($keyword);
if ( ! empty($keyword)) {
  $vars[$filter_key] = $keyword;
}

// Prioritise "Term" filter
if (function_exists('embl_taxonomy_get_term')) {
  $term_id = get_field('vf_embl_news_term', $acf_id);
  $term = embl_taxonomy_get_term($term_id);
  if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
    $vars[$filter_key] = $term->meta[EMBL_Taxonomy::META_NAME];
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

// Replace wrapping element class with pattern classes

$content = preg_replace(
  '#^((\s*<[^>]+?)vf-content-hub-html)#',
  '$2 vf-news-container__content',
  $content
);

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

$enable_sidebar = get_field('vf_embl_news_enable_sidebar');
$heading = get_field('vf_embl_news_heading');
$link = get_field('vf_embl_news_link');
$additional_text = get_field('vf_embl_news_additional_text');
$embl_grid = 'embl-grid';
if ($enable_sidebar == 1) {
  $embl_grid .= ' embl-grid--has-sidebar';
}
?>
  <section class="<?php echo $embl_grid; ?>">
    <div class="vf-section-header">
      <a class="vf-section-header__heading vf-section-header__heading--is-link" href="
      <?php 
      if (! empty($link)){
        echo esc_url($link); }
        else {
          echo 'https://www.embl.org/news';
        } ?>
      ">

      <?php 
      if (! empty($heading)){
        echo ($heading); }
        else {
          echo 'Latest news';
      } ?>
        <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24"
          height="24" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="" fill-rule="nonzero">
          </path>
        </svg>
      </a>
      <?php if ($additional_text) { ?>
      <p class="vf-section-header__text">
        <?php echo $additional_text; ?>
      </p>
      <?php } ?>
    </div>
    <div>
      <?php echo $content; ?>
    </div>
    
    <?php 
    if ($enable_sidebar == 1) {
    if (is_active_sidebar('vf_wp_embl_news_container')) { ?>
    <div>
      <?php vf_sidebar('vf_wp_embl_news_container'); ?>
    </div>
    <?php }} ?>

  </section>
