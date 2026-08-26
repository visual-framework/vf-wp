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
$vfwp_pdf_display_path = $vfwp_pdf_url;

if ($vfwp_pdf_url !== '') {
	$vfwp_pdf_url_path = wp_parse_url($vfwp_pdf_url, PHP_URL_PATH);

	if (is_string($vfwp_pdf_url_path) && $vfwp_pdf_url_path !== '') {
		$vfwp_uploads_position = strpos($vfwp_pdf_url_path, '/uploads/');
		$vfwp_pdf_display_path = false !== $vfwp_uploads_position
			? substr($vfwp_pdf_url_path, $vfwp_uploads_position)
			: $vfwp_pdf_url_path;
	}
}
?>
<article class="vf-summary">

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php echo esc_url($vfwp_pdf_url); ?>" class="vf-summary__link"><?php echo wp_kses($vfwp_pdf_title, array('mark' => array())); ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <b><?php esc_html_e('Document', 'vfwp'); ?></b>
    <?php if ($vfwp_pdf_snippet !== '') : ?>
      <?php echo ' | ' . wp_kses($vfwp_pdf_snippet, array('mark' => array())); ?>
    <?php endif; ?>
  </p>

  <?php if ($vfwp_pdf_url !== '') : ?>
  <div class="vf-summary__meta">
    <p class="vf-summary__author | vf-u-margin__bottom--0">
      <?php echo esc_html($vfwp_pdf_display_path); ?>
      <span class="vf-badge vf-badge--tertiary vf-search-result__pdf-pill"><?php esc_html_e('PDF', 'vfwp'); ?></span>
    </p>
  </div>
  <?php endif; ?>

</article>


<!--/vf-summary-->
