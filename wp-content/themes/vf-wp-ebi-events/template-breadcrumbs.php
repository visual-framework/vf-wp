<?php
/**
* Template Name: Breadrcumbs
*/

get_header();
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
$current_date = date('Ymd');
// Global Header
?>
<?php include(locate_template('partials/ebi_header.php', false, false)); ?>

<nav class="vf-breadcrumbs" aria-label="Breadcrumb">
  <ul class="vf-breadcrumbs__list | vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item">
      <a href="/about/events" class="vf-breadcrumbs__link">Events</a>
    </li>
    <li class="vf-breadcrumbs__item">
      <a href="/about/events/seminars" class="vf-breadcrumbs__link">Seminars</a>
    </li>
    <li class="vf-breadcrumbs__item" aria-current="location">
      <?php echo $title;?>
    </li>
  </ul>
  <span class="vf-breadcrumbs__heading">Related:</span>
  <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item" false=""><a href="https://www.embl.org/events" class="vf-breadcrumbs__link">All EMBL Events</a></li>
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
<?php include(locate_template('partials/ebi_footer.php', false, false)); ?>

<?php get_footer(); ?>
