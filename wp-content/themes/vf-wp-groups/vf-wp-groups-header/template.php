<?php
/**
 * VF-WP Groups Header template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $vf_plugin;
global $post;

if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

// Plugin is rendered inside Gutenberg block
$is_render = $vf_plugin->__experimental__is_admin_render();

// Temporary - TODO: remove once theme CSS includes component
if ($is_render) {
  if (defined('VF_WP_Groups_Header::STYLESHEET')) {
    $path = untrailingslashit(
      get_stylesheet_directory()
    );
    $path .= VF_WP_Groups_Header::STYLESHEET;
    if (file_exists($path)) {
      echo '<style>';
      include($path);
      echo '</style>';
    }

  }
}

// Reset query to main template (not `$vf_plugin->post()`)
// wp_reset_postdata();

?>
<?php if ( ! $is_render) { ?>
<header class="vf-header vf-header--inlay">
  <?php get_template_part('vf-wp-groups-header/vf-masthead'); ?>
  <?php get_template_part('vf-wp-groups-header/vf-navigation'); ?>
</header>
<?php } ?>
<?php get_template_part('vf-wp-groups-header/vf-hero'); ?>
