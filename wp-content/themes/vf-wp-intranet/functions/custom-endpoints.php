<?php

// Register custom REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/community-blog', array(
        'methods' => 'GET',
        'callback' => 'get_community_blog_posts',
        'permission_callback' => '__return_true',
        'args' => array(
            'infoscreen' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, ['yes', 'no']);
                }
            ),
            'site' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                }
            ),
            'per_page' => array(
                'default' => 10,
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param) && $param > 0;
                }
            ),
        ),
    ));
});

// Callback function to handle the custom endpoint
function get_community_blog_posts($request) {
    // Get the value of infoscreen, site, and per_page parameters from the request
    $infoscreen = $request->get_param('infoscreen');
    $site = $request->get_param('site');
    $per_page = $request->get_param('per_page');

    // Default number of posts per page if 'per_page' parameter is not provided or invalid
    $posts_per_page = !empty($per_page) && is_numeric($per_page) && $per_page > 0 ? intval($per_page) : -1;

    // Prepare query arguments
    $args = array(
        'post_type' => 'community-blog',
        'posts_per_page' => $posts_per_page,
        'orderby' => 'date',
        'order'   => 'DESC',
    );

    // Add meta query if 'infoscreen' parameter is provided
    if (!empty($infoscreen)) {
        $args['meta_query'] = array(
            array(
                'key'     => 'cb_infoscreen',
                'value'   => $infoscreen === 'yes' ? '1' : '0', // 'yes' = true, 'no' = false
                'compare' => '='
            ),
        );
    }

    // Add taxonomy query if 'site' parameter is provided
    if (!empty($site)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'embl-location',
                'field'    => 'slug',
                'terms'    => $site,
            ),
        );
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

            // Get post data
            $post_data = array(
                'id'                => get_the_ID(),
                'title'             => get_the_title(),
                'date'              => get_the_time('Y-m-d\TH:i:s'), // Exact date and time format
                'excerpt'           => get_the_excerpt(), // Excerpt
                'featured_image_src'=> get_the_post_thumbnail_url(get_the_ID(), 'full'), // Featured image source URL
                'infoscreen'        => get_field('cb_infoscreen') === '1' ? 'yes' : 'no', // Fetch ACF field
                'emergency'        => get_field('cb_emergency_notification')
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

