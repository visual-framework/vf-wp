<?php
/**
* Template Name: Breadrcumbs
*/

get_header();

// Global Header
?>
<span data-protection-message-disable="true"></span>
<!-- embl-ebi global header -->
<header id="masthead-black-bar" class="clearfix masthead-black-bar | ebi-header-footer vf-content vf-u-fullbleed">
</header>
<link rel="import"
  href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=6682&pattern=node-body&source=contenthub"
  data-target="self" data-embl-js-content-hub-loader>
<link rel="stylesheet" href="//ebi.emblstatic.net/web_guidelines/EBI-Icon-fonts/v1.3/fonts.css" type="text/css"
  media="all" />
<script defer="defer" src="//ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.4/js/script.js"></script>
<link rel="stylesheet" href="https://assets.emblstatic.net/vf/v2.4.12/assets/ebi-header-footer/ebi-header-footer.css"
  type="text/css" media="all" />
<nav class="vf-breadcrumbs" aria-label="Breadcrumb">
  <ul class="vf-breadcrumbs__list | vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item">
      <a href="/about" class="vf-breadcrumbs__link">About us</a>
    </li>
    <li class="vf-breadcrumbs__item">
      <a href="/about/events" class="vf-breadcrumbs__link">Events</a>
    </li>
    <li class="vf-breadcrumbs__item" aria-current="location">
      Internal events
    </li>
  </ul>
  <span class="vf-breadcrumbs__heading">Related:</span>
  <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item" false=""><a href="https://www.embl.org/events" class="vf-breadcrumbs__link">All EMBL Events</a></li>
  </ul>
</nav>

<?php
if (class_exists('VF_WP_Hero')) {
  VF_Plugin::render(VF_WP_Hero::get_plugin('vf_wp_hero_group'));
}
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
$current_date = date('Ymd');
?>

<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <?php
    if (has_nav_menu('primary')) {
      wp_nav_menu([
        'theme_location' => 'primary',
        'depth' => 1,
        'container' => FALSE,
        'items_wrap' => '%3$s',
      ]);
    }
    ?>
  </ul>
</nav>

<?php 

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

<?php

  if ( has_block( 'acf/vfwp-intro', $post ) ) {  
  parse_blocks( 'acf/vfwp-intro' ); } 

  else if ( has_block( 'acf/vfwp-page-header', $post ) ) {
  parse_blocks( 'acf/vfwp-page-header' ); }

  else if ( has_block( 'acf/vfwp-hero', $post ) ) {
    parse_blocks( 'acf/vfwp-hero' ); }
  
  else if ( has_block( 'acf/vfwp-masthead', $post ) ) {
    parse_blocks( 'acf/vfwp-masthead' ); }

  else {  ?>
    <section class="embl-grid embl-grid--has-centered-content">
      <div></div>
     <div>
     
       <h1 class="vf-text vf-text-heading--1">
         <?php echo $title;?>
       </h1>
     </div>
     <div></div>
   </section>  
   <?php } 

$vf_theme->the_content();

?>

<!-- embl-ebi global footer -->
<link rel="import" href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=106902&pattern=node-body&source=contenthub" data-target="self" data-embl-js-content-hub-loader>
<div class="vf-u-display-none" data-protection-message-disable="true"></div>

<?php get_footer(); ?>
