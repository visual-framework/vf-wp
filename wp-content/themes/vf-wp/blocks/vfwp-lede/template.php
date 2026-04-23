<?php
// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$lede = get_field('lede', false, false);
if (!empty($lede)) {
  $lede = wpautop($lede);
  $lede = str_replace('<p>', '<p class="vf-lede">', $lede);
  $lede = wp_kses_post($lede);
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
  vf_html_empty($lede)
) {
  $admin_banner(__('Please enter content for this block.', 'vfwp'));
  return;
} ?>

<?php echo $lede; ?>
<!--/vf-lede-->
