<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'training_init_register'
);

  function training_init_register() {

    register_post_type('training', array(
      'labels'              => training_get_labels(),
      'description'         => __('Training', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "training",
      'menu_icon'           => 'dashicons-open-folder',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'training'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'post_tag',
        'training-category'
  
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function training_get_labels() {
    return array(
      'name'                     => _x( 'Training', 'Training type general name', 'vfwp' ),
      'singular_name'            => _x( 'Training', 'Training type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Training', 'vfwp' ),
      'add_new_item'             => __( 'Add New Training', 'vfwp' ),
      'edit_item'                => __( 'Edit Training', 'vfwp' ),
      'new_item'                 => __( 'New Training', 'vfwp' ),
      'view_item'                => __( 'View Training', 'vfwp' ),
      'view_items'               => __( 'View Trainings', 'vfwp' ),
      'search_items'             => __( 'Search Trainings', 'vfwp' ),
      'not_found'                => __( 'No Trainings found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Trainings found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Trainings', 'vfwp' ),
      'archives'                 => __( 'Training Archives', 'vfwp' ),
      'attributes'               => __( 'Training Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Training', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Training', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Training', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Training', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Training', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Training', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Trainings list', 'vfwp' ),
      'items_list_navigation'    => __( 'Trainings list navigation', 'vfwp' ),
      'items_list'               => __( 'Trainings list', 'vfwp' ),
      'item_published'           => __( 'Training published.', 'vfwp' ),
      'item_published_privately' => __( 'Training published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Training reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Training scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Training updated.', 'vfwp' ),
    );
  }

  ?>