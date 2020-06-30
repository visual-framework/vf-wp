<?php

$variant = get_field('banner_variant');
$text = get_field('text', false, false);

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

// Block is container style
$is_container = (bool) get_field('is_container');

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

if ($is_container) { ?>
  <div class="vf-grid">
<?php } 

if (
  vf_html_empty($text)
) {
  $admin_banner(__('Please add custom text for this banner', 'vfwp'));
  return;
} ?>

<div class="vf-banner vf-banner--alert vf-banner--<?php echo $variant ?>">

    <div class="vf-banner__content">

        <p class="vf-banner__text"><?php echo ($text); ?></p>

    </div>

</div>                                                                                


<?php if ($is_container) { ?>
  </div>
<?php } ?>
