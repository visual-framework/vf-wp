<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_Latest_Posts) {
  return;
}

$post = $vf_plugin->post();

$heading_singular = get_field('vf_latest_posts_heading_singular', $post->ID);
$heading_singular = trim($heading_singular);

$heading_plural = get_field('vf_latest_posts_heading_plural', $post->ID);
$heading_plural = trim($heading_plural);

// Use the theme provided ACF block template
$path = locate_template('blocks/vfwp-latest-posts/template.php');
if ( ! file_exists($path)) {
  return;
}

// Setup a "fake" ACF block
$block = array(
  'id'   => uniqid('block_'),
  'name' => 'acf/vfwp-latest-posts',
  'data' => array(
    'heading_singular'  => $heading_singular,
    '_heading_singular' => 'field_5e99679631cbd',
    'heading_plural'    => $heading_plural,
    '_heading_plural'   => 'field_5e9967a331cbe',
  )
);
$block = acf_prepare_block($block);

// Render the ACF block
acf_setup_meta( $block['data'], $block['id'], true );
include($path);
acf_reset_meta( $block['id'] );

?>
