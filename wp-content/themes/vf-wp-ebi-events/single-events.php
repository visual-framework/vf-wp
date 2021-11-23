<?php 
// Global Header
?>
<span data-protection-message-disable="true"></span>
<!-- embl-ebi global header -->
<header id="masthead-black-bar" class="clearfix masthead-black-bar | ebi-header-footer vf-content vf-u-fullbleed"></header>
<link rel="import" href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=6682&pattern=node-body&source=contenthub" data-target="self" data-embl-js-content-hub-loader>
<link rel="stylesheet" href="//ebi.emblstatic.net/web_guidelines/EBI-Icon-fonts/v1.3/fonts.css" type="text/css" media="all" />
<script defer="defer" src="//ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.4/js/script.js"></script>
<link rel="stylesheet" href="https://assets.emblstatic.net/vf/v2.4.12/assets/ebi-header-footer/ebi-header-footer.css" type="text/css" media="all" />

<?php
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
if (class_exists('VF_EBI_Global_Footer')) {
  VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
}

get_footer();

?>
