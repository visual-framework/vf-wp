<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Block is container style
$is_container = (bool) get_field('is_container');

$heading = get_field('heading', false, false);
$subheading = get_field('subheading', false, false);
$lede = get_field('lede');
$lede = str_replace('<p>', '<p class="vf-lede">', $lede);
$text = get_field('intro_text');
$text = wpautop($text);
$badge_link = get_field('badge_link');
$badge_style = get_field('badge_style');

if ($is_container) {
  $text = str_replace('<p>', '<p class="vf-intro__text">', $text);
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
  && vf_html_empty($lede)
  && vf_html_empty($text)
) {
  $admin_banner(__('Please enter content for this intro.', 'vfwp'));
  return;
} ?>

<?php
// Open wrappers for container
if ($is_container) {
?>
<section class="vf-intro">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
<?php } ?>

    <h1 class="vf-intro__heading<?php if ( ! empty($badge_link)) { ?> vf-intro__heading--has-tag<?php } ?>">
      <?php echo ($heading); ?>
      <?php if ( ! empty($badge_link)) { ?>
        <a href="<?php echo esc_url($badge_link['url']); ?>" class="vf-badge vf-badge--<?php echo esc_attr($badge_style); ?> vf-badge--phases">
          <?php echo esc_html($badge_link['title']); ?>
        </a>
      <?php } ?>
    </h1>
    <?php if ($subheading) { ?>
    <h2 class="vf-intro__subheading"><?php echo ($subheading); ?></h2>
    <?php } ?>
    <?php echo ($lede); ?>
    <?php echo ($text); ?>

<?php
// Close wrappers for container
if ($is_container) {
?>
  </div>
</section>
<!--/vf-intro-->
<?php } ?>
