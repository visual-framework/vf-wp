<?php
function vf_wp_training_category_labels() {
    return array(
      'name'              => _x( 'Category', 'taxonomy general name', 'vfwp' ),
      'singular_name'     => _x( 'Category', 'taxonomy singular name', 'vfwp' ),
      'search_items'      => __( 'Search Categories', 'vfwp' ),
      'all_items'         => __( 'All Categories', 'vfwp' ),
      'parent_item'       => __( 'Parent Category', 'vfwp' ),
      'parent_item_colon' => __( 'Parent Category:', 'vfwp' ),
      'edit_item'         => __( 'Edit Category', 'vfwp' ),
      'update_item'       => __( 'Update Category', 'vfwp' ),
      'add_new_item'      => __( 'Add New Category', 'vfwp' ),
      'new_item_name'     => __( 'New Category Name', 'vfwp' ),
      'menu_name'         => __( 'Categories', 'vfwp' ),
    );
  }

    /**
 * Action: `init`
 */
add_action(
    'init',
    'vf_wp_training_taxonomies__init'
  );
  
  function vf_wp_training_taxonomies__init() {
  
    register_taxonomy('training-category', array('training-category'), array(
      'labels'             => vf_wp_training_category_labels(),
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