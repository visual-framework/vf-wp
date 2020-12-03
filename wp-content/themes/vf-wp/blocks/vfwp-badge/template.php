<?php
// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$badge_link = get_field('badge_link');
$badge_text = get_field('badge_text');
$badge_style = get_field('badge_style');
$badge_variant = get_field('badge_variant');

$variant = "";
if ($badge_variant != "default") {
  $variant = " vf-badge--{$badge_variant}";
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
  vf_html_empty($badge_text)
) {
  $admin_banner(__('Please enter content for this block.', 'vfwp'));
  return;
}

if (!empty ($badge_link)) { ?>
  <a href="<?php echo esc_url($badge_link['url']); ?>" class="vf-badge vf-badge--<?php echo esc_attr($badge_style); echo esc_attr($variant);?>"><?php echo esc_html($badge_text) ?></a>

<?php }

else { ?>
 <span class="vf-badge vf-badge--<?php echo esc_attr($badge_style); echo esc_attr($variant);?>"><?php echo esc_html($badge_text) ?></span>
 <?php } ?>
<!--/vf-badge-->

