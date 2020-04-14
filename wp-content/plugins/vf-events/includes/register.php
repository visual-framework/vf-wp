<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Events_Register') ) :

class VF_Events_Register {

  function __construct() {
    add_action(
      'init',
      array($this, 'init_register')
    );
  }

  /**
   * Action: `init`
   * Register the custom post type
   */
  public function init_register() {
    register_post_type('vf_event', array(
      'labels'              => $this->get_labels(),
      'description'         => __('Events', 'vfwp'),
      'public'              => true,
      'hierarchical'        => false,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-calendar',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor'),
      'has_archive'         => true,
      'rewrite'             => array(
        'slug' => 'events'
      ),
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
      'taxonomies'          => array(
        'embl_taxonomy'
      ),
    ));
  }

  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  public function get_labels() {
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

} // VF_Events_Register

endif;

?>
