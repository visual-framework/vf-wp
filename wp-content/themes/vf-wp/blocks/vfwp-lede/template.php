<?php
// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$lede = get_field('lede', false, false);

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
  vf_html_empty($lede)
) {
  $admin_banner(__('Please enter content for this block.', 'vfwp'));
  return;
} ?>

<p class="vf-lede"><?php echo esc_html($lede) ?></p>
<!--/vf-lede-->

