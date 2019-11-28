<?php
/**
 * VF-WP Groups Header template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $vf_plugin;
global $parent;
global $post;

if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

// Reset query to main template (not `$vf_plugin->post()`)
// wp_reset_postdata();

?>
<?php

?>
<header class="vf-header vf-header--inlay">
  <?php get_template_part('vf-wp-groups-header/vf-masthead'); ?>
  <?php get_template_part('vf-wp-groups-header/vf-navigation'); ?>
</header>

<?php get_template_part('vf-wp-groups-header/vf-hero'); ?>
