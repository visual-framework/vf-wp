<?php

// Use blog index template if no page is set for homepage
if (get_option('show_on_front') !== 'page') {
  get_template_part('index');
  exit;
}

get_header();

global $vf_theme;

// Add `vf-divider` to top of wrapper
$render_block = function($block_html, $block_name, $i) {
  if ($i > 0) {
    $block_html = preg_replace(
      '#(<main[^>]*?vf-inlay__content--main[^>]*?>)#',
      '<hr class="vf-divider">$1',
      $block_html, 1
    );
  }
  return $block_html;
};

// Do not wrap any `vf` blocks by default
$is_wrapped = function($is_wrap, $block_name, $blocks, $i) {
  if (preg_match('#^vf/(.+)#', $block_name)) {
    $is_wrap = false;
  }
  return $is_wrap;
};

// Prepend the opening wrapper elements
$open_wrap = function($html, $is_wrap, $block_name) use ($render_block) {
  $has_wrap = $is_wrap;
  if ( ! $has_wrap && class_exists('VF_Gutenberg')) {
    $has_wrap = ! VF_Gutenberg::is_block_standalone($block_name);
  }
  if ($has_wrap) {
    $html = '
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
  ' . $html;
  }
  $html = $render_block($html, '', 1);
  return $html;
};

// Append the closing wrapper elements
$close_wrap = function($html, $is_wrap, $block_name) {
  $has_wrap = $is_wrap;
  if ( ! $has_wrap && class_exists('VF_Gutenberg')) {
    $has_wrap = ! VF_Gutenberg::is_block_standalone($block_name);
  }

  if ($has_wrap) {
    $html .= '
    </main>
  </div>
</section>';
  }
  return $html;
};

// Add filters
add_filter(
  'vf/__experimental__/theme/content/render_block',
  $render_block,
  10, 3
);
add_filter(
  'vf/__experimental__/theme/content/is_block_wrapped',
  $is_wrapped,
  10, 4
);
add_filter(
  'vf/__experimental__/theme/content/open_block_wrap',
  $open_wrap,
  10, 3
);
add_filter(
  'vf/__experimental__/theme/content/close_block_wrap',
  $close_wrap,
  10, 3
);

// Output blocks
$vf_theme->the_content();

get_footer();

?>
