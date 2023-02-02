<?php
// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$button_link = get_field('button_link');
$button_style = get_field('button_style');
$button_size = get_field('button_size');

$size = "";
if (($button_size == 'sm') || ($button_size == 'lg')) {
  $size = " vf-button--{$button_size}";
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

if ( ! is_array($button_link)) {
  $admin_banner(__('Please enter content for this block.', 'vfwp'));
  return;
}
?>

<a href="<?php echo esc_url($button_link['url']); ?>">
<button class="vf-button vf-button--<?php echo esc_attr($button_style); echo esc_attr($size); ?>"><?php echo esc_html($button_link['title']) ?>
</button>
</a>
<!--/vf-button-->

