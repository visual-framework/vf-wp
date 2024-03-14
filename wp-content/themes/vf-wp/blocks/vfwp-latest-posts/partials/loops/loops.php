<?php
$topic = get_field('topic');
$topic_updates = get_field('topic_updates');
$topic_events = get_field('topic_events');
$location = get_field('location');
$event_location = get_field('location_events');
$current_date = date('Ymd');


if ($post_type == 'post') {
  $loopPost= new WP_Query (array(
    'posts_per_page' => $limit,
    'post_type' => 'post',
    'cat' => $category,
    'tag__in' => $tag
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
   'tag__in' => $tag
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
    'tag__in' => $tag
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
   'tag__in' => $tag
  )); }
  
    
elseif (($post_type == 'insites') && (empty($topic))) {
  $loopPost= new WP_Query (array(
    'posts_per_page' => $limit,
    'post_type' => 'insites',
    'tag__in' => $tag
   )); }

//community blog 

elseif (($post_type == 'community-blog') && (!empty($topic_updates)) && (!empty($location))) {
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
         'taxonomy' => 'updates-topic',
         'field' => 'id',
         'terms' => $topic_updates,
     )
   ),  
   'tag__in' => $tag
  )); }
  
elseif (($post_type == 'community-blog') && (!empty($topic_updates))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'community-blog',
   'tax_query' => array(
     array (
         'taxonomy' => 'updates-topic',
         'field' => 'id',
         'terms' => $topic_updates,
     )
   ),  
   'tag__in' => $tag
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
   'tag__in' => $tag
  )); }
    
      
elseif (($post_type == 'community-blog') && (empty($topic_updates))) {
$loopPost= new WP_Query (array(
  'posts_per_page' => $limit,
  'post_type' => 'community-blog',
  'tag__in' => $tag
 )); }

// events

elseif (($post_type == 'events') && (!empty($topic_events)) && (!empty($event_location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'events',
   'order' => 'ASC', 
   'orderby' => 'meta_value_num',
   'meta_key' => 'vf_event_internal_start_date',
   'meta_query' => array(
     'relation' => 'OR',
     array(
         'key' => 'vf_event_internal_start_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
         'key' => 'vf_event_internal_end_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
       'key' => 'vf_event_internal_start_date',
       'value' => date('Ymd', strtotime('now')),
       'type' => 'numeric',
       'compare' => '>=',
       ) 
     ),
   'tax_query' => array(
     'relation' => 'AND',
     array (
         'taxonomy' => 'event',
         'field' => 'id',
         'terms' => $event-location,
     ),
     array (
         'taxonomy' => 'events-topic',
         'field' => 'id',
         'terms' => $topic_events,
     )
   ),  
   'tag__in' => $tag
  )); }
  
elseif (($post_type == 'events') && (!empty($topic_events))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'events',
   'order' => 'ASC', 
   'orderby' => 'meta_value_num',
   'meta_key' => 'vf_event_internal_start_date',
   'meta_query' => array(
     'relation' => 'OR',
     array(
         'key' => 'vf_event_internal_start_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
         'key' => 'vf_event_internal_end_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
       'key' => 'vf_event_internal_start_date',
       'value' => date('Ymd', strtotime('now')),
       'type' => 'numeric',
       'compare' => '>=',
       ) 
     ),
   'tax_query' => array(
     array (
         'taxonomy' => 'events-topic',
         'field' => 'id',
         'terms' => $topic_events,
     )
   ),  
   'tag__in' => $tag
  )); }
       
  
elseif (($post_type == 'events') && (!empty($event_location))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => 'events',
   'order' => 'ASC', 
   'orderby' => 'meta_value_num',
   'meta_key' => 'vf_event_internal_start_date',
   'meta_query' => array(
     'relation' => 'OR',
     array(
         'key' => 'vf_event_internal_start_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
         'key' => 'vf_event_internal_end_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
       'key' => 'vf_event_internal_start_date',
       'value' => date('Ymd', strtotime('now')),
       'type' => 'numeric',
       'compare' => '>=',
       ) 
     ),
   'tax_query' => array(
     array (
         'taxonomy' => 'event-location',
         'field' => 'id',
         'terms' => $event_location,
     )
   ),  
   'tag__in' => $tag
  )); }
    
      
elseif (($post_type == 'events') && (empty($topic_events))) {
$loopPost= new WP_Query (array(
  'posts_per_page' => $limit,
  'post_type' => 'events',
  'order' => 'ASC', 
   'orderby' => 'meta_value_num',
   'meta_key' => 'vf_event_internal_start_date',
   'meta_query' => array(
     'relation' => 'OR',
     array(
         'key' => 'vf_event_internal_start_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
         'key' => 'vf_event_internal_end_date',
         'value' => $current_date,
         'compare' => '>=',
         'type' => 'numeric'
     ),
     array(
       'key' => 'vf_event_internal_start_date',
       'value' => date('Ymd', strtotime('now')),
       'type' => 'numeric',
       'compare' => '>=',
       ) 
     ),
  'tag__in' => $tag
 )); }

 // both

elseif (($post_type == 'both') && (!empty((($topic_updates) && ($topic)) && ($location)))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => array('community-blog', 'insites'),
   'tax_query' => array(
    'relation' => 'AND',
    array (
      'relation' => 'OR',
      array (
        'taxonomy' => 'updates-topic',
        'field' => 'id',
        'terms' => $topic_updates,
    ),
    array (
        'taxonomy' => 'topic',
        'field' => 'id',
        'terms' => $topic,
    ),
    ),
    array (
      'taxonomy' => 'embl-location',
      'field' => 'id',
      'terms' => $location,
     ),

   ),  

   'tag__in' => $tag
  )); } 

elseif (($post_type == 'both') && (!empty(($topic_updates) && ($topic)))) {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => array('community-blog', 'insites'),
   'tax_query' => array(
     'relation' => 'OR',
     array (
         'taxonomy' => 'updates-topic',
         'field' => 'id',
         'terms' => $topic_updates,
     ),
     array (
         'taxonomy' => 'topic',
         'field' => 'id',
         'terms' => $topic,
     ),
   ),  
   'tag__in' => $tag
  )); } 

elseif (($post_type == 'both') && (empty(($topic_updates) && ($topic) && ($location))))  {
 $loopPost= new WP_Query (array(
   'posts_per_page' => $limit,
   'post_type' => array('community-blog', 'insites'),
   'tag__in' => $tag
  )); } 




?>
