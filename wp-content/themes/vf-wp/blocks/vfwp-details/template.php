<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$title = get_field('title');
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
  && vf_html_empty($summary)
) {
  $admin_banner(__('Please enter content for this block.', 'vfwp'));
  return;
}
?>

<details class="vf-details">
<summary class="vf-details--summary">
<?php echo esc_html($title); ?>
</summary>
<?php echo wpautop($summary); ?>
</details>

