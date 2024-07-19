<?php
/**
* Template Name: Title left aligned
*/

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

$title = get_the_title();

// display the latest editor on the intranet theme
$current_theme = wp_get_theme();
if ($current_theme->get('Name') === 'VF-WP Intranet') {
    display_latest_editor_for_admin(get_the_ID());
}

?>

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
     <div class="title-container">
     <?php
    $tags = get_the_tags($post->ID);
    if ($tags) {
    $tagslist = array();
    foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-badge">' . $tag->name . '</a>';
    }
    echo implode('  ', $tagslist);
    } 
    ?>
       <h1 class="vf-text vf-text-heading--1">
         <?php echo $title;?>
       </h1>
     </div>
   <?php } 

$vf_theme->the_content();

?>
<?php 

get_footer();

?>