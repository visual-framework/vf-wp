<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once('vf-template-placeholder.php');

if ( ! class_exists('VF_Template') ) :

/**
 * Represent a custom post type for Visual Framework WP templates
 */
class VF_Template {

  public function __construct() {
    // Nothing
  }

  static public function type() {
    return 'vf_template';
  }

  static public function default_template() {
    return array(
      array(
        'vf/container-page-template',
        array()
      ),
    );
  }

  // public function default_content() {
  //   return '<!-- wp:vf/container-page-template {"ver":"1.0.0","defaults":1} /-->' + "\n";
  // }

  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  static public function labels() {
    return array(
      'name'                     => _x( 'Templates', 'template type general name', 'vfwp' ),
      'singular_name'            => _x( 'Template', 'template type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'template', 'vfwp' ),
      'add_new_item'             => __( 'Add New Template', 'vfwp' ),
      'edit_item'                => __( 'Edit Template', 'vfwp' ),
      'new_item'                 => __( 'New Template', 'vfwp' ),
      'view_item'                => __( 'View Template', 'vfwp' ),
      'view_items'               => __( 'View Templates', 'vfwp' ),
      'search_items'             => __( 'Search Templates', 'vfwp' ),
      'not_found'                => __( 'No templates found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No templates found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Templates', 'vfwp' ),
      'archives'                 => __( 'Template Archives', 'vfwp' ),
      'attributes'               => __( 'Template Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into template', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this template', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'template', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'template', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'template', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'template', 'vfwp' ),
      'filter_items_list'        => __( 'Filter templates list', 'vfwp' ),
      'items_list_navigation'    => __( 'Templates list navigation', 'vfwp' ),
      'items_list'               => __( 'Templates list', 'vfwp' ),
      'item_published'           => __( 'Template published.', 'vfwp' ),
      'item_published_privately' => __( 'Template published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Template reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Template scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Template updated.', 'vfwp' ),
    );
  }

  public function initialize() {
    // Add hooks
    add_action(
      'init',
      array($this, 'init')
    );
    add_action(
      'vf_header',
      array($this, 'vf_header')
    );
    add_action(
      'vf_footer',
      array($this, 'vf_footer')
    );
  }

  /**
   * Action: `init`
   * Register custom post type
   * https://developer.wordpress.org/reference/functions/register_post_type/
   */
  public function init() {
    register_post_type(VF_Template::type(),array(
      'labels'              => VF_Template::labels(),
      'description'         => __('Theme Templates', 'vfwp'),
      'public'              => false,
      'hierarchical'        => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'menu_position'       => 40,
      'menu_icon'           => 'dashicons-layout',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor'),
      'has_archive'         => false,
      'rewrite'             => false,
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
    ));

    // Register the placeholder container plugin
    $placeholder = new VF_Container_Placeholder(
      array('init' => true)
    );

    // Set default Gutenberg template
    $post_type_object = get_post_type_object(VF_Template::type());
    if ($post_type_object) {
      $post_type_object->template = VF_Template::default_template();
    }
  }

  public function vf_header() {
    var_dump('HEADER CONTAINERS');
  }

  public function vf_footer() {
    var_dump('FOOTER CONTAINERS');
  }

} // VF_Template

endif;

?>
