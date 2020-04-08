<?php
/**
 * Theme template for `single-vf_template`
 * Previews are accessible via the WordPress admin area
 */

show_admin_bar(false);

get_template_part('partials/head');

global $post;
setup_postdata($post);

global $vf_templates;

$containers = $vf_templates->get_template_plugins($post);
foreach ($containers as $post_name) {
  $plugin = VF_Plugin::get_plugin($post_name);
  VF_Plugin::render($plugin);
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
