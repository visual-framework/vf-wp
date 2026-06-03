<?php

require_once('functions/custom-taxonomies.php');
require_once('functions/learning-labs-post.php');
require_once('functions/teachingbase-post.php');
require_once('functions/insight-lecture-post.php');
require_once('functions/ambassadors-post.php');
require_once('functions/archive-browser.php');

// enable featured image
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );


// Newsletter container

if ( ! defined( 'ABSPATH' ) ) exit;

// CHILD THEME CSS FILE

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

	$parent_style = 'parent-style';
  $child_style_path = get_stylesheet_directory() . '/style.css';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
	get_stylesheet_directory_uri() . '/style.css',
	array( $parent_style ),
	file_exists($child_style_path) ? filemtime($child_style_path) : wp_get_theme()->get('Version')
);
}

 // custom language switcher for the WPML plugin
function languages_links_switcher(){
$languages = icl_get_languages('skip_missing=1');
if(1 < count($languages)){ echo __(' <div class="vf-banner vf-banner--alert vf-banner--info | vf-u-margin__bottom--200">
  <div class="vf-banner__content">
    <style>
      .vf-banner__content p {
        font-size: 16px !important;
        margin: 0px !important;
      }
    </style>
    <p class="vf-banner__text">This article is also available in ');

      foreach($languages as $l){
      if(!$l['active']) $langs[] = '<a href="'.$l['url'].'"><img class="wpml-ls-flag iclflag" src="' . $l['country_flag_url'].'" />&nbsp;' .$l['native_name'].'</a>';
      }
      echo join(' and ', array_filter(array_merge(array(join(', ', array_slice($langs, 0, -1))), array_slice($langs,
      -1)), 'strlen'));

      echo __('
    </p>
  </div>
  </div>' );

  }
  }
  

  // Show linked WPML posts in a loop
function wpml_post_languages_in_loop() {
  $thispostid = get_the_ID();
  $post_trid = apply_filters('wpml_element_trid', NULL, get_the_ID(), 'post_' . get_post_type());
  $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0&orderby=code' );
  if (empty($post_trid))
      return;
  $translation = apply_filters('wpml_get_element_translations', NULL, $post_trid, 'post_' . get_post_type());
  if (1 < count($translation)) {
    echo '<p class="vf-summary__meta">Other language(s): &nbsp;&nbsp;';
      foreach ($translation as $l) {
          if ($l->element_id != $thispostid) {
              $langs[] = '<a href="' . apply_filters('wpml_permalink', ( get_permalink($l->element_id)), $l->language_code) . '"><img class="wpml-ls-flag iclflag" src="'.$languages[$l->language_code]['country_flag_url'].'" />' . '</a>';
              }
      }
      echo join(' &nbsp; ', $langs);
      echo '</p>';
  }
}

// modifies the teachingbase loop to not show child posts
function vf_wp_ells__pre_get_posts($query) {
  if (  !is_admin() && $query->is_main_query() && !$query->is_tax() && $query->is_home()) {
  $query->set('post_type', array( 'teachingbase' ) );
  }
  if (is_admin()) {
    return;
  }
  if ( ! $query->is_main_query()) {
    return;
  }
  $post_type = get_query_var('post_type');
  if ($post_type !== 'teachingbase') {
    return;
  }
  if (is_singular()) {
    return;
  }
  $query->set('post_parent', 0);
}
add_filter( 'pre_get_posts', 'vf_wp_ells__pre_get_posts' );

function vf_wp_ells_teacher_training_legacy_redirect() {
  if ( is_admin() || wp_doing_ajax() ) {
    return;
  }

  $archive_url = get_post_type_archive_link('learninglab');
  if ( ! $archive_url ) {
    $archive_url = home_url('/teacher-training/');
  }

  if ( isset($_GET['post_type']) && $_GET['post_type'] === 'learninglab' ) {
    $query = $_GET;
    unset($query['post_type']);
    wp_safe_redirect(add_query_arg($query, $archive_url), 301);
    exit;
  }

  if ( empty($_SERVER['REQUEST_URI']) ) {
    return;
  }

  $request_path = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH);
  $home_path = wp_parse_url(home_url('/'), PHP_URL_PATH);
  $relative_path = trim($request_path, '/');

  if ( $home_path && $home_path !== '/' ) {
    $home_path = trim($home_path, '/');
    if ( strpos($relative_path, $home_path . '/') === 0 ) {
      $relative_path = substr($relative_path, strlen($home_path) + 1);
    }
  }

  $segments = array_values(array_filter(explode('/', $relative_path), 'strlen'));
  if ( empty($segments) ) {
    return;
  }

  $legacy_slugs = array('training', 'learninglab', 'learninglabs');
  if ( ! in_array($segments[0], $legacy_slugs, true) ) {
    return;
  }

  $remaining_path = implode('/', array_slice($segments, 1));
  $target = trailingslashit($archive_url);
  if ( $remaining_path ) {
    $target = trailingslashit($target . $remaining_path);
  }

  wp_safe_redirect(add_query_arg($_GET, $target), 301);
  exit;
}
add_action('template_redirect', 'vf_wp_ells_teacher_training_legacy_redirect', 1);


?>
