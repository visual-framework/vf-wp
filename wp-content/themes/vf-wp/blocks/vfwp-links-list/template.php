<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$heading = get_field('heading');
$list_items = get_field('list_items');
$meta_information = get_field('meta_information');

$type = get_field('select_type');
if (empty($type)) {
  $type = 'default';
}
if (is_array($type) && count($type) > 0) {
  $type = $type[0]; // Ensure it's a string even if returned as an array
}

$class_div = "vf-links";
$class_ul = "vf-links__list vf-list"; 

if (in_array($type, ['tight', 'very-easy'])) {
  $class_div .= ' vf-links--tight vf-links__list--s';
  $class_ul .= ' vf-links__list--secondary';
}

if (in_array($type, ['easy', 'very-easy', 'has-image'])) {
  $class_div .= " vf-links__list--{$type}";
}

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if (!$is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text"><?php echo $message; ?></p>
  </div>
</div>
<?php
};

if (!have_rows('list_items')) {
  $admin_banner(__('Please add an item to the list.', 'vfwp'));
  return;
}

?>
<div class="<?php echo esc_attr($class_div); ?>">
  <?php if (!empty($heading)) { ?>
    <h3 class="vf-links__heading"><?php echo $heading; ?></h3>
  <?php } ?>
  <ul class="<?php echo esc_attr($class_ul); ?>">
  <?php while (have_rows('list_items')): the_row();
    $text = get_sub_field('text');
    $link = get_sub_field('link');
    $badge_text = get_sub_field('badge_text');
    $badge_style = get_sub_field('badge_style');
    $image = get_sub_field('image');

    // Ensure $image is an array or set to null
    if (!is_array($image)) {
      $image = null;
    } else {
      $image = wp_get_attachment_image($image['ID'], 'thumbnail', false, [
        'class' => 'vf-list__image',
        'loading' => 'lazy',
        'itemprop' => 'image',
      ]);
    }
  ?>
  <li class="vf-list__item">
    <?php if (is_array($link) && isset($link['url'])): ?>
      <a class="vf-list__link" href="<?php echo esc_url($link['url']); ?>">
        <?php if ($type === 'has-image'):
          echo $image ?: '<div class="vf-list__image"></div>';
        endif;
        echo esc_html($text);
        if ($type === 'easy'): ?>
          <svg class="vf-icon vf-icon__arrow--down | vf-list__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <title>arrow-button-down</title>
            <path d="M.249,7.207,11.233,19.678h0a1.066,1.066,0,0,0,1.539,0L23.751,7.207a.987.987,0,0,0-.107-1.414l-1.85-1.557a1.028,1.028,0,0,0-1.438.111L12.191,13.8a.25.25,0,0,1-.379,0L3.644,4.346A1.021,1.021,0,0,0,2.948,4a1,1,0,0,0-.741.238L.356,5.793A.988.988,0,0,0,0,6.478.978.978,0,0,0,.249,7.207Z"/>
          </svg>
        <?php endif; ?>
      </a>
    <?php endif;
    if (!empty($badge_text)): ?>
      <span class="vf-badge vf-badge--<?php echo esc_attr($badge_style); ?>">
        <?php echo esc_html($badge_text); ?>
      </span>
    <?php endif;
    if (!empty($meta_information)): ?>
      <p class="vf-links__meta"><?php echo esc_html($meta_information); ?></p>
    <?php endif; ?>
  </li>
  <?php endwhile; ?>
</ul>

</div>
