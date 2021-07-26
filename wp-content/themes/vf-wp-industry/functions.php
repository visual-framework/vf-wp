<?php
require_once('functions/custom-taxonomies.php');

// Register Events post type

  /**
   * Action: `init`
   * Register the custom post type
   */

add_action(
  'init',
  'industry_event_init_register'
);

  function industry_event_init_register() {

    register_post_type('industry_event', array(
        'labels'              => industry_event_get_labels(),
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
        'rest_base'           => "industry_event",
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-calendar',
        'capability_type'     => 'page',
        'supports'            => array('title', 'editor', 'page-attributes', 'excerpt'),
        'has_archive'         => true,
        'rewrite'             => array(
          'slug' => '/private/events'
        ),
        'query_var'           => true,
        'can_export'          => true,
        'delete_with_user'    => false,
        'taxonomies'          => array(
            'type',
          ),    
        ));

  }
  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  function industry_event_get_labels() {
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

  // enable featured image
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// CHILD THEME CSS FILE

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

	$parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
	get_stylesheet_directory_uri() . '/style.css',
	array( $parent_style ),
	wp_get_theme()->get('Version')
);
}

?>
