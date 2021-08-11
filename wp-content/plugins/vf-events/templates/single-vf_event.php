<?php 
// Global Header
if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
}

get_header();

global $post;

$event_organiser = get_field('vf_event_organiser');
$social_media_container = get_field('vf_event_social_media', $post->post_parent);
$cpp_container = get_field('vf_event_cpp_container', $post->post_parent);
$cancelled = get_field('vf_event_canceled');

?>


<?php     
// vf-hero container
include( plugin_dir_path( __FILE__ ) . 'partials/hero.php'); 
?>

<section class="vf-grid vf-grid__col-4">
  <div class="vf-grid__col--span-3 | vf-content">
    <?php the_content(); ?>
  </div>
  <?php 
// info box
include( plugin_dir_path( __FILE__ ) . 'partials/event-info.php'); ?>
</section>

<?php 
// CPP container
if ($cpp_container == 1 && $event_organiser == "cco_hd") {
include( plugin_dir_path( __FILE__ ) . 'partials/cpp-container.php'); 
}

// Social media container
if ($social_media_container == 1 && $event_organiser == "cco_hd") {
include( plugin_dir_path( __FILE__ ) . 'partials/social-container.php'); 
}

// Global Footer
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
}

get_footer();

?>
