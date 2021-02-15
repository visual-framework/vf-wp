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
      'name'              => _x( 'LLabs types', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'LLabs type', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search LLabs types', 'vfwp' ),
      'all_items'         => __( 'All LLabs types', 'vfwp' ),
      'parent_item'       => __( 'Parent LLabs type', 'vfwp' ),
      'parent_item_colon' => __( 'Parent LLabs type:', 'vfwp' ),
      'edit_item'         => __( 'Edit LLabs type', 'vfwp' ),
      'update_item'       => __( 'Update LLabs type', 'vfwp' ),
      'add_new_item'      => __( 'Add New LLabs type', 'vfwp' ),
      'new_item_name'     => __( 'New LLabs type Name', 'vfwp' ),
      'menu_name'         => __( 'LLabs types', 'vfwp' ),
    );
  }

function vf_wp_ells_llabs_format_labels() {
    return array(
      'name'              => _x( 'LLabs formats', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'LLabs format', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search LLabs formats', 'vfwp' ),
      'all_items'         => __( 'All LLabs formats', 'vfwp' ),
      'parent_item'       => __( 'Parent LLabs format', 'vfwp' ),
      'parent_item_colon' => __( 'Parent LLabs format:', 'vfwp' ),
      'edit_item'         => __( 'Edit LLabs format', 'vfwp' ),
      'update_item'       => __( 'Update LLabs format', 'vfwp' ),
      'add_new_item'      => __( 'Add New LLabs format', 'vfwp' ),
      'new_item_name'     => __( 'New LLabs format Name', 'vfwp' ),
      'menu_name'         => __( 'LLabs formats', 'vfwp' ),
    );
  }

function vf_wp_ells_llabs_location_labels() {
    return array(
      'name'              => _x( 'LLabs locations', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'LLabs location', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search LLabs locations', 'vfwp' ),
      'all_items'         => __( 'All LLabs locations', 'vfwp' ),
      'parent_item'       => __( 'Parent LLabs location', 'vfwp' ),
      'parent_item_colon' => __( 'Parent LLabs location:', 'vfwp' ),
      'edit_item'         => __( 'Edit LLabs location', 'vfwp' ),
      'update_item'       => __( 'Update LLabs location', 'vfwp' ),
      'add_new_item'      => __( 'Add New LLabs location', 'vfwp' ),
      'new_item_name'     => __( 'New LLabs location Name', 'vfwp' ),
      'menu_name'         => __( 'LLabs locations', 'vfwp' ),
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
  
  }
  

  ?>