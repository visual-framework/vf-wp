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

$menu_source = class_exists('VF_Navigation')
  ? VF_Navigation::get_render_menu_source()
  : 'location:primary';

$menu_args = array(
  'depth'         => 1,
  'container'     => false,
  'items_wrap'    => '%3$s',
  'fallback_cb'   => '__return_empty_string',
  'vf_navigation' => true,
);

if (is_string($menu_source) && strpos($menu_source, 'menu:') === 0) {
  $menu_id = (int) substr($menu_source, 5);
  if ($menu_id > 0) {
    $menu_args['menu'] = $menu_id;
  }
} elseif (is_string($menu_source) && strpos($menu_source, 'location:') === 0) {
  $location = substr($menu_source, 9);
  if ($location) {
    $menu_args['theme_location'] = $location;
  }
}

if (empty($menu_args['menu']) && empty($menu_args['theme_location'])) {
  $menu_args['theme_location'] = 'primary';
}

?>
<nav class="vf-navigation vf-navigation--main | vf-cluster | vf-u-padding__top--0" style="margin-bottom: 1rem !important;">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
<?php

wp_nav_menu($menu_args);

?>
  </ul>
</nav>
<!--/vf-navigation-->
