<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$title = get_field('title');
$id = get_field('vf-details-id');
$display_content = get_field('display_content');
$summary = get_field('summary', false, false);

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
  vf_html_empty($title)
) {
  $admin_banner(__('Please enter a title for this block.', 'vfwp'));
  return;
}
?>

<details  class="vf-details" id="<?php echo $id; ?>" <?php if ($is_preview) { echo ' open="open"'; } ?> <?php if ($display_content == true) { echo ' open="open"'; } ?>>
<summary class="vf-details--summary">
<?php echo esc_html($title); ?>
</summary>
<?php
// Keep old WYSIWYG field for backwards compatibility
if ( ! empty($summary)) {
  echo wpautop($summary);
}
?>
<InnerBlocks />
</details>
