<?php


function ebi_event_type_labels() {
  return array(
    'name'              => _x( 'Type', 'taxonomy general name', 'vfwp' ),
    'singular_name'     => _x( 'Type', 'taxonomy singular name', 'vfwp' ),
    'search_items'      => __( 'Search Types', 'vfwp' ),
    'all_items'         => __( 'All Types', 'vfwp' ),
    'parent_item'       => __( 'Parent Type', 'vfwp' ),
    'parent_item_colon' => __( 'Parent Type:', 'vfwp' ),
    'edit_item'         => __( 'Edit Type', 'vfwp' ),
    'update_item'       => __( 'Update Type', 'vfwp' ),
    'add_new_item'      => __( 'Add New Type', 'vfwp' ),
    'new_item_name'     => __( 'New Type Name', 'vfwp' ),
    'menu_name'         => __( 'Types', 'vfwp' ),
  );
}


function ebi_taxonomies__init() {

  register_taxonomy('type', array('type'), array(
    'labels'             => ebi_event_type_labels(),
    'hierarchical'       => true,
    'show_ui'            => true,
    'show_admin_column'  => true,
    'query_var'          => true,
    'publicly_queryable' => true,
    'show_in_nav_menus'  => false,
    'rewrite' => array('slug' => ''), 
    'show_in_rest'       => false,
  ));
}
add_action(
  'init',
  'ebi_taxonomies__init'
);



  ?>
