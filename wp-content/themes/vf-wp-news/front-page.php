<?php

// Use blog index template if no page is set for homepage
if (get_option('show_on_front') !== 'page') {
  get_template_part('index');
  exit;
}

get_template_part('partials/header');

the_post();

// Make sure blocks are not being wrapped in paragraphs
$priority = has_filter('the_content', 'wpautop');
if ($priority) {
  remove_filter('the_content', 'wpautop', $priority);
}

// Add filter to wrap blocks in VF grid containers if needed
add_filter('render_block', 'home_render_block', 11, 2);

function home_render_block($html, $block) {
  if ( ! $block['blockName']) {
    return $html;
  }
  $has_grid = array(
    'acf/vf-latest-posts',
    'acf/vf-group-header',
    'vf/latest-posts',
    'vf/group-header',
  );
  if ( ! in_array($block['blockName'], $has_grid)) {
    ob_start();
?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <?php echo $html; ?>
    </main>
  </div>
</section>
<?php
    // $html = "\n" . '<section class="vf-inlay"><div class="vf-grid">' . $html . '</div></section>' . "\n";
    $html = ob_get_contents();
    ob_end_clean();
  }
  $html .= '<!-- vf:divider -->';
  return $html;
}

// Capture the rendered post content
ob_start();
the_content();
$html = ob_get_contents();
ob_end_clean();

// Remove empty content and add VF divider patterns
$blocks = explode('<!-- vf:divider -->', $html);
foreach ($blocks as $i => $html) {
  if (empty(trim(strip_tags($html)))) {
    continue;
  }
  if ($i !== 0) {
    $html = preg_replace(
      '#(<main[^>]*?vf-inlay__content--main[^>]*?>)#',
      '<hr class="vf-divider">$1',
      $html, 1
    );
  }
  echo $html;
}

// Remove filter just in case
remove_filter('render_block', 'home_render_block', 11, 2);

get_template_part('partials/footer');

?>
