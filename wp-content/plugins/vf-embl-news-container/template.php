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

?>
<div class="vf-u-grid--reset vf-body vf-body__additional-content vf-u-background-color-ui--white">
  <hr class="vf-divider">
  <section class="vf-news-container | embl-grid embl-grid--has-sidebar">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading"><?php the_title(); ?></h2>
    </div>
    <?php echo $content; ?>
    <div class="vf-news-container__sidebar">
      <?php
      if (class_exists('VF_Factoid')) {
        $vf_factoid = VF_Plugin::get_plugin('vf_factoid');
        $fields = get_field('vf_embl_news_factoid', $acf_id);
        if (is_array($fields)) {
          VF_Plugin::render($vf_factoid, $fields, $vf_plugin);
        }
      }
      ?>
    </div>
  </section>
</div>
