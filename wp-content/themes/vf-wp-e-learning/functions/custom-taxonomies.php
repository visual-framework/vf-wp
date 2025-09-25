<?php
function vf_wp_elearning_training_organiser_labels() {
  return array(
    'name'              => _x( 'Provider', 'taxonomy general name', 'vfwp' ),
    'singular_name'     => _x( 'Provider', 'taxonomy singular name', 'vfwp' ),
    'search_items'      => __( 'Search Providers', 'vfwp' ),
    'all_items'         => __( 'All Providers', 'vfwp' ),
    'parent_item'       => __( 'Parent Provider', 'vfwp' ),
    'parent_item_colon' => __( 'Parent Provider:', 'vfwp' ),
    'edit_item'         => __( 'Edit Provider', 'vfwp' ),
    'update_item'       => __( 'Update Provider', 'vfwp' ),
    'add_new_item'      => __( 'Add New Provider', 'vfwp' ),
    'new_item_name'     => __( 'New Provider Name', 'vfwp' ),
    'menu_name'         => __( 'Provider', 'vfwp' ),
  );
}

  /**
 * Action: `init`
 */
add_action(
    'init',
    'vf_wp_elearning_taxonomies__init'
  );
  
  function vf_wp_elearning_taxonomies__init() {
  

    register_taxonomy('training-organiser', array('training-organiser'), array(
      'labels'             => vf_wp_elearning_training_organiser_labels(),
      'hierarchical'       => true,
      'show_ui'            => true,
      'show_admin_column'  => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'show_in_nav_menus'  => false,
      'rewrite'            => false,
      'show_in_rest'       => true,
    ));
  
  }