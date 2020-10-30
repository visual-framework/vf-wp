<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list--inline | vf-cluster__inner">
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
