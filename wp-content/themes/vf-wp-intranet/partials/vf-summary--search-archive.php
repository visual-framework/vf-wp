<?php
/**
 * Search summary for a synthetic archive index row.
 */

if (!defined('ABSPATH')) {
	exit;
}

global $vfwp_indexed_search_result;

if (!is_array($vfwp_indexed_search_result)) {
	return;
}

$title = !empty($vfwp_indexed_search_result['title_highlighted'])
	? (string) $vfwp_indexed_search_result['title_highlighted']
	: esc_html((string) $vfwp_indexed_search_result['title']);
$snippet = !empty($vfwp_indexed_search_result['snippet_highlighted'])
	? (string) $vfwp_indexed_search_result['snippet_highlighted']
	: '';
$url = !empty($vfwp_indexed_search_result['url']) ? (string) $vfwp_indexed_search_result['url'] : '';
?>
<article class="vf-summary<?php echo !empty($vfwp_indexed_search_result['fallback']['active']) ? ' vf-summary--has-search-fallback' : ''; ?>">
  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php echo esc_url($url); ?>" class="vf-summary__link"><?php echo wp_kses($title, array('mark' => array())); ?></a>
    &nbsp;<span class="vf-badge vf-badge--tertiary vf-search-result__type-pill"><?php esc_html_e('Training', 'vfwp'); ?></span>
  </h2>
  <?php if ($snippet !== '') : ?>
    <p class="vf-summary__meta" style="margin-bottom: 8px;">
      <?php echo wp_kses($snippet, array('mark' => array())); ?>
    </p>
  <?php endif; ?>
  <?php
  if (class_exists('VFWP_Intranet_Search_Frontend') && !empty($vfwp_indexed_search_result['fallback'])) {
    echo VFWP_Intranet_Search_Frontend::render_fallback_notice($vfwp_indexed_search_result['fallback']);
  }
  ?>
</article>
