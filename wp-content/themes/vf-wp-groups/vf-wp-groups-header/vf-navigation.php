<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$menu_source = class_exists('VF_Navigation')
  ? VF_Navigation::resolve_menu_source(get_field('vf_navigation_menu'))
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
<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
<?php
wp_nav_menu($menu_args);

?>
  </ul>
</nav>
<!--/vf-navigation-->
