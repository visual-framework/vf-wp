<?php

if (class_exists('VF_Global_Header')) {
    VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
  }
if (class_exists('VF_EBI_Global_Footer')) {
    VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_header'));
  }
if (class_exists('VF_Breadcrumbs')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
  }
get_header();

global $vf_theme;

$title = $vf_theme->get_title();
$slug = get_page_by_path( 'blog' ); 
$custom_template = get_field('vf_groups_custom_blog_template', $slug->ID);

if ($custom_template) {
  if (class_exists('VF_WP_Hero_Blog')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_hero_blog'));
  }
  if (class_exists('VF_Navigation')) {
    VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
  } }
else {
  if (class_exists('VF_WP_Groups_Header')) {
    VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_wp_groups_header'));
  }
  
}
if ($custom_template) {echo '<br>';}
?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800">
    <div class="vf-grid__col--span-2">
      <?php
       if (!$custom_template) { ?>
      <h1 class="vf-text vf-text-heading--1">
        <?php echo esc_html($title); ?>
      </h1>
      <?php } ?>
      <?php
      while (have_posts()) {
        the_post();
        get_template_part('partials/vf-summary--article');
        if ( ! $vf_theme->is_last_post()) {
          echo '<hr class="vf-divider">';
        }
      }
      vf_pagination();
      ?>
    </div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <div>
      <?php vf_sidebar('sidebar-blog'); ?>
    </div>
    <?php } ?>
</div>
<?php
// Global Footer
if (class_exists('VF_Global_Footer')) {
    VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
  }
if (class_exists('VF_EBI_Global_Footer')) {
    VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
  }
  
get_footer();
  ?>