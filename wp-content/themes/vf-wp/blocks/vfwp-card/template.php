<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$title = get_field('title');
$link = get_field('link');
$text = get_field('text', false, false);
$text = str_replace('<a', '<a class="vf-card_link"', $text);
$subheading = get_field('subheading');
$image = get_field('image');
$image_url = get_field('image_url');

$is_link = ! empty($link);

$style = get_field('style');
if (empty($style)) {
  $style = 'bordered';
}
if ($style === 'default') {
  $style = 'bordered';
}

if (is_array($style)) {
  $style = $style[0];
}

if ( ! is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'large', false, array(
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

$classes = "vf-card vf-card--brand";

if ($style) {
  $classes .= " vf-card--{$style}";
}

?>

<article class="<?php echo esc_attr($classes); ?> vf-u-margin__bottom--800">
<?php
if (!empty($image_url)) { ?>
<img src="<?php echo esc_url($image_url); ?>" class="vf-card__image" alt="" loading="lazy" itemprop="image">
<?php 
}
elseif (!empty($image)) {
  echo ($image);
}
else {
  echo '';
}
?>
  <div class="vf-card__content | vf-stack vf-stack--400">
  <?php if (!empty($title)) { ?>
    <h3 class="vf-card__heading">
      <?php if ($link) { ?>
        <a class="vf-card__link" href="<?php echo esc_url($link['url']); ?>">
      <?php } 
       echo esc_html($title); 
       if ($link) { ?>
       <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
       </svg>
        </a>
      <?php }  ?>
    </h3>
    <?php } ?>
    <?php if (!empty($subheading)) { ?>
    <p class="vf-card__subheading"><?php echo esc_html($subheading); ?></p>
    <?php } ?>
    <?php if (!empty($text)) { ?>
    <p class="vf-card__text"><?php echo ($text); ?></p>
    <?php } ?>
  </div>
</article>

