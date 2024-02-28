<?php
  /**
   * Add metadata to REST API
   */

// display
function register_display_api_field() {
    register_rest_field('post', 'field_target_display',
        array(
            'get_callback' => 'get_display_api_field',
            'schema' => null,
        )
    );
}

add_action('rest_api_init', 'register_display_api_field');

function get_display_api_field($post) {
    return get_field('field_target_display', $post['id']);
}

// language
function register_language_api_field() {
    register_rest_field('post', 'field_article_language',
        array(
            'get_callback' => 'get_language_api_field',
            'schema' => null,
        )
    );
}

add_action('rest_api_init', 'register_language_api_field');

function get_language_api_field($post) {
    return get_field('field_article_language', $post['id']);
}

// Add featured image to REST API
add_action('rest_api_init', 'register_rest_images' );
function register_rest_images(){
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}


function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}




/// YOAST SEO overwrite canonical url for EBI news

function ebi_news_canonical_url( $canonical ) {
  global $post;
  if(class_exists('WPSEO_Primary_Term')) {
  $wpseo_primary_term = new WPSEO_Primary_Term( 'category', $post->ID );
  $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
  $the_primary_term = get_term( $wpseo_primary_term );
  $categories_ebi = array('perspectives', 'announcements', 'research-highlights', 'technology-and-innovation');
  if ( (in_array($the_primary_term->slug, $categories_ebi, true)) ) {
    $canonical = 'https://www.ebi.ac.uk/about/news/'. strtolower($the_primary_term->slug) . '/' . $post->post_name;
    }
  return $canonical;
   }
  }

add_filter( 'wpseo_canonical', 'ebi_news_canonical_url', 20 );


function ebi_news_opengraph_url( $url ) {
  global $post;
  if(class_exists('WPSEO_Primary_Term')) {
  $wpseo_primary_term = new WPSEO_Primary_Term( 'category', $post->ID );
  $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
  $the_primary_term = get_term( $wpseo_primary_term );
  $categories_ebi = array('perspectives', 'announcements', 'research-highlights', 'technology-and-innovation');
  if ( (in_array($the_primary_term->slug, $categories_ebi, true)) ) {
    $url = 'https://www.ebi.ac.uk/about/news/'. strtolower($the_primary_term->slug) . '/' . $post->post_name;
    }
  return $url;
   }
  }
  add_filter( 'wpseo_opengraph_url', 'ebi_news_opengraph_url', 20 );


/*
 * Redirect posts to EMBL-EBI URL
 */

add_action( 'template_redirect', 'redirect_to_ebi' );
function redirect_to_ebi(){
    global $post;
    if( strpos( $_SERVER["REQUEST_URI"], '/announcements/' ) !== false ){ 
      if( strpos( $_SERVER["REQUEST_URI"], '/category/' ) !== false ){ 
      $redirect = 'https://www.ebi.ac.uk/about/news/category/announcements/';
       }
      else {
      $redirect = 'https://www.ebi.ac.uk/about/news/announcements/' . trim($post->post_name, '-2');
      }      
      wp_redirect( $redirect );
    }
    if( strpos( $_SERVER["REQUEST_URI"], '/perspectives/' ) !== false ){ 
      if( strpos( $_SERVER["REQUEST_URI"], '/category/' ) !== false ){ 
      $redirect = 'https://www.ebi.ac.uk/about/news/category/perspectives/';
       }
      else {
      $redirect = 'https://www.ebi.ac.uk/about/news/perspectives/' . trim($post->post_name, '-2');
      }      
      wp_redirect( $redirect );
    }
    if( strpos( $_SERVER["REQUEST_URI"], '/research-highlights/' ) !== false ){ 
      if( strpos( $_SERVER["REQUEST_URI"], '/category/' ) !== false ){ 
      $redirect = 'https://www.ebi.ac.uk/about/news/category/research-highlights/';
       }
      else {
      $redirect = 'https://www.ebi.ac.uk/about/news/research-highlights/' . trim($post->post_name, '-2');
      }      
      wp_redirect( $redirect );
    }
    if( strpos( $_SERVER["REQUEST_URI"], '/technology-and-innovation/' ) !== false ){ 
      if( strpos( $_SERVER["REQUEST_URI"], '/category/' ) !== false ){ 
      $redirect = 'https://www.ebi.ac.uk/about/news/category/technology-and-innovation/';
       }
      else {
      $redirect = 'https://www.ebi.ac.uk/about/news/technology-and-innovation/' . trim($post->post_name, '-2');
      }      
      wp_redirect( $redirect );
    }
    if( strpos( $_SERVER["REQUEST_URI"], '/updates-from-data-resources/' ) !== false ){ 
      if( strpos( $_SERVER["REQUEST_URI"], '/category/' ) !== false ){ 
      $redirect = 'https://www.ebi.ac.uk/about/news/category/updates-from-data-resources/';
       }
      else {
      $redirect = 'https://www.ebi.ac.uk/about/news/updates-from-data-resources/' . trim($post->post_name, '-2');
      }      
      wp_redirect( $redirect );
    }

}  

