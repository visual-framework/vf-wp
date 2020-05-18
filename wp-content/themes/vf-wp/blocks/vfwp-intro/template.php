<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$lede = get_field('lede');
$lede = str_replace('<p>', '<p class="vf-lede">', $lede);
$text = get_field('intro_text');
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-intro__text">', $text);
$badge_text = get_field('badge_text');
$badge_style = get_field('badge_style');


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
) {
  $admin_banner(__('Please enter content.', 'vfwp'));
  return;
} ?>

<section class="vf-intro | embl-grid embl-grid--has-centered-content">
    <div>
        <!-- empty -->
    </div>
    <div>
    
        <h1 class="vf-intro__heading vf-intro__heading--has-tag"><?php echo ($heading); ?>       
        <?php if( ! empty($badge_text)) { ?>
        <a href="JavaScript:Void(0);" class="vf-badge vf-badge--<?php echo esc_attr($badge_style); ?> vf-badge--phases">
          <?php echo esc_html($badge_text); ?>
        </a>
      <?php } ?>
        </h1>

        <?php echo ($lede); ?>

        <?php echo ($text); ?>

    </div>
</section>
