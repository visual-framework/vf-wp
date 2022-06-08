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
    $display = get_field('field_target_display', $post->ID);
    $category = get_the_category($post->ID);
    $cat_slug = '';
    if (!empty($category[0]->slug)) {
        $cat_slug = $category[0]->slug;
      }
  if ( (is_single()) && ($display == 'embl-ebi') ) {
      $canonical = 'https://www.ebi.ac.uk/about/news/'. strtolower($cat_slug) . '/' . $post->post_name;
    }
  
    return $canonical;
  }
  
  add_filter( 'wpseo_canonical', 'ebi_news_canonical_url', 20 );

  
  function ebi_news_opengraph_url( $url ) {
      global $post;
      $display = get_field('field_target_display', $post->ID);
      $category = get_the_category($post->ID);
      $cat_slug = '';
      if (!empty($category[0]->slug)) {
          $cat_slug = $category[0]->slug;
        }
      if ( (is_single()) && ($display == 'embl-ebi') ) {
          $url = 'https://www.ebi.ac.uk/about/news/'. strtolower($cat_slug) . '/' . $post->post_name;
        }
        
        return $url;
  }

    add_filter( 'wpseo_opengraph_url', 'ebi_news_opengraph_url' );


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

/**
 * Function to trigger EBI News build process on post publish|save
 * @param $post_id
 * @see https://gitlab.ebi.ac.uk/emblorg/backlog/-/issues/652
 */
function trigger_ebi_news_build_process($post_id) {
    $slug = 'post';
    if ( $slug != $_POST['post_type'] ) {
      return;
    }
  
    if ((strpos($_SERVER['SERVER_NAME'], "wwwdev") !== false) || (strpos($_SERVER['SERVER_NAME'], "localhost") !== false)){
      $branch_ref = "develop";
    }
    else {
      $branch_ref = "master";
    }
  
    $display = get_field('field_target_display', $post_id);
    // CHeck if display is ebi or both
    if ($display == 'embl-ebi' || $display == 'both') {
      // Trigger CI build to update EBI news
      $updated_post_id = "emblorg-" . $post_id;
      $response = wp_remote_post( 'https://gitlab.ebi.ac.uk/api/v4/projects/3488/trigger/pipeline', array("body" => array(
          'token' => '7ee8e6f2bb44cf9a094a7a66e6b9a3',
          'ref' => $branch_ref,
          'variables[FETCH_NEWS]' => 'INCREMENTAL',
          'variables[UPDATED_NEWS_ID]' => $updated_post_id
      )));
      if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        echo "Something went wrong with CI trigger: $error_message";
      }
    }
  }
  add_action( 'save_post', 'trigger_ebi_news_build_process' );
?>