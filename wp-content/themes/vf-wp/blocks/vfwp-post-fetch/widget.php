<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Widget_Fetch') ) :

class VF_Widget_Fetch extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'vf_widget_fetch',
      __('Fetch posts', 'vfwp')
    );
  }

  /**
   * Render the plugin using the widget ACF data
   */
  public function widget($args, $instance) {

  $widget_id = 'widget_' . $args['widget_id'];

// widget ID with prefix for use in ACF API functions

$url = get_field('url', $widget_id, false, false); 
  
  // Initialize variable.
  $allposts = '';
  
  // Enter the name of your blog here followed by /wp-json/wp/v2/posts and add filters like this one that limits the result to 2 posts.
  $response = wp_remote_get( $url );

  // Exit if error.
  if ( is_wp_error( $response ) ) {
    return;
  }

  // Get the body.
  $posts = json_decode( wp_remote_retrieve_body( $response ) );

  // Exit if nothing is returned.
  if ( empty( $posts ) ) {
    return;
  }

  // If there are posts.
  if ( ! empty( $posts ) ) {

    // For each post.
    foreach ( $posts as $post ) {

      // Use print_r($post); to get the details of the post and all available fields
      // Format the date.
      $fordate = date( 'j F y', strtotime( $post->modified ) );

      // Show a linked title and post date.
      $allposts .= '<article class="vf-summary vf-summary--article vf-u-margin__bottom--400">

      <h3 class="vf-summary__title"><a class="vf-summary__link" style="font-size: 19px;" href="' 
      . esc_url( $post->link ) 
      . '" target=\"_blank\">' 
      . esc_html( $post->title->rendered ) 
      . '</a> </h3>  <div class="vf-summary__meta"><p class="vf-summary__date" style="margin-left: 0;">' 
      . esc_html( $fordate ) . '</p></div> </article>';
    }
    
    echo $allposts;
  } 
  }

  public function form($instance) {
    // Do nothing...
  }

} // VF_Widget_Fetch

endif;

/**
 * Register fetch Widget
 */
function register_fetch_widget()
{
  register_widget( 'VF_Widget_Fetch' );
}
add_action( 'widgets_init', 'register_fetch_widget' ); ?>