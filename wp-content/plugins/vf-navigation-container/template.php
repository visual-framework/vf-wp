<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

if ($is_preview) {
?>
<div class="vf-banner vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo esc_html_e('This is a placeholder for the navigation.', 'vfwp'); ?>
    </p>
  </div>
</div>
<?php
  return;
}
?>
<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<nav class="vf-navigation vf-navigation--main | vf-cluster | vf-u-padding__top--0" style="margin-bottom: 1rem !important;">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
<?php

if (has_nav_menu('primary')) {
  wp_nav_menu(array(
    'theme_location' => 'primary',
    'depth'          => 1,
    'container'      => false,
    'items_wrap'     => '%3$s'

  ));
}

if (has_nav_menu('secondary')) {
  wp_nav_menu(array(
    'theme_location' => 'secondary',
    'depth'          => 1,
    'container'      => false,
    'items_wrap'     => '%3$s'
  ));
}

?>
  </ul>
</nav>
<!--/vf-navigation-->

