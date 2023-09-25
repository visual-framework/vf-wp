<?php
/*
 * Set cron for importing training data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval_training' );
function vfwp_intranet_add_cron_interval_training( $schedules ) {
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

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_training_data');

// Function to start process training data.
function vfwp_intranet_cron_process_training_data() {
    $training_json_feed_api_endpoint = "https://www.ebi.ac.uk/api/v1/ebi-training-events-webinars?source=trainingcontenthub";
  
    // Fetch API content and decode it
    $training_feed_content = file_get_contents($training_json_feed_api_endpoint);
    $training_data = json_decode($training_feed_content, true);
    
    if (!empty($training_data) && is_array($training_data)) {
      insert_training_posts_from_json($training_data);
      ?>
      <div class="notice notice-success is-dismissible">
          <p><?php _e('EMBL-EBI Data Science Training data imported successfully!', 'sample-text-domain'); ?></p>
      </div>
      <?php
  } else {
      ?>
      <div class="notice notice-error is-dismissible">
          <p><?php _e('An error occured by fetching the EMBL-EBI Data Science Training data!', 'sample-text-domain'); ?></p>
      </div>
      <?php
  }
}

/*
 * Function to Insert/Update training records in WP.
 */

function insert_training_posts_from_json($training_data) {
  if ( ! is_admin() ) {
     require_once( ABSPATH . 'wp-admin/includes/post.php' );
  }

  
    if (!empty($training_data) && is_array($training_data)) {
        foreach ($training_data as $key => $person) {
        $title = $person['title'];
        $url = basename($person['more_info_link']);
        $permalink = $person['more_info_link'];
        $startDate = $person['opening_date'];
        $endDate = $person['closing_date'];
        $location = $person['location'];
        // $provider = $person['provider'];
        if (!empty($startDate)) {
            $formattedStartDate = date('Ymd', strtotime($startDate));
        } 
        if (!empty($endDate)) {
            $formattedendDate = date('Ymd', strtotime($endDate));
        } 

        echo $formattedStartDate;

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
            add_post_meta($post_id, 'url', $permalink);
            add_post_meta($post_id, 'vf-wp-training-start_date', $formattedStartDate);
            add_post_meta($post_id, 'vf-wp-training-end_date', $formattedendDate);
            // Check if the term exists before setting it
            $location_term_exists = term_exists(strtolower($location), 'event-location');
            if ($location_term_exists !== 0 && $location_term_exists !== null) {
                // Set the custom taxonomy terms
                wp_set_object_terms($post_id, array(strtolower($location)), 'event-location', false);
            }
            // Check if the term exists before setting it
            // $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
            // if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
            //     // Set the custom taxonomy terms
            //     wp_set_object_terms($post_id, array(strtolower($provider)), 'training-organiser', false);
            // }  
        
            }

        // update post if already exists
        else if (get_page_by_path($url, 'OBJECT', 'training')){
            $get_post = get_page_by_path($url, 'OBJECT', 'training');
            $existing_post_id = $get_post->ID;
            update_post_meta($existing_post_id, 'post_title', $title);
            update_post_meta($existing_post_id, 'url', $url);
            update_post_meta($existing_post_id, 'vf-wp-training-start_date', $formattedStartDate);
            update_post_meta($existing_post_id, 'vf-wp-training-end_date', $formattedendDate);        
            if (!(metadata_exists( 'post', $existing_post_id, 'post_title'))) {
            add_post_meta($post_id, 'post_title', $title);
            }    
            // Check if the term exists before setting it
            $location_term_exists = term_exists(strtolower($location), 'event-location');
            if ($location_term_exists !== 0 && $location_term_exists !== null) {
                // Set the custom taxonomy terms
                wp_set_object_terms($existing_post_id, array(strtolower($location)), 'event-location', false);
            }
            // Check if the term exists before setting it
            // $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
            // if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
            //     // Set the custom taxonomy terms
            //     wp_set_object_terms($existing_post_id, array(strtolower($provider)), 'training-organiser', false);
            // }
        }}
    }
}

// delete all draft posts
function delete_all_training_posts(){
    $draft_args = array('post_type' => 'training', 'numberposts'=> -1, 'post_status' => 'publish' );   
    $draft_posts = get_posts( $draft_args );
    foreach($draft_posts as $draft_delete){
        wp_delete_post( $draft_delete->ID, true );
     }
    }


?>