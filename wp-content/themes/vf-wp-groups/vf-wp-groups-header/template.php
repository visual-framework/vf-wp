<?php

global $vf_plugin;
global $parent;
global $post;

// Reset query to main template (not `$vf_plugin->post()`)
wp_reset_postdata();

?>
<header class="vf-header vf-header--inlay">
  <?php get_template_part('vf-wp-groups-header/vf-masthead'); ?>
  <?php get_template_part('vf-wp-groups-header/vf-navigation'); ?>
</header>
