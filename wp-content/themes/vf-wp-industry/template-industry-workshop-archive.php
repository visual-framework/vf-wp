<?php
/**
* Template Name: Workshops archive
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
  <div class="vf-grid vf-grid__col-3 | vf-u-padding__top--400">
    <div class="vf-grid__col--span-3">
    <?php
     $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$forthcomingLoop = new WP_Query (array( 
  'paged' => $paged,
  'tax_query' => array(
    array (
        'taxonomy' => 'type',
        'field' => 'slug',
        'terms' => 'industry-workshop',
    )
  ), 
  'posts_per_page' => 12, 
  'post_type' => 'industry_event', 
  'order' => 'DESC', 
  'orderby' => 'meta_value_num',
  'meta_key' => 'vf_event_industry_start_date', 
));
  $ids = array();
  $temp_query = $wp_query;
  $wp_query   = NULL;
  $wp_query   = $forthcomingLoop;
  $current_month = "";
  while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();
  $start_date = get_field('vf_event_industry_start_date', $post->post_parent);
  $start = DateTime::createFromFormat('j M Y', $start_date);
  $decide = get_field('vf_event_industry_date_to_be_decided', $post->post_parent);
  $end_date = get_field('vf_event_industry_end_date', $post->post_parent);
  $end = DateTime::createFromFormat('j M Y', $end_date); ?>
      <h3>
      <?php
      $dateformatstring = "Y";
      $unixtimestamp = strtotime(get_field('vf_event_industry_start_date'));
      $pretty_month = date_i18n($dateformatstring, $unixtimestamp);
      if ($current_month != $pretty_month){
        echo $pretty_month;
        $current_month = $pretty_month;
      } ?>
      </h3> 
<?php

    include(locate_template('partials/vf-summary-event.php', false, false)); ?>
      
      <?php endwhile;?>
      <?php wp_reset_postdata();   ?>
      <div class="vf-grid"> 
        <?php 
        vf_pagination();
        $wp_query = NULL;
        $wp_query = $temp_query;
        ?>
        </div>
    </div>
    <div></div>
  </div>
</section>

<?php get_footer(); ?>


