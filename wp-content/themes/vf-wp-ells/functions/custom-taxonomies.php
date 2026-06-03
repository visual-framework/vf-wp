<?php

function vf_wp_ells_age_group_labels() {
    return array(
      'name'              => _x( 'Age groups', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Age group', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Age groups', 'vfwp' ),
      'all_items'         => __( 'All Age groups', 'vfwp' ),
      'parent_item'       => __( 'Parent Age group', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Age group:', 'vfwp' ),
      'edit_item'         => __( 'Edit Age group', 'vfwp' ),
      'update_item'       => __( 'Update Age group', 'vfwp' ),
      'add_new_item'      => __( 'Add New Age group', 'vfwp' ),
      'new_item_name'     => __( 'New Age group Name', 'vfwp' ),
      'menu_name'         => __( 'Age groups', 'vfwp' ),
    );
  }

function vf_wp_ells_topic_area_labels() {
    return array(
      'name'              => _x( 'Topic areas', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Topic area', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Topic areas', 'vfwp' ),
      'all_items'         => __( 'All Topic areas', 'vfwp' ),
      'parent_item'       => __( 'Parent Topic area', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Topic area:', 'vfwp' ),
      'edit_item'         => __( 'Edit Topic area', 'vfwp' ),
      'update_item'       => __( 'Update Topic area', 'vfwp' ),
      'add_new_item'      => __( 'Add New Topic area', 'vfwp' ),
      'new_item_name'     => __( 'New Topic area Name', 'vfwp' ),
      'menu_name'         => __( 'Topic areas', 'vfwp' ),
    );
  }

function vf_wp_ells_llabs_type_labels() {
    return array(
      'name'              => _x( 'Teacher training types', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Teacher training type', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Teacher training types', 'vfwp' ),
      'all_items'         => __( 'All Teacher training types', 'vfwp' ),
      'parent_item'       => __( 'Parent Teacher training type', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Teacher training type:', 'vfwp' ),
      'edit_item'         => __( 'Edit Teacher training type', 'vfwp' ),
      'update_item'       => __( 'Update Teacher training type', 'vfwp' ),
      'add_new_item'      => __( 'Add New Teacher training type', 'vfwp' ),
      'new_item_name'     => __( 'New Teacher training type Name', 'vfwp' ),
      'menu_name'         => __( 'Teacher training types', 'vfwp' ),
    );
  }

function vf_wp_ells_llabs_format_labels() {
    return array(
      'name'              => _x( 'Teacher training formats', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Teacher training format', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Teacher training formats', 'vfwp' ),
      'all_items'         => __( 'All Teacher training formats', 'vfwp' ),
      'parent_item'       => __( 'Parent Teacher training format', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Teacher training format:', 'vfwp' ),
      'edit_item'         => __( 'Edit Teacher training format', 'vfwp' ),
      'update_item'       => __( 'Update Teacher training format', 'vfwp' ),
      'add_new_item'      => __( 'Add New Teacher training format', 'vfwp' ),
      'new_item_name'     => __( 'New Teacher training format Name', 'vfwp' ),
      'menu_name'         => __( 'Teacher training formats', 'vfwp' ),
    );
  }

function vf_wp_ells_llabs_location_labels() {
    return array(
      'name'              => _x( 'Teacher training locations', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Teacher training location', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Teacher training locations', 'vfwp' ),
      'all_items'         => __( 'All Teacher training locations', 'vfwp' ),
      'parent_item'       => __( 'Parent Teacher training location', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Teacher training location:', 'vfwp' ),
      'edit_item'         => __( 'Edit Teacher training location', 'vfwp' ),
      'update_item'       => __( 'Update Teacher training location', 'vfwp' ),
      'add_new_item'      => __( 'Add New Teacher training location', 'vfwp' ),
      'new_item_name'     => __( 'New Teacher training location Name', 'vfwp' ),
      'menu_name'         => __( 'Teacher training locations', 'vfwp' ),
    );
  }

function vf_wp_ells_country_labels() {
    return array(
      'name'              => _x( 'Country', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Country', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Countries', 'vfwp' ),
      'all_items'         => __( 'All Countries', 'vfwp' ),
      'parent_item'       => __( 'Parent Country', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Country:', 'vfwp' ),
      'edit_item'         => __( 'Edit Country', 'vfwp' ),
      'update_item'       => __( 'Update Country', 'vfwp' ),
      'add_new_item'      => __( 'Add New Country', 'vfwp' ),
      'new_item_name'     => __( 'New Country Name', 'vfwp' ),
      'menu_name'         => __( 'Countries', 'vfwp' ),
    );
  }

  /**
 * Action: `init`
 */
add_action(
    'init',
    'vf_wp_ells_taxonomies__init'
  );
  
  function vf_wp_ells_taxonomies__init() {
  
    register_taxonomy('age-group', array('age-group'), array(
      'labels'             => vf_wp_ells_age_group_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('llabs-type', array('llabs-type'), array(
      'labels'             => vf_wp_ells_llabs_type_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('llabs-format', array('llabs-format'), array(
      'labels'             => vf_wp_ells_llabs_format_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('llabs-location', array('llabs-location'), array(
      'labels'             => vf_wp_ells_llabs_location_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('topic-area', array('topic-area'), array(
      'labels'             => vf_wp_ells_topic_area_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('country', array('country'), array(
      'labels'             => vf_wp_ells_country_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
  }
  

  ?>
