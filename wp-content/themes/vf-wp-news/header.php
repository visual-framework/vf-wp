<?php get_template_part('partials/head'); ?>
<?php vf_header(); ?>

<?php 
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Plugin::get_plugin('vf_breadcrumbs'));
}
?>
