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
  'peopleinit_register'
);

  function peopleinit_register() {

    register_post_type('people', array(
      'labels'              => people_get_labels(),
      'description'         => __('People', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "people",
      'menu_icon'           => 'dashicons-id-alt',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'people'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(  
      ),
    ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function people_get_labels() {
    return array(
      'name'                     => _x( 'People', 'Person type general name', 'vfwp' ),
      'singular_name'            => _x( 'Person', 'Person type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Person', 'vfwp' ),
      'add_new_item'             => __( 'Add New Person', 'vfwp' ),
      'edit_item'                => __( 'Edit Person', 'vfwp' ),
      'new_item'                 => __( 'New Person', 'vfwp' ),
      'view_item'                => __( 'View Person', 'vfwp' ),
      'view_items'               => __( 'View People', 'vfwp' ),
      'search_items'             => __( 'Search People', 'vfwp' ),
      'not_found'                => __( 'No People found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No People found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All People', 'vfwp' ),
      'archives'                 => __( 'Person Archives', 'vfwp' ),
      'attributes'               => __( 'Person Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Person', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Person', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Person', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Person', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Person', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Person', 'vfwp' ),
      'filter_items_list'        => __( 'Filter People list', 'vfwp' ),
      'items_list_navigation'    => __( 'People list navigation', 'vfwp' ),
      'items_list'               => __( 'People list', 'vfwp' ),
      'item_published'           => __( 'Person published.', 'vfwp' ),
      'item_published_privately' => __( 'Person published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Person reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Person scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Person updated.', 'vfwp' ),
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
      'description'         => __('Internal news', 'vfwp'),
      'public'              => true,
      'hierarchical'        => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "news",
      'menu_icon'           => 'dashicons-text-page',
      'capability_type'     => 'page',
      'supports'            => array('title', 'author', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'news'
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
      'name'                     => _x( 'Internal news', 'Internal news type general name', 'vfwp' ),
      'singular_name'            => _x( 'Internal news', 'Internal news type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'Internal news', 'vfwp' ),
      'add_new_item'             => __( 'Add New Internal news', 'vfwp' ),
      'edit_item'                => __( 'Edit Internal news', 'vfwp' ),
      'new_item'                 => __( 'New Internal news', 'vfwp' ),
      'view_item'                => __( 'View Internal news', 'vfwp' ),
      'view_items'               => __( 'View Internal news', 'vfwp' ),
      'search_items'             => __( 'Search Internal news', 'vfwp' ),
      'not_found'                => __( 'No Internal news found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No Internal news found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Internal news', 'vfwp' ),
      'archives'                 => __( 'Internal news Archives', 'vfwp' ),
      'attributes'               => __( 'Internal news Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into Internal news', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Internal news', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'Internal news', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'Internal news', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'Internal news', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'Internal news', 'vfwp' ),
      'filter_items_list'        => __( 'Filter Internal news list', 'vfwp' ),
      'items_list_navigation'    => __( 'Internal news list navigation', 'vfwp' ),
      'items_list'               => __( 'Internal news list', 'vfwp' ),
      'item_published'           => __( 'Internal news published.', 'vfwp' ),
      'item_published_privately' => __( 'Internal news published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Internal news reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Internal news scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Internal news updated.', 'vfwp' ),
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
    'name'                     => _x( 'Important updates', 'post type general name', 'vfwp' ),
    'singular_name'            => _x( 'Important update', 'post type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'Important update', 'vfwp' ),
    'add_new_item'             => __( 'Add New Important update', 'vfwp' ),
    'edit_item'                => __( 'Edit Important update', 'vfwp' ),
    'new_item'                 => __( 'New Important update', 'vfwp' ),
    'view_item'                => __( 'View Important update', 'vfwp' ),
    'view_items'               => __( 'View Important updates', 'vfwp' ),
    'search_items'             => __( 'Search Important updates', 'vfwp' ),
    'not_found'                => __( 'No Important updates found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No Important updates found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Important updates', 'vfwp' ),
    'archives'                 => __( 'Important update Archives', 'vfwp' ),
    'attributes'               => __( 'Important update Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into Important update', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this Important update', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'Important update', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'Important update', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'Important update', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'Important update', 'vfwp' ),
    'filter_items_list'        => __( 'Filter Important updates list', 'vfwp' ),
    'items_list_navigation'    => __( 'Important updates list navigation', 'vfwp' ),
    'items_list'               => __( 'Important updates list', 'vfwp' ),
    'item_published'           => __( 'Important update published.', 'vfwp' ),
    'item_published_privately' => __( 'Important update published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Important update reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Important update scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Important update updated.', 'vfwp' ),
  );
}


function vf_wp_intranet_blog__init() {

  register_post_type('community-blog', array(
    'labels'              => get_blog_labels(),
    'description'         => __('Important updates', 'vfwp'),
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
    'supports'            => array('title', 'editor', 'revisions', 'author', 'excerpt', 'thumbnail'),
    'has_archive'         => 'updates',
    'rewrite'             => array(
      'slug'       => 'updates',
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
    'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'revisions', 'thumbnail'),
    'has_archive'         => true,
    'rewrite'             => array(
      'slug' => 'events'
    ),
    'query_var'           => true,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'event-location',
      'post_tag',
      'topic'
    ),
  ));
  
}  

?>
