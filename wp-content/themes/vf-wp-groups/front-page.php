<?php

// Use blog index template if no page is set for homepage
if (get_option('show_on_front') !== 'page') {
  get_template_part('index');
  exit;
}

get_header();

global $vf_theme;

// Marker appended after each inlay section
$placeholder = "\n<!--[/VF_INLAY]-->\n";

// These blocks require no wrapper; their templates have layout
$has_wrap = array(
  'acf/vf-latest-posts', // deprecated
  'acf/vf-group-header', // deprecated
  'vf/latest-posts',
  'vf/group-header',
);

// Prepend the opening wrapper elements
$open_wrap = function($html = '') {
  return '
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
  ' . $html;
};

// Append the closing wrapper elements
$close_wrap = function($html = '') use ($placeholder) {
  return $html . "
    </main>
  </div>
</section>
{$placeholder}";
};

// Render block callback
$render_block = function($block_html, $block_name)
  use ($has_wrap, $open_wrap, $close_wrap, $placeholder)
{
  // 1) Append placeholder (block has its own template)
  if (in_array($block_name, $has_wrap)) {
    return $block_html . $placeholder;
  }
  // 2) Add wrapper (block is not wrapped)
  if ( ! in_array($block_name, VF_Theme_Content::WRAPPED)) {
    return $open_wrap() . $block_html . $close_wrap();
  }
  // 3) Return unchanged (block is wrapped by filters)
  return $block_html;
};

// Add filters
add_filter(
  'vf/__experimental__/theme/content/open_block_wrap',
  $open_wrap,
  10, 1
);
add_filter(
  'vf/__experimental__/theme/content/close_block_wrap',
  $close_wrap,
  10, 1
);
add_filter(
  'vf/__experimental__/theme/content/render_block',
  $render_block,
  10, 2
);

// Capture rendered content
ob_start();
$vf_theme->the_content();
$html = ob_get_contents();
ob_end_clean();

// Add VF divider patterns and output content
$inlays = explode(trim($placeholder), $html);
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
