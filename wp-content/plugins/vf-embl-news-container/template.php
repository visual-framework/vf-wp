<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_EMBL_News) return;

$content = $vf_plugin->api_html();
$hash = VF_Cache::hash(
  esc_url_raw($vf_plugin->api_url())
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
        $fields = get_field('vf_embl_news_factoid');
        if (is_array($fields)) {
          VF_Plugin::render($vf_factoid, $fields, $vf_plugin);
        }
      }
      ?>
    </div>
  </section>
</div>
