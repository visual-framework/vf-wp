<?php
  function vf_wp_news_award_type_labels() {
    return array(
      'name'              => _x( 'Types', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Type', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Types', 'vfwp' ),
      'all_items'         => __( 'All Types', 'vfwp' ),
      'parent_item'       => __( 'Parent Type', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Type:', 'vfwp' ),
      'edit_item'         => __( 'Edit Type', 'Updates vfwp' ),
      'update_item'       => __( 'Update Type', 'vfwp' ),
      'add_new_item'      => __( 'Add New Type', 'vfwp' ),
      'new_item_name'     => __( 'New Type Name', 'vfwp' ),
      'menu_name'         => __( 'Types', 'vfwp' ),
    );
  }

  function vf_wp_news_award_unit_labels() {
    return array(
      'name'              => _x( 'Units', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Unit', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Units', 'vfwp' ),
      'all_items'         => __( 'All Units', 'vfwp' ),
      'parent_item'       => __( 'Parent Unit', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Unit:', 'vfwp' ),
      'edit_item'         => __( 'Edit Unit', 'Updates vfwp' ),
      'update_item'       => __( 'Update Unit', 'vfwp' ),
      'add_new_item'      => __( 'Add New Unit', 'vfwp' ),
      'new_item_name'     => __( 'New Unit Name', 'vfwp' ),
      'menu_name'         => __( 'Units', 'vfwp' ),
    );
  }

  function vf_wp_news_award_site_labels() {
    return array(
      'name'              => _x( 'Sites', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Site', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Sites', 'vfwp' ),
      'all_items'         => __( 'All Sites', 'vfwp' ),
      'parent_item'       => __( 'Parent Site', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Site:', 'vfwp' ),
      'edit_item'         => __( 'Edit Site', 'Updates vfwp' ),
      'update_item'       => __( 'Update Site', 'vfwp' ),
      'add_new_item'      => __( 'Add New Site', 'vfwp' ),
      'new_item_name'     => __( 'New Site Name', 'vfwp' ),
      'menu_name'         => __( 'Sites', 'vfwp' ),
    );
  }

/**
 * Action: `init`
 */

add_action(
    'init',
    'vf_wp_news_taxonomies__init'
  );
  
  function vf_wp_news_taxonomies__init() {
  
    register_taxonomy('award-type', array('award-type'), array(
      'labels'             => vf_wp_news_award_type_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => true,
      'rewrite'            => false,
      'show_in_rest'       => true,
    ));

    register_taxonomy('award-unit', array('award-unit'), array(
      'labels'             => vf_wp_news_award_unit_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => true,
      'rewrite'            => false,
      'show_in_rest'       => true,
    ));

    register_taxonomy('award-site', array('award-site'), array(
      'labels'             => vf_wp_news_award_site_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => true,
      'rewrite'            => false,
      'show_in_rest'       => true,
    ));
 
}

  ?>