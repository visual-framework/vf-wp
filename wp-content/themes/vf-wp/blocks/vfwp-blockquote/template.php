<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$text = get_field('text', false, false);
$citation = get_field('citation', false, false);
$color = get_field('color');

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
  vf_html_empty($text)
) {
  $admin_banner(__('Please enter the content for this block.', 'vfwp'));
  return;
}
?>

<blockquote class="vf-blockquote | vf-stack vf-stack--400 | vf-u-margin__bottom--400">
  <p
    class="vf-blockquote__text | vf-u-margin__top--0<?php if ($color == 'grey') { echo ' | vf-u-text-color--grey--dark';} ?>">
    <?php echo ($text); ?>
  </p>
  <?php if ($citation) { ?>
  <footer class="vf-blockquote__footer">
    <cite class="vf-blockquote__citation | vf-text-body vf-text-body--3"><?php echo ($citation); ?></cite>
  </footer>
  <?php } ?>
</blockquote>
