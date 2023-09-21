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

  // get the data from peolple API  
  $bioit_json_feed_api_endpoint = "https://bio-it.embl.de/events/categories/course/feed/";
  // Fetch API to get paging details.
  $bioit_feed_content = file_get_contents($bioit_json_feed_api_endpoint);
  $bioit_feed_content = str_replace(array("\n", "\r", "\t"), '', $bioit_feed_content);

  $bioit_feed_content = trim(str_replace('"', "'", $bioit_feed_content));
  $xmldata = simplexml_load_string($bioit_feed_content);
  $raw_data = json_encode($xmldata, true);
  
  if (!empty($raw_data) ) {
      insert_bioit_posts_from_json($raw_data);
    }
    else {
        echo "There was error fetching paging details for bioit api.";
    }
    
}

/*
* Function to Insert/Update bioit records in WP.
*/

function insert_bioit_posts_from_json($raw_data) {
    if ( ! is_admin() ) {
        require_once( ABSPATH . 'wp-admin/includes/post.php' );
    }
    
    $raw_content_decoded = json_decode($raw_data, true);
    $bioit_data = $raw_content_decoded['channel']['item'];
    
    var_dump($bioit_data);
    
    if (!empty($bioit_data)) {
        foreach ($bioit_data as $key => $person) {
            $title = $person['title'];
        $url = basename($person['link']);

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
            add_post_meta($post_id, 'url', $url);
            }

        // update post if already exists
        else if (get_page_by_path($url, 'OBJECT', 'training')){
            $get_post = get_page_by_path($url, 'OBJECT', 'training');
            $existing_post_id = $get_post->ID;
            update_post_meta($existing_post_id, 'post_title', $title);
            update_post_meta($existing_post_id, 'url', $url);
            if (!(metadata_exists( 'post', $existing_post_id, 'post_title'))) {
            add_post_meta($post_id, 'post_title', $title);
            }    
        }}
    }
}



?>