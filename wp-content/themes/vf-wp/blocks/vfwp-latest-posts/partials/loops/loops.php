<?php
$topic = get_field('topic');
$location = get_field('location');

if ($post_type == 'post') {
  $loopPost= new WP_Query (array(
    'posts_per_page' => $limit,
    'post_type' => 'post',
    'cat' => $category,
    'tag__in' => $tag,
    's' => $keyword
   )); }
  
elseif (($post_type == 'insites') && (!empty($topic)) && (!empty($location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'insites',
   'tax_query' => array(
     'relation' => 'AND',
     array (
         'taxonomy' => 'embl-location',
         'field' => 'id',
         'terms' => $location,
     ),
     array (
         'taxonomy' => 'topic',
         'field' => 'id',
         'terms' => $topic,
     )
   ),  
   'tag__in' => $tag,
   's' => $keyword
  )); }

elseif (($post_type == 'insites') && (!empty($topic))) {
  $loopPost= new WP_Query (array(
    'posts_per_page' => $limit,
    'post_type' => 'insites',
    'tax_query' => array(
      array (
          'taxonomy' => 'topic',
          'field' => 'id',
          'terms' => $topic,
      )
  ),  
    'tag__in' => $tag,
    's' => $keyword
 )); }
     

elseif (($post_type == 'insites') && (!empty($location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'insites',
   'tax_query' => array(
     array (
         'taxonomy' => 'embl-location',
         'field' => 'id',
         'terms' => $location,
     )
   ),  
   'tag__in' => $tag,
   's' => $keyword
  )); }
  
    
elseif (($post_type == 'insites') && (empty($topic))) {
  $loopPost= new WP_Query (array(
    'posts_per_page' => $limit,
    'post_type' => 'insites',
    'tag__in' => $tag,
    's' => $keyword
   )); }

//community blog 

elseif (($post_type == 'community-blog') && (!empty($topic)) && (!empty($location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'community-blog',
   'tax_query' => array(
     'relation' => 'AND',
     array (
         'taxonomy' => 'embl-location',
         'field' => 'id',
         'terms' => $location,
     ),
     array (
         'taxonomy' => 'topic',
         'field' => 'id',
         'terms' => $topic,
     )
   ),  
   'tag__in' => $tag,
   's' => $keyword
  )); }
  
elseif (($post_type == 'community-blog') && (!empty($topic))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'community-blog',
   'tax_query' => array(
     array (
         'taxonomy' => 'topic',
         'field' => 'id',
         'terms' => $topic,
     )
   ),  
   'tag__in' => $tag,
   's' => $keyword
  )); }
       
  
elseif (($post_type == 'community-blog') && (!empty($location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'community-blog',
   'tax_query' => array(
     array (
         'taxonomy' => 'embl-location',
         'field' => 'id',
         'terms' => $location,
     )
   ),  
   'tag__in' => $tag,
   's' => $keyword
  )); }
    
      
elseif (($post_type == 'community-blog') && (empty($topic))) {
$loopPost= new WP_Query (array(
  'posts_per_page' => $limit,
  'post_type' => 'community-blog',
  'tag__in' => $tag,
  's' => $keyword
 )); }
  
?>
