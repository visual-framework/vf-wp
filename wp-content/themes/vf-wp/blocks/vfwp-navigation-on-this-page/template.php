<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;


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
  ! have_rows('vf-wp-navigation-otp')
) {
  $admin_banner(__('Please add a link.', 'vfwp'));
  return;
}

?>
<nav class="vf-navigation vf-navigation--on-this-page | vf-u-fullbleed | vf-cluster">
  <?php 
  $counter = 0;
  if (have_rows('vf-wp-navigation-otp')) { ?>
    <ul class="vf-navigation__list | vf-list | vf-cluster__inner" data-vf-js-navigation-on-this-page-container="true">
    <li class="vf-navigation__item">
    On this page
    </li>
   <?php
   while (have_rows('vf-wp-navigation-otp')) {
     the_row();
     $link = get_sub_field('vf-wp-navigation-otp-link');
   ?>
   <li class="vf-navigation__item">
     <a class="vf-navigation__link" href="<?php echo esc_url($link['url']); ?>" <?php if( $counter == 0 ) { echo 'aria-selected="true"';}?>><?php echo esc_html($link['title']); ?></a>
   </li>
     <?php $counter++; } ?>
    </ul>
  <?php } ?>
</nav>
