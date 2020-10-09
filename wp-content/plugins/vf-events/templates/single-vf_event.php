<?php 
// Global Header
if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}

get_header();

global $post;

$summary = get_field('vf_event_summary');

$additional_info = get_field('vf_event_additional_info');
?>

<style>
.vf-details--summary {
  background-color: #f3f3f3 !important;
}
</style>

<?php     

// vf-hero container
include( plugin_dir_path( __FILE__ ) . 'partials/hero.php'); 
?>

<section class="vf-grid vf-grid__col-4">
  <div class="vf-grid__col--span-3 | vf-content">
    <?php the_content(); ?>
  </div>

<?php 
// info box for EMBL CCO
include( plugin_dir_path( __FILE__ ) . 'partials/event-info.php'); ?>
  
</section>

<?php 
// CPP container
include( plugin_dir_path( __FILE__ ) . 'partials/cpp-container.php'); ?>

<?php 
// Global Footer
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
}

get_footer();

?>