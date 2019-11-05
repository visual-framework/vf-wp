<?php

// Use blog index template if no page is set for homepage
if (get_option('show_on_front') !== 'page') {
  get_template_part('index');
  exit;
}

get_header();

global $vf_theme;

$has_wrap = array(
  'acf/vf-latest-posts',
  'acf/vf-group-header',
  'vf/latest-posts',
  'vf/group-header',
);

$open_wrap = function($html = '') {
  return '
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
  ' . $html;
};

$close_wrap = function($html = '') {
  return $html . '
    </main>
  </div>
</section>
<!--[/VF_INLAY]-->
  ';
};

$render_block = function($block_html, $block_name)
  use ($has_wrap, $open_wrap, $close_wrap)
{
  // Append placeholder
  $placeholder = "\n<!--[/VF_INLAY]-->\n";
  if (in_array($block_name, $has_wrap)) {
    return $block_html . $placeholder;
  }
  // Add wrapper and placeholder if block isn't going to be wrapped
  if ( ! in_array($block_name, VF_Theme_Content::WRAPPED)) {
    return
        $open_wrap()
      . $block_html
      . $close_wrap()
      . $placeholder;
  }
  return $block_html;
};

add_filter(
  'vf/theme/content/open_block_wrap',
  $open_wrap,
  10, 1
);

add_filter(
  'vf/theme/content/close_block_wrap',
  $close_wrap,
  10, 1
);

add_filter(
  'vf/theme/content/render_block',
  $render_block,
  10, 2
);

// Capture rendered content
ob_start();
$vf_theme->the_content();
$html = ob_get_contents();
ob_end_clean();

// Add VF divider patterns and output content
$inlays = explode('<!--[/VF_INLAY]-->', $html);
foreach ($inlays as $i => $html) {
  if ($i !== 0) {
    $html = preg_replace(
      '#(<main[^>]*?vf-inlay__content--main[^>]*?>)#',
      '<hr class="vf-divider">$1',
      $html, 1
    );
  }
  echo $html;
}

get_footer();

?>
