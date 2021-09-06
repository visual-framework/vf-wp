<?php


  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'document_init_register'
);

  function document_init_register() {

    register_post_type('documents', array(
      'labels'              => document_get_labels(),
      'description'         => __('Documents', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "documents",
      'menu_icon'           => 'dashicons-open-folder',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'documents'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'embl-location',
        'post_tag'
  
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function document_get_labels() {
    return array(
      'name'                     => _x( 'Documents', 'Document type general name', 'vfwp' ),
      'singular_name'            => _x( 'Document', 'Document type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Document', 'vfwp' ),
      'add_new_item'             => __( 'Add New Document', 'vfwp' ),
      'edit_item'                => __( 'Edit Document', 'vfwp' ),
      'new_item'                 => __( 'New Document', 'vfwp' ),
      'view_item'                => __( 'View Document', 'vfwp' ),
      'view_items'               => __( 'View Documents', 'vfwp' ),
      'search_items'             => __( 'Search Documents', 'vfwp' ),
      'not_found'                => __( 'No Documents found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Documents found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Documents', 'vfwp' ),
      'archives'                 => __( 'Document Archives', 'vfwp' ),
      'attributes'               => __( 'Document Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Document', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Document', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Document', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Document', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Document', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Document', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Documents list', 'vfwp' ),
      'items_list_navigation'    => __( 'Documents list navigation', 'vfwp' ),
      'items_list'               => __( 'Documents list', 'vfwp' ),
      'item_published'           => __( 'Document published.', 'vfwp' ),
      'item_published_privately' => __( 'Document published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Document reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Document scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Document updated.', 'vfwp' ),
    );
  }

  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'insites_init_register'
);

  function insites_init_register() {

    register_post_type('insites', array(
      'labels'              => insites_get_labels(),
      'description'         => __('INsites', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "insites",
      'menu_icon'           => 'dashicons-text-page',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'insites'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'embl-location',
        'post_tag',
        'topic'
  
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function insites_get_labels() {
    return array(
      'name'                     => _x( 'INsites', 'INsite type general name', 'vfwp' ),
      'singular_name'            => _x( 'INsite', 'INsite type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'INsite', 'vfwp' ),
      'add_new_item'             => __( 'Add New INsite', 'vfwp' ),
      'edit_item'                => __( 'Edit INsite', 'vfwp' ),
      'new_item'                 => __( 'New INsite', 'vfwp' ),
      'view_item'                => __( 'View INsite', 'vfwp' ),
      'view_items'               => __( 'View INsites', 'vfwp' ),
      'search_items'             => __( 'Search INsites', 'vfwp' ),
      'not_found'                => __( 'No INsites found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No INsites found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All INsites', 'vfwp' ),
      'archives'                 => __( 'INsite Archives', 'vfwp' ),
      'attributes'               => __( 'INsite Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into INsite', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this INsite', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'INsite', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'INsite', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'INsite', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'INsite', 'vfwp' ),
      'filter_items_list'        => __( 'Filter INsites list', 'vfwp' ),
      'items_list_navigation'    => __( 'INsites list navigation', 'vfwp' ),
      'items_list'               => __( 'INsites list', 'vfwp' ),
      'item_published'           => __( 'INsite published.', 'vfwp' ),
      'item_published_privately' => __( 'INsite published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'INsite reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'INsite scheduled.', 'vfwp' ),
      'item_updated'             => __( 'INsite updated.', 'vfwp' ),
    );
  }



/**
 * Action: `init`
 */
add_action(
  'init',
  'vf_wp_intranet_blog__init'
);

/**
 * Reference: `get_post_type_labels`
 * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
 */
function get_blog_labels() {
  return array(
    'name'                     => _x( 'Community blog', 'post type general name', 'vfwp' ),
    'singular_name'            => _x( 'Post', 'post type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'Post', 'vfwp' ),
    'add_new_item'             => __( 'Add New Post', 'vfwp' ),
    'edit_item'                => __( 'Edit Post', 'vfwp' ),
    'new_item'                 => __( 'New Post', 'vfwp' ),
    'view_item'                => __( 'View Post', 'vfwp' ),
    'view_items'               => __( 'View Posts', 'vfwp' ),
    'search_items'             => __( 'Search Posts', 'vfwp' ),
    'not_found'                => __( 'No Posts found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No Posts found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Posts', 'vfwp' ),
    'archives'                 => __( 'Post Archives', 'vfwp' ),
    'attributes'               => __( 'Post Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into Post', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this post', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'post', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'post', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'post', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'post', 'vfwp' ),
    'filter_items_list'        => __( 'Filter posts list', 'vfwp' ),
    'items_list_navigation'    => __( 'Posts list navigation', 'vfwp' ),
    'items_list'               => __( 'Posts list', 'vfwp' ),
    'item_published'           => __( 'Post published.', 'vfwp' ),
    'item_published_privately' => __( 'Post published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Post reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Post scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Post updated.', 'vfwp' ),
  );
}


function vf_wp_intranet_blog__init() {

  register_post_type('community-blog', array(
    'labels'              => get_blog_labels(),
    'description'         => __('Community blog', 'vfwp'),
    'public'              => true,
    'hierarchical'        => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'rest_base'           => 'blog',
    'menu_position'       => 20,
    'menu_icon'           => 'dashicons-groups',
    'capability_type'     => 'page',
    'supports'            => array('title', 'editor', 'revisions', 'trackbacks', 'author', 'excerpt', 'thumbnail'),
    'has_archive'         => true,
    'rewrite'             => array(
      'slug'       => 'community-blog',
      'with_front' => false
    ),
    'query_var'           => true,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'embl-location',
      'post_tag',
      'topic'
    ),
  ));
  
}  

/**
 * Action: `init`
 */
add_action(
  'init',
  'vf_wp_intranet_events__init'
);

/**
 * Reference: `get_post_type_labels`
 * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
 */
function get_intranet_events_labels() {
  return array(
    'name'                     => _x( 'Events', 'event type general name', 'vfwp' ),
    'singular_name'            => _x( 'Event', 'event type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'event', 'vfwp' ),
    'add_new_item'             => __( 'Add New Event', 'vfwp' ),
    'edit_item'                => __( 'Edit Event', 'vfwp' ),
    'new_item'                 => __( 'New Event', 'vfwp' ),
    'view_item'                => __( 'View Event', 'vfwp' ),
    'view_items'               => __( 'View Events', 'vfwp' ),
    'search_items'             => __( 'Search Events', 'vfwp' ),
    'not_found'                => __( 'No events found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No events found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Events', 'vfwp' ),
    'archives'                 => __( 'Event Archives', 'vfwp' ),
    'attributes'               => __( 'Event Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into event', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this event', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'event', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'event', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'event', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'event', 'vfwp' ),
    'filter_items_list'        => __( 'Filter events list', 'vfwp' ),
    'items_list_navigation'    => __( 'Events list navigation', 'vfwp' ),
    'items_list'               => __( 'Events list', 'vfwp' ),
    'item_published'           => __( 'Event published.', 'vfwp' ),
    'item_published_privately' => __( 'Event published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Event reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Event scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Event updated.', 'vfwp' ),
);
}


function vf_wp_intranet_events__init() {

  register_post_type('events', array(
    'labels'              => get_intranet_events_labels(),
    'description'         => __('Events', 'vfwp'),
    'public'              => true,
    'hierarchical'        => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'rest_base'           => 'events',
    'menu_position'       => 20,
    'menu_icon'           => 'dashicons-calendar',
    'capability_type'     => 'page',
    'supports'            => array('title', 'editor', 'page-attributes', 'excerpt'),
    'has_archive'         => true,
    'rewrite'             => array(
      'slug' => 'events'
    ),
    'query_var'           => true,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'embl-location',
      'post_tag',
      'topic'
    ),
  ));
  
}  

?>
