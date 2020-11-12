<?php
/**
 * VF-WP Groups Header template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$vf_plugin = VF_Plugin::get_plugin('vf_wp_groups_header');
if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

$is_hero = get_field('vf_hero_enable', $vf_plugin->post()->ID);
$is_hero = in_array($is_hero, array(1, '1', true));

$masthead = get_field('vf_masthead_enable');

$nav = get_field('vf_navigation_enable');

// Reset query to main template (not `$vf_plugin->post()`)
// wp_reset_postdata();

?>
<header class="vf-header vf-header--inlay">
  <?php if ($nav == 1) {
    get_template_part('vf-wp-groups-header/vf-navigation');
  }
  ?>
  <?php if ($masthead == 1) {
    get_template_part('vf-wp-groups-header/vf-masthead');
  }
   ?>
</header>
<?php if ($is_hero) { ?>
<?php get_template_part('vf-wp-groups-header/vf-hero'); ?>
<?php } ?>
