<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$sub_heading = get_field('sub_heading');
$link = get_field('link'); // May be string or array
$text = get_field('text');
$anchor = get_field('anchor');

$type = get_field('select_type');
if (empty($type)) {
  $type = 'default';
}
if (is_array($type)) {
  $type = $type[0];
}

// Safely extract link URL and target
$link_url = '';
$link_target = '_self';

if (!empty($link) && is_array($link)) {
    $link_url = $link['url'] ?? '';
    $link_target = $link['target'] ?? '_self';
} elseif (!empty($link) && is_string($link)) {
    $link_url = $link;
    $link_target = '_self';
}

// Function to output a banner message in Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if (!$is_preview) return;
  ?>
  <div class="vf-banner vf-banner--alert vf-banner--<?php echo esc_attr($modifier); ?>">
    <div class="vf-banner__content">
      <p class="vf-banner__text"><?php echo esc_html($message); ?></p>
    </div>
  </div>
  <!--/vf-banner-->
  <?php
};

if (vf_html_empty($heading)) {
  $admin_banner(__('Please add a heading.', 'vfwp'));
  return;
}

?>

<div class="vf-section-header | vf-u-margin__bottom--400">
  <h2 class="vf-section-header__heading" 
      <?php if ($anchor) echo 'id="' . esc_attr($anchor) . '"'; ?>>
      
    <?php if (in_array($type, ['is_a_link', 'has_sub-heading_and_link', 'has_sub-heading_link_text', 'has_link_text'])): ?>
      <a class="vf-section-header__heading vf-section-header__heading--is-link"
         <?php if ($anchor) echo 'id="' . esc_attr($anchor) . '"'; ?>
         target="<?php echo esc_attr($link_target); ?>"
         href="<?php echo esc_url($link_url); ?>">
        <?php echo esc_html($heading); ?>
        <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg>
      </a>
    <?php else: ?>
      <?php echo esc_html($heading); ?>
    <?php endif; ?>
  </h2>

  <?php if (in_array($type, ['has_sub-heading', 'has_sub-heading_and_link', 'has_sub-heading_link_text']) && !empty($sub_heading)) : ?>
    <p class="vf-section-header__subheading"><?php echo esc_html($sub_heading); ?></p>
  <?php endif; ?>

  <?php if (in_array($type, ['has_text', 'has_sub-heading_link_text', 'has_link_text']) && !empty($text)) : ?>
    <p class="vf-section-header__text"><?php echo esc_html($text); ?></p>
  <?php endif; ?>
</div>
