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
      'rest_base'           => "teacher-training",
      'menu_icon'           => 'dashicons-edit-page',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => 'teacher-training',
      'rewrite'             => array(
        'slug' => 'teacher-training'
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
      'name'                     => _x( 'Teacher training', 'Teacher training type general name', 'vfwp' ),
      'singular_name'            => _x( 'Teacher training', 'Teacher training type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Teacher training', 'vfwp' ),
      'add_new_item'             => __( 'Add New Teacher training', 'vfwp' ),
      'edit_item'                => __( 'Edit Teacher training', 'vfwp' ),
      'new_item'                 => __( 'New Teacher training', 'vfwp' ),
      'view_item'                => __( 'View Teacher training', 'vfwp' ),
      'view_items'               => __( 'View Teacher training', 'vfwp' ),
      'search_items'             => __( 'Search Teacher training', 'vfwp' ),
      'not_found'                => __( 'No Teacher training found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Teacher training found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Teacher training', 'vfwp' ),
      'archives'                 => __( 'Teacher training Archives', 'vfwp' ),
      'attributes'               => __( 'Teacher training Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Teacher training', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Teacher training', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Teacher training', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Teacher training', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Teacher training', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Teacher training', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Teacher training list', 'vfwp' ),
      'items_list_navigation'    => __( 'Teacher training list navigation', 'vfwp' ),
      'items_list'               => __( 'Teacher training list', 'vfwp' ),
      'item_published'           => __( 'Teacher training published.', 'vfwp' ),
      'item_published_privately' => __( 'Teacher training published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Teacher training reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Teacher training scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Teacher training updated.', 'vfwp' ),
    );
  }



?>
