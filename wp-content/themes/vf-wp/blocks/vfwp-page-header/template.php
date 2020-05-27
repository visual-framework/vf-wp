<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$is_container = get_field('is_container');
// Fallback for undefined older block fields
if ($is_container === null) {
  $is_container = true;
}
$is_container = (bool) $is_container;

$heading = get_field('heading');
$sub_heading = get_field('sub_heading');

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
  && vf_html_empty($sub_heading)
) {
  $admin_banner(__('Please enter content.', 'vfwp'));
  return;
}

?>

<?php if ($is_container) { ?>
<section class="vf-inlay">
  <div class="vf-inlay__content">
    <main class="vf-inlay__content--full-width">
<?php } ?>

<header class="vf-page-header">
    <h1 class="vf-page-header__heading"><?php echo $heading; ?></h1>
    <?php if ( ! empty ($sub_heading)) { ?>
    <span class="vf-page-header__sub-heading"><?php echo $sub_heading; ?></span>
    <?php } ?>
</header>

<?php if ($is_container) { ?>
    </main>
  </div>
</section>
<?php } ?>