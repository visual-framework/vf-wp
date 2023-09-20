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
      'public'              => false,
      'hierarchical'        => false,
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
      'query_var'           => false,
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
      'public'              => false,
      'hierarchical'        => false,
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
      'query_var'           => false,
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
      'public'              => false,
      'hierarchical'        => false,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => "news",
      'menu_position'       => 22,
      'menu_icon'           => 'dashicons-text-page',
      'capability_type'     => 'page',
      'supports'            => array('title', 'author', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'news'
      ),
      'query_var'           => false,
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
    'name'                     => _x( 'Updates', 'post type general name', 'vfwp' ),
    'singular_name'            => _x( 'Update', 'post type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'Update', 'vfwp' ),
    'add_new_item'             => __( 'Add New Update', 'vfwp' ),
    'edit_item'                => __( 'Edit Update', 'vfwp' ),
    'new_item'                 => __( 'New Update', 'vfwp' ),
    'view_item'                => __( 'View Update', 'vfwp' ),
    'view_items'               => __( 'View Updates', 'vfwp' ),
    'search_items'             => __( 'Search Updates', 'vfwp' ),
    'not_found'                => __( 'No Updates found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No Updates found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Updates', 'vfwp' ),
    'archives'                 => __( 'Update Archives', 'vfwp' ),
    'attributes'               => __( 'Update Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into Update', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this Update', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'Update', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'Update', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'Update', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'Update', 'vfwp' ),
    'filter_items_list'        => __( 'Filter Updates list', 'vfwp' ),
    'items_list_navigation'    => __( 'Updates list navigation', 'vfwp' ),
    'items_list'               => __( 'Updates list', 'vfwp' ),
    'item_published'           => __( 'Update published.', 'vfwp' ),
    'item_published_privately' => __( 'Update published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Update reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Update scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Update updated.', 'vfwp' ),
  );
}


function vf_wp_intranet_blog__init() {

  register_post_type('community-blog', array(
    'labels'              => get_blog_labels(),
    'description'         => __('Updates', 'vfwp'),
    'public'              => false,
    'hierarchical'        => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'rest_base'           => 'blog',
    'menu_position'       => 23,
    'menu_icon'           => 'dashicons-groups',
    'capability_type'     => 'page',
    'supports'            => array('title', 'editor', 'revisions', 'author', 'excerpt', 'thumbnail'),
    'has_archive'         => 'updates',
    'rewrite'             => array(
      'slug'       => 'updates',
      'with_front' => false
    ),
    'query_var'           => false,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'embl-location',
      'post_tag',
      'updates-topic'
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
    'menu_position'       => 25,
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

/**
 * Action: `init`
 */
add_action(
  'init',
  'vf_wp_intranet_teams__init'
);

/**
 * Reference: `get_post_type_labels`
 * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
 */
function get_intranet_teams_labels() {
  return array(
    'name'                     => _x( 'Teams', 'Team type general name', 'vfwp' ),
    'singular_name'            => _x( 'Team', 'Team type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'Team', 'vfwp' ),
    'add_new_item'             => __( 'Add New Team', 'vfwp' ),
    'edit_item'                => __( 'Edit Team', 'vfwp' ),
    'new_item'                 => __( 'New Team', 'vfwp' ),
    'view_item'                => __( 'View Team', 'vfwp' ),
    'view_items'               => __( 'View Teams', 'vfwp' ),
    'search_items'             => __( 'Search Teams', 'vfwp' ),
    'not_found'                => __( 'No Teams found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No Teams found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Teams', 'vfwp' ),
    'archives'                 => __( 'Team Archives', 'vfwp' ),
    'attributes'               => __( 'Team Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into Team', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this Team', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'Team', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'Team', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'Team', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'Team', 'vfwp' ),
    'filter_items_list'        => __( 'Filter Teams list', 'vfwp' ),
    'items_list_navigation'    => __( 'Teams list navigation', 'vfwp' ),
    'items_list'               => __( 'Teams list', 'vfwp' ),
    'item_published'           => __( 'Team published.', 'vfwp' ),
    'item_published_privately' => __( 'Team published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Team reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Team scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Team updated.', 'vfwp' ),
);
}


function vf_wp_intranet_teams__init() {

  register_post_type('teams', array(
    'labels'              => get_intranet_teams_labels(),
    'description'         => __('Team', 'vfwp'),
    'public'              => false,
    'hierarchical'        => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'rest_base'           => 'external',
    'menu_position'       => 21,
    'menu_icon'           => 'dashicons-external',
    'capability_type'     => 'page',
    'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'revisions', 'thumbnail'),
    'has_archive'         => true,
    'rewrite'             => array(
      'slug' => 'external'
    ),
    'query_var'           => false,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'post_tag',
    ),
  ));
  
}  

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
        'public'              => false,
        'hierarchical'        => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'rest_base'           => "training",
        'menu_icon'           => 'dashicons-book-alt',
        'capability_type'     => 'page',
        'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
        'has_archive'         => true,
        'rewrite'             => array(
          'slug' => 'training'
        ),
        'query_var'           => false,
        'can_export'          => true,
        'delete_with_user'    => false,
        'taxonomies'          => array(
          'training-organiser',
          'event-location',
    
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
