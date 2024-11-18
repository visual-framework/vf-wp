<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$sub_heading = get_field('sub_heading');
$link = get_field('link');
$link_url = is_array($link) && isset($link['url']) ? $link['url'] : '';
$text = get_field('text');
$anchor = get_field('anchor');

$type = get_field('select_type');

if (empty($type)) {
  $type = 'default';
}
if (is_array($type)) {
  $type = $type[0];
}

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if (! $is_preview) {
    return; }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};

if (
  vf_html_empty($heading)
) {
  $admin_banner(__('Please add a heading.', 'vfwp'));
  return;
}

?>
<div class="vf-section-header | vf-u-margin__bottom--400">
  <h2 class="vf-section-header__heading" 
  <?php 
    if ($anchor) { 
      echo 'id="' . esc_attr($anchor) . '"';
    } ?>
  >
  <?php
    if ($type === 'is_a_link' || $type === 'has_sub-heading_and_link' || $type === 'has_sub-heading_link_text' || $type === 'has_link_text') { 
      // Check if $link_url is a valid string or extract it if it's an array
      if (is_array($link_url) && isset($link_url['url'])) {
        $link_url = $link_url['url']; // Extract URL from array
      } elseif (!is_string($link_url)) {
        $link_url = ''; // Default to an empty string if not valid
      }
      ?>
      <a class="vf-section-header__heading vf-section-header__heading--is-link"
      <?php if ($anchor) { echo 'id="' . esc_attr($anchor) . '"'; } ?>
      href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($heading); ?><svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>
    <?php } 
    else {
      echo esc_html($heading); 
    } ?>
  </h2>
  <?php 

  if ($type === 'has_sub-heading' || $type === 'has_sub-heading_and_link' || $type === 'has_sub-heading_link_text') { ?>
    <p class="vf-section-header__subheading"><?php echo esc_html($sub_heading); ?></p>

  <?php } 
  if ($type === 'has_text' || $type === 'has_sub-heading_link_text' || $type === 'has_link_text') { ?>
    <p class="vf-section-header__text"><?php echo esc_html($text); ?></p>
  <?php } ?>
</div>

