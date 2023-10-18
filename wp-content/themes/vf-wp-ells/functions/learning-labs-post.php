<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'llabs_init_register'
);

  function llabs_init_register() {

    register_post_type('learninglab', array(
      'labels'              => llabs_get_labels(),
      'description'         => __('Teacher training', 'vfwp'),
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
      'menu_icon'           => 'dashicons-edit-page',
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
        'topic-area',
        'llabs-type',
        'llabs-format',
        'llabs-location'
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function llabs_get_labels() {
    return array(
      'name'                     => _x( 'LearningLabs', 'LearningLab type general name', 'vfwp' ),
      'singular_name'            => _x( 'LearningLab', 'LearningLab type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'LearningLab', 'vfwp' ),
      'add_new_item'             => __( 'Add New LearningLab', 'vfwp' ),
      'edit_item'                => __( 'Edit LearningLab', 'vfwp' ),
      'new_item'                 => __( 'New LearningLab', 'vfwp' ),
      'view_item'                => __( 'View LearningLab', 'vfwp' ),
      'view_items'               => __( 'View LearningLabs', 'vfwp' ),
      'search_items'             => __( 'Search LearningLabs', 'vfwp' ),
      'not_found'                => __( 'No LearningLabs found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No LearningLabs found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All LearningLabs', 'vfwp' ),
      'archives'                 => __( 'LearningLab Archives', 'vfwp' ),
      'attributes'               => __( 'LearningLab Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into LearningLab', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this LearningLab', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'LearningLab', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'LearningLab', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'LearningLab', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'LearningLab', 'vfwp' ),
      'filter_items_list'        => __( 'Filter LearningLabs list', 'vfwp' ),
      'items_list_navigation'    => __( 'LearningLabs list navigation', 'vfwp' ),
      'items_list'               => __( 'LearningLabs list', 'vfwp' ),
      'item_published'           => __( 'LearningLab published.', 'vfwp' ),
      'item_published_privately' => __( 'LearningLab published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'LearningLab reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'LearningLab scheduled.', 'vfwp' ),
      'item_updated'             => __( 'LearningLab updated.', 'vfwp' ),
    );
  }



?>
