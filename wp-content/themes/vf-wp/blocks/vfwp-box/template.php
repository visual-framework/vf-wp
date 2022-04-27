<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$text = get_field('text');
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-box__text">', $text);
$link = get_field('link');

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

$theme_none = get_field('theme_none');
if (empty($theme_none)) {
  $theme_none = 'very-easy';
}
if (is_array($theme_none)) {
  $theme_none = $theme_none[0];
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
  vf_html_empty($heading)
  && vf_html_empty($text)
) {
  $admin_banner(__('Please enter content for this box.', 'vfwp'));
  return;
}

$classes = "vf-box";

if ($link) {
  $classes .= " vf-box--is-link";
}

if ($style === 'none') {
  $classes .= " vf-box--{$theme_none}";
}
  
if ($style === 'normal') {
$classes .= " vf-box--{$style}";
if ($style !== 'easy') {
  $classes .= " vf-box-theme--{$theme_normal}";
} }

if ($style === 'easy') {
$classes .= " vf-box--{$style}";
if ($style !== 'normal') {
  $classes .= " vf-box-theme--{$theme_easy}";
} }?>

<div class="<?php echo esc_attr($classes); ?> | vf-u-margin__bottom--400">
  <?php if (! empty($heading)) { ?>
    <h3 class="vf-box__heading">
      <?php } if ($link) { ?>
        <a class="vf-box__link" href="<?php echo esc_url($link['url']); ?>">
          <?php } ?>
          <?php echo esc_html($heading); ?>
        <?php if ($link) { ?>
        </a>
        <?php }  ?>
      <?php if (! empty($heading)) { ?>
    </h3> 
      <?php } ?>
  <?php echo ($text); ?>
</div>