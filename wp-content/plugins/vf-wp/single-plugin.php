<?php
/**
 * Theme template for `single-vf_block` and `single-vf_container`
 * Previews are accessible via the WordPress admin area
 */

get_template_part('partials/head');

the_post();

global $post;

if (class_exists('VF_Plugin')) {
  $vf_plugin = VF_Plugin::get_plugin($post->post_name);
  if ($vf_plugin instanceof VF_Plugin) {
    VF_Plugin::render($vf_plugin);
  }
}

get_template_part('partials/foot');

?>
