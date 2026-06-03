<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'lecture_init_register'
);

  function lecture_init_register() {

    register_post_type('insight-lecture', array(
      'labels'              => lecture_get_labels(),
      'description'         => __('Insight Lecture', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "insight-lecture",
      'menu_icon'           => 'dashicons-book-alt',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'insight-lecture'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'age-group',
        'topic-area'
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function lecture_get_labels() {
    return array(
      'name'                     => _x( 'Insight Lectures', 'Insight Lecture type general name', 'vfwp' ),
      'singular_name'            => _x( 'Insight Lecture', 'Insight Lecture type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Insight Lecture', 'vfwp' ),
      'add_new_item'             => __( 'Add New Insight Lecture', 'vfwp' ),
      'edit_item'                => __( 'Edit Insight Lecture', 'vfwp' ),
      'new_item'                 => __( 'New Insight Lecture', 'vfwp' ),
      'view_item'                => __( 'View Insight Lecture', 'vfwp' ),
      'view_items'               => __( 'View Insight Lectures', 'vfwp' ),
      'search_items'             => __( 'Search Insight Lectures', 'vfwp' ),
      'not_found'                => __( 'No Insight Lectures found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Insight Lectures found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Insight Lectures', 'vfwp' ),
      'archives'                 => __( 'Insight Lecture Archives', 'vfwp' ),
      'attributes'               => __( 'Insight Lecture Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Insight Lecture', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Insight Lecture', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Insight Lecture', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Insight Lecture', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Insight Lecture', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Insight Lecture', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Insight Lectures list', 'vfwp' ),
      'items_list_navigation'    => __( 'Insight Lectures list navigation', 'vfwp' ),
      'items_list'               => __( 'Insight Lectures list', 'vfwp' ),
      'item_published'           => __( 'Insight Lecture published.', 'vfwp' ),
      'item_published_privately' => __( 'Insight Lecture published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Insight Lecture reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Insight Lecture scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Insight Lecture updated.', 'vfwp' ),
    );
  }



?>
