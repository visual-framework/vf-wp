<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'teachingbase_init_register'
);

  function teachingbase_init_register() {

    register_post_type('teachingbase', array(
      'labels'              => teachingbase_get_labels(),
      'description'         => __('TeachingBASE', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "teachingbase",
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-welcome-learn-more',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'teachingbase'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'category',
        'post_tag'
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function teachingbase_get_labels() {
    return array(
      'name'                     => _x( 'TeachingBASEs', 'TeachingBASE type general name', 'vfwp' ),
      'singular_name'            => _x( 'TeachingBASE', 'TeachingBASE type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'TeachingBASE', 'vfwp' ),
      'add_new_item'             => __( 'Add New TeachingBASE', 'vfwp' ),
      'edit_item'                => __( 'Edit TeachingBASE', 'vfwp' ),
      'new_item'                 => __( 'New TeachingBASE', 'vfwp' ),
      'view_item'                => __( 'View TeachingBASE', 'vfwp' ),
      'view_items'               => __( 'View TeachingBASEs', 'vfwp' ),
      'search_items'             => __( 'Search TeachingBASEs', 'vfwp' ),
      'not_found'                => __( 'No TeachingBASEs found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No TeachingBASEs found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All TeachingBASEs', 'vfwp' ),
      'archives'                 => __( 'TeachingBASE Archives', 'vfwp' ),
      'attributes'               => __( 'TeachingBASE Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into TeachingBASE', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this TeachingBASE', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'TeachingBASE', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'TeachingBASE', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'TeachingBASE', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'TeachingBASE', 'vfwp' ),
      'filter_items_list'        => __( 'Filter TeachingBASEs list', 'vfwp' ),
      'items_list_navigation'    => __( 'TeachingBASEs list navigation', 'vfwp' ),
      'items_list'               => __( 'TeachingBASEs list', 'vfwp' ),
      'item_published'           => __( 'TeachingBASE published.', 'vfwp' ),
      'item_published_privately' => __( 'TeachingBASE published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'TeachingBASE reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'TeachingBASE scheduled.', 'vfwp' ),
      'item_updated'             => __( 'TeachingBASE updated.', 'vfwp' ),
    );
  }



?>
