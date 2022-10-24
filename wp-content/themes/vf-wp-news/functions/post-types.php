<?php
// ARCHIVES PAGE
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'archives',
        array(
            'labels' => array(
                'name' => __( 'archives' ),
                'singular_name' => __( 'archives' )
            ),
        'public' => true,
        'has_archive' => true,
        'show_in_menu'   => false,
        )
    );
}

  /**
   * Action: `init`
   * Register the custom post type
   */

  add_action(
    'init',
    'embletc_init_register'
  );
  
    function embletc_init_register() {
  
      register_post_type('EMBLetc', array(
        'labels'              => embletc_get_labels(),
        'description'         => __('EMBLetc Articles', 'vfwp'),
        'public'              => true,
        'hierarchical'        => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'rest_base'           => "embletc",
        'menu_icon'           => 'dashicons-star-filled',
        'capability_type'     => 'page',
        'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail', 'author'),
        'has_archive'         => false,
        'rewrite'             => array(
          'slug' => 'embl-etc'
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
    function embletc_get_labels() {
      return array(
        'name'                     => _x( 'EMBLetc Articles', 'EMBLetc type general name', 'vfwp' ),
        'singular_name'            => _x( 'EMBLetc', 'EMBLetc type singular name', 'vfwp' ),
        'add_new'                  => _x( 'Add New', 'EMBLetc', 'vfwp' ),
        'add_new_item'             => __( 'Add New EMBLetc', 'vfwp' ),
        'edit_item'                => __( 'Edit EMBLetc', 'vfwp' ),
        'new_item'                 => __( 'New EMBLetc', 'vfwp' ),
        'view_item'                => __( 'View EMBLetc', 'vfwp' ),
        'view_items'               => __( 'View EMBLetc', 'vfwp' ),
        'search_items'             => __( 'Search EMBLetc', 'vfwp' ),
        'not_found'                => __( 'No EMBLetc found.', 'vfwp' ),
        'not_found_in_trash'       => __( 'No EMBLetc found in Trash.', 'vfwp' ),
        'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
        'all_items'                => __( 'All EMBLetc', 'vfwp' ),
        'archives'                 => __( 'EMBLetc Archives', 'vfwp' ),
        'attributes'               => __( 'EMBLetc Attributes', 'vfwp' ),
        'insert_into_item'         => __( 'Insert into EMBLetc', 'vfwp' ),
        'uploaded_to_this_item'    => __( 'Uploaded to this EMBLetc', 'vfwp' ),
        'featured_image'           => _x( 'Featured image', 'EMBLetc', 'vfwp' ),
        'set_featured_image'       => _x( 'Set featured image', 'EMBLetc', 'vfwp' ),
        'remove_featured_image'    => _x( 'Remove featured image', 'EMBLetc', 'vfwp' ),
        'use_featured_image'       => _x( 'Use as featured image', 'EMBLetc', 'vfwp' ),
        'filter_items_list'        => __( 'Filter EMBLetc list', 'vfwp' ),
        'items_list_navigation'    => __( 'EMBLetc list navigation', 'vfwp' ),
        'items_list'               => __( 'EMBLetc list', 'vfwp' ),
        'item_published'           => __( 'EMBLetc published.', 'vfwp' ),
        'item_published_privately' => __( 'EMBLetc published privately.', 'vfwp' ),
        'item_reverted_to_draft'   => __( 'EMBLetc reverted to draft.', 'vfwp' ),
        'item_scheduled'           => __( 'EMBLetc scheduled.', 'vfwp' ),
        'item_updated'             => __( 'EMBLetc updated.', 'vfwp' ),
      );
    }


    // Issue post tyoe
  add_action(
    'init',
    'embletc_issue_init_register'
  );
  
    function embletc_issue_init_register() {
  
      register_post_type('embletc-issue', array(
        'labels'              => embletc_issue_get_labels(),
        'description'         => __('EMBLetc Issue', 'vfwp'),
        'public'              => true,
        'hierarchical'        => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'rest_base'           => "issue",
        'menu_icon'           => 'dashicons-star-filled',
        'capability_type'     => 'page',
        'supports'            => array('title', 'editor', 'page-attributes', 'excerpt', 'thumbnail'),
        'has_archive'         => true,
        'rewrite'             => array(
          'slug' => 'embletc'
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
    function embletc_issue_get_labels() {
      return array(
        'name'                     => _x( 'EMBLetc Issue', 'EMBLetc Issue type general name', 'vfwp' ),
        'singular_name'            => _x( 'EMBLetc Issue', 'EMBLetc Issue type singular name', 'vfwp' ),
        'add_new'                  => _x( 'Add New', 'EMBLetc Issue', 'vfwp' ),
        'add_new_item'             => __( 'Add New EMBLetc Issue', 'vfwp' ),
        'edit_item'                => __( 'Edit EMBLetc Issue', 'vfwp' ),
        'new_item'                 => __( 'New EMBLetc Issue', 'vfwp' ),
        'view_item'                => __( 'View EMBLetc Issue', 'vfwp' ),
        'view_items'               => __( 'View EMBLetc Issue', 'vfwp' ),
        'search_items'             => __( 'Search EMBLetc Issue', 'vfwp' ),
        'not_found'                => __( 'No EMBLetc Issue found.', 'vfwp' ),
        'not_found_in_trash'       => __( 'No EMBLetc Issue found in Trash.', 'vfwp' ),
        'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
        'all_items'                => __( 'All EMBLetc Issue', 'vfwp' ),
        'archives'                 => __( 'EMBLetc Issue Archives', 'vfwp' ),
        'attributes'               => __( 'EMBLetc Issue Attributes', 'vfwp' ),
        'insert_into_item'         => __( 'Insert into EMBLetc Issue', 'vfwp' ),
        'uploaded_to_this_item'    => __( 'Uploaded to this EMBLetc Issue', 'vfwp' ),
        'featured_image'           => _x( 'Featured image', 'EMBLetc Issue', 'vfwp' ),
        'set_featured_image'       => _x( 'Set featured image', 'EMBLetc Issue', 'vfwp' ),
        'remove_featured_image'    => _x( 'Remove featured image', 'EMBLetc Issue', 'vfwp' ),
        'use_featured_image'       => _x( 'Use as featured image', 'EMBLetc Issue', 'vfwp' ),
        'filter_items_list'        => __( 'Filter EMBLetc Issue list', 'vfwp' ),
        'items_list_navigation'    => __( 'EMBLetc Issue list navigation', 'vfwp' ),
        'items_list'               => __( 'EMBLetc Issue list', 'vfwp' ),
        'item_published'           => __( 'EMBLetc Issue published.', 'vfwp' ),
        'item_published_privately' => __( 'EMBLetc Issue published privately.', 'vfwp' ),
        'item_reverted_to_draft'   => __( 'EMBLetc Issue reverted to draft.', 'vfwp' ),
        'item_scheduled'           => __( 'EMBLetc Issue scheduled.', 'vfwp' ),
        'item_updated'             => __( 'EMBLetc Issue updated.', 'vfwp' ),
      );
    }
  
?>