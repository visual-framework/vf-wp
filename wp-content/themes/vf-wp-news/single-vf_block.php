<?php

get_template_part('partials/head');

the_post();

?>
<?php

global $post;

if (class_exists('VF_Plugin')) {
  $vf_plugin = VF_Plugin::get_plugin($post->post_name);
  if ($vf_plugin instanceof VF_Plugin) {
    VF_Plugin::render($vf_plugin);
  }
}

?>
<?php

get_template_part('partials/foot');

?>
