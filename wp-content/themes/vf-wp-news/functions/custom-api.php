<?php
/* 
Register a custom REST API for all posts
*/

// /wp-json/custom/v1/posts?taxonomy_slug=slug_name&tags=tag1,tag2&categories=cat1,cat2

function custom_api_query_posts() {
    register_rest_route( 'custom/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_filtered_posts',
        'permission_callback' => '__return_true',
        'args' => array(
            'taxonomy_slug' => array(
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param );
                }
            ),
            'tags' => array(
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param ) || is_array( $param );
                }
            ),
            'categories' => array(
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param ) || is_array( $param );
                }
            )
        )
    ) );
}
add_action( 'rest_api_init', 'custom_api_query_posts' );

// Callback function for fetching filtered posts
function get_filtered_posts( $data ) {
    // Set up query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1, // Get all posts
        'post_status' => 'publish',
    );

    // Filter by taxonomy slug
    if ( ! empty( $data['taxonomy_slug'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'embl_taxonomy', 
                'field'    => 'slug',
                'terms'    => (array) $data['taxonomy_slug'],
                'operator' => 'IN',
            ),
        );
    }

    // Filter by tags
    if ( ! empty( $data['tags'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => (array) $data['tags'], 
            'operator' => 'IN',
        );
    }

    // Filter by categories
    if ( ! empty( $data['categories'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => (array) $data['categories'], 
            'operator' => 'IN',
        );
    }

    // Run the query
    $query = new WP_Query( $args );

    // Check if posts exist
    if ( $query->have_posts() ) {
        $posts = array();

        while ( $query->have_posts() ) {
            $query->the_post();
            $posts[] = array(
              'title'   => get_the_title(),
              'excerpt' => get_the_excerpt(),
              'date'    => get_the_date(),
              'link'    => get_permalink(),
              'featured_image_src'    => get_the_post_thumbnail_url(get_the_ID(), 'full')
            );
        }

        return rest_ensure_response( $posts );
    } else {
        return new WP_Error( 'no_posts', 'No posts found', array( 'status' => 404 ) );
    }
}


/* 
  Register a custom REST API for Awards and honor post type
 */


// Register custom REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/awards', array(
        'methods' => 'GET',
        'callback' => 'get_awards_posts',
        'permission_callback' => '__return_true',
    ));
  });
  
  // Callback function to handle the custom endpoint
  function get_awards_posts($request) {
    // Get the value of cb_featured, site, and per_page parameters from the request
    $per_page = $request->get_param('per_page');
    $award_site = $request->get_param('award_site');
    $award_unit = $request->get_param('award_unit');
    
    // Default number of posts per page if 'per_page' parameter is not provided or invalid
    $posts_per_page = !empty($per_page) && is_numeric($per_page) && $per_page > 0 ? intval($per_page) : -1;
  
    // Query arguments to retrieve posts from awards post type
    $args = array(
        'post_type' => 'awards',
        'posts_per_page' => $posts_per_page,
        'orderby' => 'date', // Sort by date
        'order'   => 'DESC', // Show latest item first
    );
  
    // Taxonomy query to filter posts by 'award-site' and 'award-unit' taxonomies
    $tax_query = array('relation' => 'AND'); // Default relation is AND
  
    // Add 'award-site' taxonomy filter if the parameter is provided
    if (!empty($award_site)) {
        $tax_query[] = array(
            'taxonomy' => 'award-site',
            'field'    => 'slug', // Adjust to 'id' if you want to filter by term ID
            'terms'    => $award_site,
        );
    }
  
    // Add 'award-unit' taxonomy filter if the parameter is provided
    if (!empty($award_unit)) {
        $tax_query[] = array(
            'taxonomy' => 'award-unit',
            'field'    => 'slug', // Adjust to 'id' if you want to filter by term ID
            'terms'    => $award_unit,
        );
    }
  
    // Only add tax_query if filters are set
    if (!empty($award_site) || !empty($award_unit)) {
        $args['tax_query'] = $tax_query;
    }
  
    // Perform the query
    $query = new WP_Query($args);
  
    // Check if there are posts found
    if ($query->have_posts()) {
        // Create an array to store posts data
        $posts_data = array();
  
        // Loop through each post
        while ($query->have_posts()) {
            $query->the_post();
  
            // Get terms for 'award-site' and 'award-unit' taxonomies
            $award_site_terms = get_the_terms(get_the_ID(), 'award-site');
            $award_unit_terms = get_the_terms(get_the_ID(), 'award-unit');
  
            // Format terms as arrays of names or slugs
            $award_site_data = $award_site_terms ? wp_list_pluck($award_site_terms, 'name') : array();
            $award_unit_data = $award_unit_terms ? wp_list_pluck($award_unit_terms, 'name') : array();
  
            // Get post data
            $post_data = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'date' => get_the_time('Y-m-d\TH:i:s'), // Exact date and time format
                'excerpt' => get_the_excerpt(), // Excerpt
                'featured_image_src' => get_the_post_thumbnail_url(get_the_ID(), 'full'), // Featured image source URL
                'award_site' => $award_site_data, // Add award-site terms
                'award_unit' => $award_unit_data, // Add award-unit terms
                'url' => get_permalink(get_the_ID()), // Add link to the post
                // Add more fields as needed
            );
  
            // Add post data to the array
            $posts_data[] = $post_data;
        }
  
        // Reset post data
        wp_reset_postdata();
  
        // Return the posts data as JSON response
        return rest_ensure_response($posts_data);
    } else {
        // If no posts found, return empty array
        return rest_ensure_response(array());
    }
  }
?>