<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$title = get_field('title');
$link = get_field('link');
$text = get_field('text');
$image = get_field('image');

$is_link = ! empty($link);

$style = get_field('style');
if (empty($style)) {
  $style = 'very-easy';
}
if (is_array($style)) {
  $style = $style[0];
}

$theme = get_field('theme');
if (empty($theme)) {
  $theme = 'none';
}
if (is_array($theme)) {
  $theme = $theme[0];
}

if ( ! is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-card__image',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
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
  ! $image
  && vf_html_empty($title)
  && vf_html_empty($text)
) {
  $admin_banner(__('Please enter content for this card.', 'vfwp'));
  return;
}

$classes = "vf-card";


$classes .= " vf-card--{$style}";
if ($style !== 'very-easy' && $theme !== 'none') {
  $classes .= " vf-card-theme--{$theme}";
}

?>


<article class="<?php echo esc_attr($classes); ?> vf-u-margin__bottom--800">
<?php
if ($image) {
  echo $image;
}
?>
  <div class="vf-card__content">
    <h3 class="vf-card__title">
      <?php if ($link) { ?>
        <a class="vf-card__link" href="<?php echo esc_url($link['url']); ?>">
      <?php } 
       echo esc_html($title); 
       if ($link) { ?>
        </a>
      <?php }  ?>
    </h3>
    <p class="vf-card__text">
      <?php echo esc_html($text); ?>
    </p>
  </div>
</article>

