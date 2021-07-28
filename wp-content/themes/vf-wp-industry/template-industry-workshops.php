<?php
/**
* Template Name: Industry workshops
*/

$current_date = date('Ymd');


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


<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="vf-tabs">
      <ul class="vf-tabs__list" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--1">Forthcoming workshops</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--2">Past workshops</a>
        </li>
      </ul>
    </div>
    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section" id="vf-tabs__section--1">
        <?php

$forthcomingLoop = new WP_Query (array(
    'tax_query' => array(
        array (
            'taxonomy' => 'type',
            'field' => 'slug',
            'terms' => 'industry-workshop',
        )
    ),
    'post_type' => 'industry_event', 
    'order' => 'ASC',
    'orderby' => 'meta_value_num', 
    'posts_per_page' => 10, 
    'meta_key' => 'vf_event_industry_start_date', 
    'meta_query' => array(
    array(
        'key' => 'vf_event_industry_start_date',
        'value' => $current_date,
        'compare' => '>=',
        'type' => 'numeric'
    ),
            array(
          'key' => 'vf_event_industry_start_date',
          'value' => date('Ymd', strtotime('now')),
          'type' => 'numeric',
          'compare' => '>=',
          ),
        array(
          'key' => 'vf_event_industry_end_date',
          'value' => date('Ymd', strtotime('now')),
          'type' => 'numeric',
          'compare' => '>=',
          ),  

    
) ));
$ids = array();
$current_month = "";
while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();
$ids[] = get_the_ID();
$start_date = get_field('vf_event_industry_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_industry_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);
?>
<h3>
<?php
  $dateformatstring = "F Y";
  $unixtimestamp = strtotime(get_field('vf_event_industry_start_date'));
  $pretty_month = date_i18n($dateformatstring, $unixtimestamp);
  if ($current_month != $pretty_month){
    echo $pretty_month;
    $current_month = $pretty_month;
  }
  ?>
</h3> <?php
include(locate_template('partials/vf-summary-event.php', false, false)); ?>
        <?php endwhile;?>
        <?php wp_reset_postdata(); ?>

    </section>

    <section class="vf-tabs__section" id="vf-tabs__section--2">

      <?php
$pastLoop = new WP_Query (array(
    'tax_query' => array(
        array (
            'taxonomy' => 'type',
            'field' => 'slug',
            'terms' => 'industry-workshop',
        )
    ),
    'post_type' => 'industry_event', 
    'order' => 'DESC' ,
    'orderby' => 'meta_value_num',
    'posts_per_page' => 10, 
    'meta_key' => 'vf_event_industry_start_date',    
    'meta_query' => array(
    array(
        'key' => 'vf_event_industry_start_date',
        'value' => $current_date,
        'compare' => '<=',
        'type' => 'numeric'
    ),
            array(
          'key' => 'vf_event_industry_start_date',
          'value' => date('Ymd', strtotime('now')),
          'type' => 'numeric',
          'compare' => '<=',
          ),
        array(
          'key' => 'vf_event_industry_end_date',
          'value' => date('Ymd', strtotime('now')),
          'type' => 'numeric',
          'compare' => '<=',
          ),  


) ));
$ids = array();
$current_month = "";
while ($pastLoop->have_posts()) : $pastLoop->the_post();
$ids[] = get_the_ID();
$start_date = get_field('vf_event_industry_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_industry_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);
?>
<h3>
<?php
  $dateformatstring = "F Y";
  $unixtimestamp = strtotime(get_field('vf_event_industry_start_date'));
  $pretty_month = date_i18n($dateformatstring, $unixtimestamp);
  if ($current_month != $pretty_month){
    echo $pretty_month;
    $current_month = $pretty_month;
  }
  ?>
</h3> <?php
include(locate_template('partials/vf-summary-event.php', false, false)); ?>

      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
      <p><a href="./archive">View all past workshops</a></p>
    </section>
</div>
  </div>
</div>

<?php get_footer(); ?>