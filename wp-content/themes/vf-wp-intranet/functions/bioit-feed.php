<?php
/*
 * Set cron for importing training data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval_bioit' );
function vfwp_intranet_add_cron_interval_bioit( $schedules ) {
  $schedules['every_six_hours'] = array(
    'interval' => 21600, // Every 6 hours
    'display'  => __( 'Every 6 hours' ),
  );
  return $schedules;
}

// Check if cron scheduled or not.
if ( ! wp_next_scheduled( 'vfwp_intranet_cron_process' ) ) {
  wp_schedule_event( time(), 'every_six_hours', 'vfwp_intranet_cron_process' );
}

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_bioit_data');

// Function to start process training data.
function vfwp_intranet_cron_process_bioit_data() {
    // get the data from people API  
    $bioit_json_feed_api_endpoint = "https://bio-it.embl.de/events/categories/course/feed/";
    // Fetch API to get paging details.
    $bioit_feed_content = file_get_contents($bioit_json_feed_api_endpoint);
    $bioit_feed_content = str_replace(array("\n", "\r", "\t"), '', $bioit_feed_content);

    $bioit_feed_content = trim(str_replace('"', "'", $bioit_feed_content));
    $xmldata = simplexml_load_string($bioit_feed_content, "SimpleXMLElement", LIBXML_NOCDATA);

    if (!empty($xmldata)) {
        insert_bioit_posts_from_xml($xmldata);
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Success!', 'sample-text-domain'); ?></p>
        </div>
        <?php
    } else {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Error!', 'sample-text-domain'); ?></p>
        </div>
        <?php
    }
}

/*
 * Function to Insert/Update bioit records in WP.
 */
function insert_bioit_posts_from_xml($xmldata) {
    if (!is_admin()) {
        require_once(ABSPATH . 'wp-admin/includes/post.php');
    }

    $bioit_data = $xmldata->channel->item;

    if (!empty($bioit_data)) {
        foreach ($bioit_data as $item) {
            $title = (string)$item->title;
            $url = basename((string)$item->link);
            $description = (string)$item->description;

            // Initialize an empty associative array
            $associative_array = [];

            // Use regular expressions to extract key-value pairs from HTML content
            preg_match_all('/<([^>]+)>([^<]+)<\/\1>/', $description, $matches);

            // Iterate through the matches and populate the associative array
            for ($i = 0; $i < count($matches[0]); $i++) {
                $key = $matches[1][$i];
                $value = $matches[2][$i];
                $associative_array[$key] = $value;
            }
            $startDate = isset($associative_array['startDate']) ? $associative_array['startDate'] : '';
            $endDate = isset($associative_array['endDate']) ? $associative_array['endDate'] : '';
            $provider = isset($associative_array['provider']) ? $associative_array['provider'] : '';
            $location = isset($associative_array['location']) ? $associative_array['location'] : '';
            $overview = isset($associative_array['overview']) ? $associative_array['overview'] : '';
            $permalink = (string)$item->link;
            // echo ($provider);
            // Print the associative array
            print_r($associative_array);

            $new_post = [
                'post_title' => $title,
                'post_name' => $url,
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'training',
            ];

// Insert post
if (!get_page_by_path($url, 'OBJECT', 'training')) {
    $post_id = wp_insert_post($new_post);
    add_post_meta($post_id, 'post_title', $title);
    add_post_meta($post_id, 'vf-wp-training-start_date', $startDate);
    add_post_meta($post_id, 'vf-wp-training-end_date', $endDate);
    add_post_meta($post_id, 'vf-wp-training-info', $overview);
    add_post_meta($post_id, 'vf-wp-training-url', $permalink);

                    // Check if the term exists before setting it
                    $location_term_exists = term_exists(strtolower($location), 'event-location');
                    if ($location_term_exists !== 0 && $location_term_exists !== null) {
                        // Set the custom taxonomy terms
                        wp_set_object_terms($post_id, array(strtolower($location)), 'event-location', false);
                    }
                    // Check if the term exists before setting it
                    $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
                    if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
                        // Set the custom taxonomy terms
                        wp_set_object_terms($post_id, array(strtolower($provider)), 'training-organiser', false);
                    }    
                }

// update post if already exists
else if ($existing_post = get_page_by_path($url, 'OBJECT', 'training')) {
    $existing_post_id = $existing_post->ID;
    update_post_meta($existing_post_id, 'post_title', $title);
    update_post_meta($existing_post_id, 'vf-wp-training-start_date', $startDate);
    update_post_meta($existing_post_id, 'vf-wp-training-end_date', $endDate);
    update_post_meta($existing_post_id, 'vf-wp-training-info', $overview);
    update_post_meta($existing_post_id, 'vf-wp-training-url', $permalink);
    if (!metadata_exists('post', $existing_post_id, 'post_title')) {
        add_post_meta($existing_post_id, 'post_title', $title);
    }
                    // Check if the term exists before setting it
                    $location_term_exists = term_exists(strtolower($location), 'event-location');
                    if ($location_term_exists !== 0 && $location_term_exists !== null) {
                        // Set the custom taxonomy terms
                        wp_set_object_terms($existing_post_id, array(strtolower($location)), 'event-location', false);
                    }
                    // Check if the term exists before setting it
                    $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
                    if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
                        // Set the custom taxonomy terms
                        wp_set_object_terms($existing_post_id, array(strtolower($provider)), 'training-organiser', false);
                    }


}
        }
    
} }



?>