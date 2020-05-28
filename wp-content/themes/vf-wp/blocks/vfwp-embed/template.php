<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$caption = get_field('caption');
$url = get_field('url');
$ratio = get_field('ratio');


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
  vf_html_empty($url)
) {
  $admin_banner(__('Please add a video.', 'vfwp'));
  return;
}

if ($ratio === '16 x 9') {
  $class = "vf-embed--16x9";
}

if ($ratio === '4 x 3') {
  $class = "vf-embed--4x3";
}

if ($ratio === '16 x 9 max width') {
  $class = "vf-embed--16x9";
  $style = "--vf-embed-max-width: 400px";
}
?>

<div class="vf-embed <?php echo ($class); ?>" style="<?php echo ($style); ?>"><iframe src="<?php echo ($url); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>

<?php if( ! empty($caption) ) { ?>  
<figcaption class="vf-figure__caption vf-u-margin__top--sm"><?php echo ($caption); ?></figcaption>

<?php } ?>
