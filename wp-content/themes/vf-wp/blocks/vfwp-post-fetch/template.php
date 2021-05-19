<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;


if(!function_exists('get_posts_via_rest')) {

function get_posts_via_rest() {
  $url = get_field('url');
  
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
    
    return $allposts;
  }

} }
// Register as a shortcode to be used on the site.
add_shortcode( 'get_posts_via_rest', 'get_posts_via_rest' );

?>
<?php echo do_shortcode('[get_posts_via_rest]'); ?> 

