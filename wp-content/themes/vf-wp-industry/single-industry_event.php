<?php 
// Global Header
if (class_exists('VF_EBI_Global_Header')) {
  VF_Plugin::render(VF_EBI_Global_Header::get_plugin('vf_ebi_global_header'));
}
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
}

get_header();

global $post;

$cancelled = get_field('vf_event_industry_canceled');

?>
<?php 
// info banner
if ($cancelled == 'postponed') { ?>
<div class="vf-banner vf-banner--alert vf-banner--info | vf-u-margin__bottom--200 vf-u-margin__top--200">
  <div class="vf-banner__content">
    <p class="vf-banner__text">This event has been postponed</a></p>
  </div>
</div>
<?php }

if ($cancelled == 'yes') { ?>
<div class="vf-banner vf-banner--alert vf-banner--danger | vf-u-margin__bottom--200 vf-u-margin__top--200">
  <div class="vf-banner__content">
    <p class="vf-banner__text">This event has been cancelled</a></p>
  </div>
</div>
<?php } ?>

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

// Global Footer
if (class_exists('VF_EBI_Global_Footer')) {
  VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
}

get_footer();

?>
