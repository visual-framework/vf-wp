<?php
/**
 * VF-WP Groups Header template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$vf_plugin = VF_Plugin::get_plugin('vf_wp_groups_header');
if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

// Reset query to main template (not `$vf_plugin->post()`)
// wp_reset_postdata();

?>
<?php 
get_template_part('vf-wp-groups-header/vf-hero'); 
get_template_part('vf-wp-groups-header/vf-navigation');
?>