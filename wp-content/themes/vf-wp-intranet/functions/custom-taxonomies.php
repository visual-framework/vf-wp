<?php

function vf_wp_intranet_location_labels() {
    return array(
      'name'              => _x( 'EMBL Locations', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'EMBL Location', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search EMBL Locations', 'vfwp' ),
      'all_items'         => __( 'All EMBL Locations', 'vfwp' ),
      'parent_item'       => __( 'Parent EMBL Location', 'vfwp' ),
      'parent_item_colon' => __( 'Parent EMBL Location:', 'vfwp' ),
      'edit_item'         => __( 'Edit EMBL Location', 'vfwp' ),
      'update_item'       => __( 'Update EMBL Location', 'vfwp' ),
      'add_new_item'      => __( 'Add New EMBL Location', 'vfwp' ),
      'new_item_name'     => __( 'New EMBL Location Name', 'vfwp' ),
      'menu_name'         => __( 'EMBL Locations', 'vfwp' ),
    );
  }

  function vf_wp_intranet_topic_labels() {
    return array(
      'name'              => _x( 'Topics', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Topic', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Topics', 'vfwp' ),
      'all_items'         => __( 'All Topics', 'vfwp' ),
      'parent_item'       => __( 'Parent Topic', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Topic:', 'vfwp' ),
      'edit_item'         => __( 'Edit Topic', 'vfwp' ),
      'update_item'       => __( 'Update Topic', 'vfwp' ),
      'add_new_item'      => __( 'Add New Topic', 'vfwp' ),
      'new_item_name'     => __( 'New Topic Name', 'vfwp' ),
      'menu_name'         => __( 'Topics', 'vfwp' ),
    );
  }

  /**
 * Action: `init`
 */
add_action(
    'init',
    'vf_wp_intranet_taxonomies__init'
  );
  
  function vf_wp_intranet_taxonomies__init() {
  
    register_taxonomy('embl-location', array('embl-location'), array(
      'labels'             => vf_wp_intranet_location_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => false,
    ));
  
    register_taxonomy('topic', array('topic'), array(
      'labels'             => vf_wp_intranet_topic_labels(),
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