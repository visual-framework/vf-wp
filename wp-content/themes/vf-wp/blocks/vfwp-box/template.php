<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$text = get_field('text');
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-box__text">', $text);
$image = get_field('image');


$style = get_field('style');
if (empty($style)) {
  $style = 'easy';
}
if (is_array($style)) {
  $style = $style[0];
}

$theme_easy = get_field('theme_easy');
if (empty($theme_easy)) {
  $theme_easy = 'none';
}
if (is_array($theme_easy)) {
  $theme_easy = $theme_easy[0];
}

$theme_normal = get_field('theme_normal');
if (empty($theme_normal)) {
  $theme_normal = 'none';
}
if (is_array($theme_normal)) {
  $theme_normal = $theme_normal[0];
}


// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
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
  vf_html_empty($text)
) {
  $admin_banner(__('Please enter content for this box.', 'vfwp'));
  return;
}

$classes = "vf-box";

if ($style === 'normal') {
$classes .= " vf-box--{$style}";
if ($style !== 'easy' && $theme_normal !== 'none') {
  $classes .= " vf-box-theme--{$theme_normal}";
} }

if ($style === 'easy') {
$classes .= " vf-box--{$style}";
if ($style !== 'normal' && $theme_easy !== 'none') {
  $classes .= " vf-box-theme--{$theme_easy}";
} }?>


<div class="<?php echo esc_attr($classes); ?>">
  <?php if (! empty($heading)) { ?>
    <h3 class="vf-box__heading">
      <?php echo esc_html($heading); ?>
    </h3>
    <?php } ?>
      <?php echo ($text); ?>
  </div>

