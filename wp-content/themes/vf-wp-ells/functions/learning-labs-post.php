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

    register_post_type('llabs-event', array(
      'labels'              => llabs_get_labels(),
      'description'         => __('LLabs Events', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "llabs-event",
      'menu_icon'           => 'dashicons-edit-page',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'llabs-event'
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
      'name'                     => _x( 'LLabs Events', 'LLabs event type general name', 'vfwp' ),
      'singular_name'            => _x( 'LLabs Event', 'LLabs event type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'LLabs event', 'vfwp' ),
      'add_new_item'             => __( 'Add New LLabs Event', 'vfwp' ),
      'edit_item'                => __( 'Edit LLabs Event', 'vfwp' ),
      'new_item'                 => __( 'New LLabs Event', 'vfwp' ),
      'view_item'                => __( 'View LLabs Event', 'vfwp' ),
      'view_items'               => __( 'View LLabs Events', 'vfwp' ),
      'search_items'             => __( 'Search LLabs Events', 'vfwp' ),
      'not_found'                => __( 'No LLabs events found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No LLabs events found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All LLabs Events', 'vfwp' ),
      'archives'                 => __( 'LLabs Event Archives', 'vfwp' ),
      'attributes'               => __( 'LLabs Event Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into LLabs event', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this LLabs event', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'LLabs event', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'LLabs event', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'LLabs event', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'LLabs event', 'vfwp' ),
      'filter_items_list'        => __( 'Filter LLabs events list', 'vfwp' ),
      'items_list_navigation'    => __( 'LLabs Events list navigation', 'vfwp' ),
      'items_list'               => __( 'LLabs Events list', 'vfwp' ),
      'item_published'           => __( 'LLabs Event published.', 'vfwp' ),
      'item_published_privately' => __( 'LLabs Event published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'LLabs Event reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'LLabs Event scheduled.', 'vfwp' ),
      'item_updated'             => __( 'LLabs Event updated.', 'vfwp' ),
    );
  }



?>
