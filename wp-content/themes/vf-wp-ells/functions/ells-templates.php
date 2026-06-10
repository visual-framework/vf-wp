<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function vf_wp_ells_template_block($name) {
  $attrs = array(
    'id' => uniqid('block_'),
    'name' => $name,
  );

  return '<!-- wp:' . $name . ' ' . wp_json_encode($attrs) . ' /-->';
}

function vf_wp_ells_default_template_content($post_content) {
  return implode("\n\n", array(
    vf_wp_ells_template_block('acf/vf-container-global-header'),
    vf_wp_ells_template_block('acf/vf-container-ebi-global-header'),
    vf_wp_ells_template_block('acf/vf-container-breadcrumbs'),
    vf_wp_ells_template_block('acf/vf-container-page-template'),
    vf_wp_ells_template_block('acf/vf-container-global-footer'),
    vf_wp_ells_template_block('acf/vf-container-ebi-global-footer'),
  )) . "\n";
}
add_filter('vf/templates/post_content/default', 'vf_wp_ells_default_template_content');
add_filter('vf/templates/post_content/front_page', 'vf_wp_ells_default_template_content');

function vf_wp_ells_remove_unavailable_template_blocks($content) {
  $unavailable_blocks = array(
    'acf/vf-container-wp-groups-header',
  );

  foreach ( $unavailable_blocks as $block_name ) {
    $content = preg_replace(
      '/<!--\s+wp:' . preg_quote($block_name, '/') . '\b.*?\/-->\s*/s',
      '',
      $content
    );
  }

  return $content;
}

function vf_wp_ells_sanitize_saved_vf_templates() {
  if ( ! post_type_exists('vf_template') ) {
    return;
  }

  $templates = get_posts(array(
    'post_type' => 'vf_template',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'no_found_rows' => true,
  ));

  foreach ( $templates as $template_id ) {
    $content = get_post_field('post_content', $template_id);
    $sanitized_content = vf_wp_ells_remove_unavailable_template_blocks($content);

    if ( $sanitized_content !== $content ) {
      wp_update_post(array(
        'ID' => $template_id,
        'post_content' => $sanitized_content,
      ));
    }
  }
}
add_action('init', 'vf_wp_ells_sanitize_saved_vf_templates', 20);

?>
