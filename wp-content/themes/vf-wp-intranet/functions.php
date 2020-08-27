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




  function breadcrumbs() {
  
    $delimiter = '&raquo;';
    $name = 'Home'; //text for the 'Home' link
    $currentBefore = '<li class="vf-breadcrumbs__item">';
    $currentAfter = '</li>';
    $home = get_bloginfo('url');
    
    echo '<nav class="vf-breadcrumbs" aria-label="Breadcrumb">';
    echo '<ul class="vf-breadcrumbs__list | vf-list vf-list--inline">';

    // blog home page
    if ( is_home() ) {
      echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
      $insites_url = get_post_type_archive_link('post');
      $insites_name = 'Insites';
      echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
       }

    // community blog   
    if ( is_post_type_archive('community-blog') ) {
      echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
      $community_url = get_post_type_archive_link('community-blog');
      $community_name = 'Community blog';
      echo '<li class="vf-breadcrumbs__item"><a href="' . $community_url . '" class="vf-breadcrumbs__link">' . $community_name . '</a></li>';
        }

    // events
    else if ( is_post_type_archive('vf_event') ) {
      echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a></li>';
      $event_url = get_post_type_archive_link('vf_event');
      $event_name = 'Internal events';
      echo '<li class="vf-breadcrumbs__item"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
        }
    
    if (is_tag()){
      echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
      $insites_url = get_post_type_archive_link('post');
      $insites_name = 'Insites';
      echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
      echo $currentBefore . 'Tag: &#39;';
      single_tag_title();
      echo '&#39;' . $currentAfter;
    }

    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) 
      echo (get_category_parents($parentCat, TRUE, ''));
      echo '<li class="vf-breadcrumbs__item"><a href="' . $home . '" class="vf-breadcrumbs__link">' . $name . '</a>';
      $insites_url = get_post_type_archive_link('post');
      $insites_name = 'INsites';
      echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
      echo $currentBefore . 'Category: &#39;';
      single_cat_title();
      echo '&#39;' . $currentAfter; 
  
    }


    if ( !is_home() && !is_archive('community-blog') && !is_archive('vf_event') && !is_front_page() || is_paged() ) {
    
      global $post;
      echo '
      <li class="vf-breadcrumbs__item">
      <a href="' . $home . '" class="vf-breadcrumbs__link" >' . $name . '</a></li>';
        if ( is_day() ) {
        echo '<li class="vf-breadcrumbs__item"><a href="' . get_year_link(get_the_time('Y')) . '"class="vf-breadcrumbs__link">' . get_the_time('Y') . '</a></li>';
        echo '<li class="vf-breadcrumbs__item"><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '"class="vf-breadcrumbs__link">' . get_the_time('F') . '</a></li>';
        echo $currentBefore . get_the_time('d') . $currentAfter;
    
      } elseif ( is_month() ) {
        echo '<li class="vf-breadcrumbs__item"><a href="' . get_year_link(get_the_time('Y')) . '"class="vf-breadcrumbs__link">' . get_the_time('Y') . '</a></li>';
        echo $currentBefore . get_the_time('F') . $currentAfter;
    
      } elseif ( is_year() ) {
        echo $currentBefore . get_the_time('Y') . $currentAfter;

        // community blog single post
      } elseif ( is_singular('community-blog') ) {
        $community_url = get_post_type_archive_link('community-blog');
        $community_name = 'Community blog';
        echo '<li class="vf-breadcrumbs__item"><a href="' . $community_url . '" class="vf-breadcrumbs__link">' . $community_name . '</a></li>';
        echo '<li class="vf-breadcrumbs__item">' . get_the_title($post->ID) . '</li>';

        // event single post
      } elseif ( is_singular('vf_event') ) {
        $event_url = get_post_type_archive_link('vf_event');
        $event_name = 'Internal events';
        echo '<li class="vf-breadcrumbs__item"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
        echo '<li class="vf-breadcrumbs__item">' . get_the_title($post->ID) . '</li>';

        // insites single post
      } elseif ( is_single() && !is_attachment() ) {
        $insites_url = get_post_type_archive_link('post');
        $insites_name = 'INsites';
        echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
        echo '<li class="vf-breadcrumbs__item">' . get_the_title($post->ID) . '</li>';
    
      } elseif ( is_attachment() ) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, '');
        echo '<li class="vf-breadcrumbs__item"><a href="' . get_permalink($parent) . '"class="vf-breadcrumbs__link">' . $parent->post_title . '</a></li>';
        echo $currentBefore;
        the_title();
        echo $currentAfter;
    
      } elseif ( is_page() && !$post->post_parent ) {
        echo $currentBefore;
        the_title();
        echo $currentAfter;
    
      } elseif ( is_page() && $post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<li class="vf-breadcrumbs__item"><a href="' . get_permalink($page->ID) . '" class="vf-breadcrumbs__link">' . get_the_title($page->ID) . '</a></li>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) echo $crumb . '';
        echo $currentBefore;
        the_title(); 
        echo $currentAfter;
    
      } elseif ( is_search() ) {
        echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
    
      } elseif ( is_author() ) {
         global $author;
        $userdata = get_userdata($author);
        echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
    
      } elseif ( is_404() ) {
        echo $currentBefore . 'Error 404' . $currentAfter;
      }
    
      if ( get_query_var('paged') ) {
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
        echo __('Page') . ' ' . get_query_var('paged');
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
      }
    
      echo '</ul>';
      echo '</nav>';
    
    }
  }
?>