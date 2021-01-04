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