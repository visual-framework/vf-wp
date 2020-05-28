<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$caption = get_field('caption');
$image = get_field('image');
$link = get_field('link');

$image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-figure__image',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));


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
  ! $image
) {
  $admin_banner(__('Please add an image.', 'vfwp'));
  return;
}
?>

<figure class="vf-figure">

<?php if( ($link) ) { ?>
<a href="<?php echo ($link); ?>">
<?php }

echo ($image); 

if( ($link) ) { ?>

</a>
<?php }
    
if( ! empty($caption) ) { ?>
<figcaption class="vf-figure__caption"><?php echo ($caption); ?></figcaption>
<?php } ?>

</figure>