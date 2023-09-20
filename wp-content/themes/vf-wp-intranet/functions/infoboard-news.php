<?php

function get_posts_via_rest() {
    $url = 'https://www.embl.org/news/wp-json/wp/v2/posts/?per_page=1';
    
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
        $fordate = date( 'j F Y', strtotime( $post->modified ) );
        $excerpt_raw = $post->excerpt->rendered;
        // Show a linked title and post date.
        $allposts .= '<div id="external_news">
        <div style="display: inline-block; margin-right: 24px;"><table class="newsContent"><tbody><tr><td> <img src="' . esc_url($post->fimg_url) . '" width="768" height="461"></td></tr></tbody></table></div>
        <div style="display: inline-block; position: absolute;"><div class="newsDate">' . esc_html( $fordate ) .'</div>
        <div class="newsTitle">' . esc_html( $post->title->rendered ) .'</div>
        <div class="newsSubtitle">' . ($excerpt_raw) .'</div></div>
        </div>';
      }
      
      return $allposts;
    }
  
  } 
// Register as a shortcode to be used on the site.
add_shortcode( 'get_posts_via_rest', 'get_posts_via_rest' );


?>
