<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'ambassadors_init_register'
);

  function ambassadors_init_register() {

    register_post_type('ambassadors', array(
      'labels'              => ambassadors_get_labels(),
      'description'         => __('Ambassadors', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "ambassadors",
      'menu_icon'           => 'dashicons-groups',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'ambassadors'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'country'
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function ambassadors_get_labels() {
    return array(
      'name'                     => _x( 'Ambassadors', 'Ambassador type general name', 'vfwp' ),
      'singular_name'            => _x( 'Ambassador', 'Ambassador type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Ambassador', 'vfwp' ),
      'add_new_item'             => __( 'Add New Ambassador', 'vfwp' ),
      'edit_item'                => __( 'Edit Ambassador', 'vfwp' ),
      'new_item'                 => __( 'New Ambassador', 'vfwp' ),
      'view_item'                => __( 'View Ambassador', 'vfwp' ),
      'view_items'               => __( 'View Ambassadors', 'vfwp' ),
      'search_items'             => __( 'Search Ambassadors', 'vfwp' ),
      'not_found'                => __( 'No Ambassadors found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Ambassadors found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Ambassadors', 'vfwp' ),
      'archives'                 => __( 'Ambassador Archives', 'vfwp' ),
      'attributes'               => __( 'Ambassador Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Ambassador', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Ambassador', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Ambassador', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Ambassador', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Ambassador', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Ambassador', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Ambassadors list', 'vfwp' ),
      'items_list_navigation'    => __( 'Ambassadors list navigation', 'vfwp' ),
      'items_list'               => __( 'Ambassadors list', 'vfwp' ),
      'item_published'           => __( 'Ambassador published.', 'vfwp' ),
      'item_published_privately' => __( 'Ambassador published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Ambassador reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Ambassador scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Ambassador updated.', 'vfwp' ),
    );
  }



?>
