<?php
/**
 * Theme template for `single-vf_block` and `single-vf_container`
 * Previews are accessible via the WordPress admin area
 */

if ( ! is_user_logged_in()) {
  return;
}

show_admin_bar(false);

get_template_part('partials/head');

global $post;
setup_postdata($post);

if (class_exists('VF_Plugin')) {
  $vf_plugin = VF_Plugin::get_plugin($post->post_name);
  if ($vf_plugin instanceof VF_Plugin) {
    VF_Plugin::render($vf_plugin);
  }
}

?>
<style type="text/css">
  html {
    margin: 0 !important;
  }
</style>
<?php

get_template_part('partials/foot');

?>
