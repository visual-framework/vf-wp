<?php

/**
* Template Name: Secondary Hero (EBI)
*/

global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();

get_header(); 
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

if (class_exists('VF_WP_Hero_Secondary')) {
  VF_Plugin::render(VF_WP_Hero_Secondary::get_plugin('vf_wp_hero_secondary'));
}

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}


$open_wrap = function($html, $block_name) {
  $html = '
<div class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
' . $html;
return $html;
};

$close_wrap = function($html, $block_name) {
  $html .= '
  </div>
  <div></div>
</div>
<!--/embl-grid-->';
return $html;
};

add_filter(
'vf/__experimental__/theme/content/open_block_wrap',
$open_wrap,
10, 2
);

add_filter(
'vf/__experimental__/theme/content/close_block_wrap',
$close_wrap,
10, 2
);
?>

<div>
  <h1 class="vf-text vf-text-heading--1">
    <?php echo $title;?>
  </h1>
</div>

<?php 

$vf_theme->the_content();

if (class_exists('VF_EBI_Global_Footer')) {
  VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
}
 ?>

<?php get_footer(); ?>