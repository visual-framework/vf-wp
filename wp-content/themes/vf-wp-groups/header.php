<?php get_template_part('partials/head'); ?>
<?php vf_header(); ?>
<?php

if (class_exists('VF_WP_Groups_Header')) {
  $header = new VF_WP_Groups_Header();
  var_dump($header);
  VF_Plugin::render($header);
}

?>
