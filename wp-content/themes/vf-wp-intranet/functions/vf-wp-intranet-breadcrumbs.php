<?php

/*
Plugin Name: VF-WP Intranet Breadcrumbs
Description: VF-WP Intranet theme global container.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Breadcrumbs_Intranet extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_breadcrumbs_intranet',
    'post_title' => 'Intranet breadcrumbs',
    'post_type'  => 'vf_container',
    // Allow block to be previewed in WP admin
    '__experimental__has_admin_preview' => true
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_breadcrumbs_intranet');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }


public function intranet_breadcrumbs() {
  
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
    
        $post = get_queried_object();
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
        echo '<li class="vf-breadcrumbs__item">' . single_post_title() . '</li>';

        // event single post
      } elseif ( is_singular('vf_event') ) {
        $event_url = get_post_type_archive_link('vf_event');
        $event_name = 'Internal events';
        echo '<li class="vf-breadcrumbs__item"><a href="' . $event_url . '" class="vf-breadcrumbs__link">' . $event_name . '</a></li>';
        echo '<li class="vf-breadcrumbs__item">' . single_post_title() . '</li>';

        // insites single post
      } elseif ( is_single() && !is_attachment() ) {
        $insites_url = get_post_type_archive_link('post');
        $insites_name = 'INsites';
        echo '<li class="vf-breadcrumbs__item"><a href="' . $insites_url . '" class="vf-breadcrumbs__link">' . $insites_name . '</a></li>';
        echo '<li class="vf-breadcrumbs__item">' . single_post_title() . '</li>';
    
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
        single_post_title();
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
        single_post_title(); 
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

  public function template_callback($block, $content, $is_preview = false, $acf_id) {
    // Block preview in Gutenberg editor
    $is_preview = isset($is_preview) && $is_preview;

    if ($is_preview) {
    ?>
    <div class="vf-banner vf-banner--info">
    <div class="vf-banner__content">
        <p class="vf-banner__text">
        <?php echo esc_html_e('This is a placeholder for the intranet breadcrumbs container.', 'vfwp'); ?>
        </p>
    </div>
    </div>
    <?php
    return;
    } $this->intranet_breadcrumbs();

   }

} // VF_Breadcrumbs_Intranet

$plugin = new VF_Breadcrumbs_Intranet(array('init' => true));

?>
