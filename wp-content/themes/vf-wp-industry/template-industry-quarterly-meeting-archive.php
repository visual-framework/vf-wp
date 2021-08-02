<?php
/**
* Template Name: Quarterly meetings archive
*/

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

$title = get_the_title();

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
<div>
  <h1 class="vf-text vf-text-heading--1">
    <?php echo $title;?>
  </h1>
</div>
<?php } 

$vf_theme->the_content(); ?>


<section class="vf-content">
  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>

      <?php include(locate_template('partials/filter-workshop.php', false, false)); ?>
    </div>
    <div class="vf-grid__col--span-3">
      <?php
$forthcomingLoop = new WP_Query (array( 
  'tax_query' => array(
    array (
        'taxonomy' => 'type',
        'field' => 'slug',
        'terms' => 'industry-quarterly-meeting',
    )
  ), 
  'post_type' => 'industry_event', 
  'order' => 'DESC', 
  'orderby' => 'meta_value_num',
  'posts_per_page' => 10, 
  'meta_key' => 'vf_event_industry_start_date', 
));
  $ids = array();
  
  while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();
    include(locate_template('partials/vf-summary-event-list.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata();   ?>
      <div class="vf-grid"> 
        <?php vf_pagination();?></div>
    </div>
  </div>
</section>

<?php get_footer(); ?>


