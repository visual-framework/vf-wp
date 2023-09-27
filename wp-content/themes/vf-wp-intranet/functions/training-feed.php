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
    $training_json_feed_api_endpoint = "https://www.ebi.ac.uk/api/v1/ebi-training-events-webinars?source=trainingcontenthub&timeframe=upcoming";
  
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
        foreach ($training_data as $key => $training_ebi) {
        $title = $training_ebi['title'];
        $url = basename($training_ebi['more_info_link']);
        $permalink = $training_ebi['more_info_link'];
        $startDate = $training_ebi['opening_date'];
        $endDate = $training_ebi['closing_date'];
        $location = $training_ebi['location'];
        $format = $training_ebi['eventType'];
        $keywords = $training_ebi['keywords'];
        $category = $training_ebi['category'];
        $fee = $training_ebi['registration_fees'];
        $registrationDeadline = $training_ebi['registration_deadline'];
        $overview = $training_ebi['description'];
        $overview = strip_tags($overview);
        
        // $provider = $person['provider'];
        if (!empty($startDate)) {
            $formattedStartDate = date('Ymd', strtotime($startDate));
        } 
        if (!empty($endDate)) {
            $formattedendDate = date('Ymd', strtotime($endDate));
        } 

        if (!empty($registrationDeadline)) {
            $formattedregDate = date('Ymd', strtotime($registrationDeadline));
        } 
        else {
            $formattedregDate = $formattedStartDate;
        }

        // echo $permalink;

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
            add_post_meta($post_id, 'vf-wp-training-url', $permalink);
            add_post_meta($post_id, 'vf-wp-training-start_date', $formattedStartDate);
            add_post_meta($post_id, 'vf-wp-training-end_date', $formattedendDate);
            add_post_meta($post_id, 'vf-wp-training-category', $category);
            add_post_meta($post_id, 'vf-wp-training-format', $format);
            add_post_meta($post_id, 'vf-wp-training-fee', $fee);
            add_post_meta($post_id, 'vf-wp-training-registration-deadline', $formattedregDate);
            add_post_meta($post_id, 'vf-wp-training-info', $overview);
            add_post_meta($post_id, 'keyword', $keywords);
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
            update_post_meta($existing_post_id, 'vf-wp-training-url', $permalink);
            update_post_meta($existing_post_id, 'vf-wp-training-start_date', $formattedStartDate);
            update_post_meta($existing_post_id, 'vf-wp-training-end_date', $formattedendDate); 
            update_post_meta($existing_post_id, 'vf-wp-training-category', $category);
            update_post_meta($existing_post_id, 'vf-wp-training-format', $format);
            update_post_meta($existing_post_id, 'vf-wp-training-fee', $fee);
            update_post_meta($existing_post_id, 'vf-wp-training-registration-deadline', $formattedregDate);
            update_post_meta($existing_post_id, 'vf-wp-training-info', $overview);
            update_post_meta($existing_post_id, 'keyword', $keywords);
       
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