<?php 

// adds support for feature images

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

/**
 * Load ACF JSON from theme
 */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }
 
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
    
  /**
   * Action: `init`
   */
  add_action(
    'init',
    'vf_wp_intranet_blog__init'
  );
  
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
        'category',
        'post_tag'
      ),
    ));
    
  }  


?>