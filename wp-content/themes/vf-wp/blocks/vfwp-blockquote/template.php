<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$text = get_field('text', false, false);
$author = get_field('citation', false, false);
$author_link = get_field('author_link');
$author_image = get_field('author_image');
$blockquote_type = get_field('blockquote_type');
if ( ! is_array($author_image)) {
  $author_image = null;
} else {
$author_image = wp_get_attachment_image($author_image['ID'], 'medium', false, array(
  'class'    => ($blockquote_type === 'small') ? 'vf-profile__image vf-profile--medium vf-u-margin__right--600' : 'vf-profile__image vf-u-margin__right--600',
  'loading'  => 'lazy',
  'itemprop' => 'image',
)); }
$other_details = get_field('other_details');

// Determine blockquote class based on type
$blockquote_class = ($blockquote_type === 'small') ? 'vf-blockquote-small' : 'vf-blockquote';

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

<blockquote class="<?php echo $blockquote_class; ?> | vf-u-margin__bottom--600 vf-u-margin__top--600">
<?php if (! empty($author_image)) { 
  echo $author_image;
} ?>
  <div<?php echo (!empty($author_image)) ? ' class="vf-blockquote-has-image"' : ''; ?>>
    <div>
      <?php echo ($text); ?>
    </div>
    
    <?php if ($author) { ?>
      <footer class="vf-u-margin__top--600">
      <?php if(!empty($author_link)) { ?>
        <a href="<?php echo ($author_link); ?>" class="vf-blockquote_author__link">
      <?php } ?>

      <div class="vf-blockquote_author">
        <?php echo ($author); ?>
      </div>

      <?php if(!empty($author_link)) { ?>
        </a>
      <?php } ?>

      <div class="vf-blockquote_author__details"><?php echo ($other_details); ?></div>
    </footer>
    <?php } ?>
  </div>
</blockquote>
