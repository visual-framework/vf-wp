<?php



if( ! defined( 'ABSPATH' ) ) exit;

function get_all_them_posts() {
  $count_posts = wp_count_posts('document');
  $published_posts = $count_posts->publish;
  return $published_posts;
}

// CHILD THEME CSS FILE

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('vfwp'),
        wp_get_theme()->get('Version')
    );
}

// DISPLAY CUSTOM FIELDS IN THE MENU

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_filter('acf/settings/show_admin', '__return_true');
function my_acf_save_post( $post_id ) {
    // get new value
    $user = get_field( 'author', $post_id );
  if( $user ) {
    wp_update_post( array( 'ID'=>$post_id, 'post_author'=>$user['ID']) );
  }
}
add_action('acf/save_post', 'my_acf_save_post', 20);

add_filter(
  'acf/settings/load_json',
  'vf_wp_documents__acf_settings_load_json',
  1
);

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
function vf_wp_document_labels() {
  return array(
    'name'                     => _x( 'Documents', 'document type general name', 'vfwp' ),
    'singular_name'            => _x( 'Document', 'document type singular name', 'vfwp' ),
    'add_new'                  => _x( 'Add New', 'document', 'vfwp' ),
    'add_new_item'             => __( 'Add New Document', 'vfwp' ),
    'edit_item'                => __( 'Edit Document', 'vfwp' ),
    'new_item'                 => __( 'New Document', 'vfwp' ),
    'view_item'                => __( 'View Document', 'vfwp' ),
    'view_items'               => __( 'View Documents', 'vfwp' ),
    'search_items'             => __( 'Search Documents', 'vfwp' ),
    'not_found'                => __( 'No documents found.', 'vfwp' ),
    'not_found_in_trash'       => __( 'No documents found in Trash.', 'vfwp' ),
    'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
    'all_items'                => __( 'All Documents', 'vfwp' ),
    'archives'                 => __( 'Document Archives', 'vfwp' ),
    'attributes'               => __( 'Document Attributes', 'vfwp' ),
    'insert_into_item'         => __( 'Insert into document', 'vfwp' ),
    'uploaded_to_this_item'    => __( 'Uploaded to this document', 'vfwp' ),
    'featured_image'           => _x( 'Featured image', 'document', 'vfwp' ),
    'set_featured_image'       => _x( 'Set featured image', 'document', 'vfwp' ),
    'remove_featured_image'    => _x( 'Remove featured image', 'document', 'vfwp' ),
    'use_featured_image'       => _x( 'Use as featured image', 'document', 'vfwp' ),
    'filter_items_list'        => __( 'Filter documents list', 'vfwp' ),
    'items_list_navigation'    => __( 'Documents list navigation', 'vfwp' ),
    'items_list'               => __( 'Documents list', 'vfwp' ),
    'item_published'           => __( 'Document published.', 'vfwp' ),
    'item_published_privately' => __( 'Document published privately.', 'vfwp' ),
    'item_reverted_to_draft'   => __( 'Document reverted to draft.', 'vfwp' ),
    'item_scheduled'           => __( 'Document scheduled.', 'vfwp' ),
    'item_updated'             => __( 'Document updated.', 'vfwp' ),
  );
}

function vf_wp_document_topic_labels() {
  return array(
    'name'              => _x( 'Topics', 'taxonomy general name', 'vfwp' ),
    'singular_name'     => _x( 'Topic', 'taxonomy singular name', 'vfwp' ),
    'search_items'      => __( 'Search Topics', 'vfwp' ),
    'all_items'         => __( 'All Topics', 'vfwp' ),
    'parent_item'       => __( 'Parent Topic', 'vfwp' ),
    'parent_item_colon' => __( 'Parent Topic:', 'vfwp' ),
    'edit_item'         => __( 'Edit Topic', 'vfwp' ),
    'update_item'       => __( 'Update Topic', 'vfwp' ),
    'add_new_item'      => __( 'Add New Topic', 'vfwp' ),
    'new_item_name'     => __( 'New Topic Name', 'vfwp' ),
    'menu_name'         => __( 'Topics', 'vfwp' ),
  );
}

function vf_wp_document_type_labels() {
  return array(
    'name'              => _x( 'Types', 'taxonomy general name', 'vfwp' ),
    'singular_name'     => _x( 'Type', 'taxonomy singular name', 'vfwp' ),
    'search_items'      => __( 'Search Types', 'vfwp' ),
    'all_items'         => __( 'All Types', 'vfwp' ),
    'parent_item'       => __( 'Parent Type', 'vfwp' ),
    'parent_item_colon' => __( 'Parent Type:', 'vfwp' ),
    'edit_item'         => __( 'Edit Type', 'vfwp' ),
    'update_item'       => __( 'Update Type', 'vfwp' ),
    'add_new_item'      => __( 'Add New Type', 'vfwp' ),
    'new_item_name'     => __( 'New Type Name', 'vfwp' ),
    'menu_name'         => __( 'Types', 'vfwp' ),
  );
}

/**
 * Action: `init`
 */
add_action(
  'init',
  'vf_wp_documents__init'
);

function vf_wp_documents__init() {

  register_post_type('document', array(
    'labels'              => vf_wp_document_labels(),
    'description'         => __('Documents', 'vfwp'),
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
    'menu_icon'           => 'dashicons-media-document',
    'capability_type'     => 'page',
    'supports'            => array('title', 'editor'),
    'has_archive'         => true,
    'rewrite'             => array(
      'slug'       => '/',
      'with_front' => false
    ),
    'query_var'           => true,
    'can_export'          => true,
    'delete_with_user'    => false,
    'taxonomies'          => array(
      'document_topic',
      'document_type'
    ),
  ));

  register_taxonomy('document_topic', array('document'), array(
    'labels'             => vf_wp_document_topic_labels(),
    'hierarchical'       => true,
    'show_ui'            => true,
    'show_admin_column'  => true,
    'query_var'          => true,
    'publicly_queryable' => true,
    'show_in_nav_menus'  => false,
    'rewrite'            => false,
    'show_in_rest'       => false,
  ));

  register_taxonomy('document_type', array('document'), array(
    'labels'             => vf_wp_document_type_labels(),
    'hierarchical'       => true,
    'show_ui'            => true,
    'show_admin_column'  => true,
    'query_var'          => true,
    'publicly_queryable' => true,
    'show_in_nav_menus'  => false,
    'rewrite'            => false,
    'show_in_rest'       => false,
  ));

}

add_action(
  'pre_get_posts',
  'vf_wp_documents__pre_get_posts'
);

function vf_wp_documents__pre_get_posts($query) {
  if (is_admin()) {
    return;
  }
  if ( ! $query->is_main_query()) {
    return;
  }
  $post_type = get_query_var('post_type');
  if ($post_type !== 'document') {
    return;
  }
  if (is_singular()) {
    return;
  }
  $query->set('posts_per_page', -1);
}

?>
