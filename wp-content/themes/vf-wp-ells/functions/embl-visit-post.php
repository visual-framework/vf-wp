<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'visit_init_register'
);

  function visit_init_register() {

    register_post_type('embl-visit', array(
      'labels'              => visit_get_labels(),
      'description'         => __('EMBL Visit', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "embl-visit",
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-location-alt',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'embl-visit'
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
  function visit_get_labels() {
    return array(
      'name'                     => _x( 'EMBL Visits', 'EMBL Visit type general name', 'vfwp' ),
      'singular_name'            => _x( 'EMBL Visit', 'EMBL Visit type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'EMBL Visit', 'vfwp' ),
      'add_new_item'             => __( 'Add New EMBL Visit', 'vfwp' ),
      'edit_item'                => __( 'Edit EMBL Visit', 'vfwp' ),
      'new_item'                 => __( 'New EMBL Visit', 'vfwp' ),
      'view_item'                => __( 'View EMBL Visit', 'vfwp' ),
      'view_items'               => __( 'View EMBL Visits', 'vfwp' ),
      'search_items'             => __( 'Search EMBL Visits', 'vfwp' ),
      'not_found'                => __( 'No EMBL Visits found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No EMBL Visits found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All EMBL Visits', 'vfwp' ),
      'archives'                 => __( 'EMBL Visit Archives', 'vfwp' ),
      'attributes'               => __( 'EMBL Visit Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into EMBL Visit', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this EMBL Visit', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'EMBL Visit', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'EMBL Visit', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'EMBL Visit', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'EMBL Visit', 'vfwp' ),
      'filter_items_list'        => __( 'Filter EMBL Visits list', 'vfwp' ),
      'items_list_navigation'    => __( 'EMBL Visits list navigation', 'vfwp' ),
      'items_list'               => __( 'EMBL Visits list', 'vfwp' ),
      'item_published'           => __( 'EMBL Visit published.', 'vfwp' ),
      'item_published_privately' => __( 'EMBL Visit published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'EMBL Visit reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'EMBL Visit scheduled.', 'vfwp' ),
      'item_updated'             => __( 'EMBL Visit updated.', 'vfwp' ),
    );
  }



?>
