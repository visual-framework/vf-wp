<?php
/**
 * Search result summary for indexed PDF attachments.
 */

if (empty($vfwp_indexed_search_result) || !is_array($vfwp_indexed_search_result)) {
	global $vfwp_indexed_search_result;
}

if (empty($vfwp_indexed_search_result) || !is_array($vfwp_indexed_search_result)) {
	return;
}

$vfwp_pdf_title = !empty($vfwp_indexed_search_result['title_highlighted'])
	? $vfwp_indexed_search_result['title_highlighted']
	: esc_html($vfwp_indexed_search_result['title']);
$vfwp_pdf_snippet = !empty($vfwp_indexed_search_result['snippet_highlighted'])
	? $vfwp_indexed_search_result['snippet_highlighted']
	: '';
$vfwp_pdf_url = !empty($vfwp_indexed_search_result['url']) ? $vfwp_indexed_search_result['url'] : '';
?>
<article class="vf-summary">

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php echo esc_url($vfwp_pdf_url); ?>" class="vf-summary__link"><?php echo wp_kses($vfwp_pdf_title, array('mark' => array())); ?></a>
    &nbsp;<span class="vf-badge vf-badge--tertiary vf-search-result__type-pill"><?php esc_html_e('PDF', 'vfwp'); ?></span>
  </h2>
  <?php if ($vfwp_pdf_snippet !== '') : ?>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php echo wp_kses($vfwp_pdf_snippet, array('mark' => array())); ?>
  </p>
  <?php endif; ?>

</article>


<!--/vf-summary-->
