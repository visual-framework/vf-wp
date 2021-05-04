<?php

require_once('functions/custom-taxonomies.php');
require_once('functions/learning-labs-post.php');
require_once('functions/teachingbase-post.php');
require_once('functions/insight-lecture-post.php');
require_once('functions/ambassadors-post.php');

// enable featured image
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );


// Newsletter container

if ( ! defined( 'ABSPATH' ) ) exit;

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
      foreach ($translation as $l) {
          if ($l->element_id != $thispostid) {
              $langs[] = '<a href="' . apply_filters('wpml_permalink', ( get_permalink($l->element_id)), $l->language_code) . '"><img class="wpml-ls-flag iclflag" src="'.$languages[$l->language_code]['country_flag_url'].'" />' . '</a>';
              }
      }
      echo join(' &nbsp; ', $langs);
  }
}

?>
