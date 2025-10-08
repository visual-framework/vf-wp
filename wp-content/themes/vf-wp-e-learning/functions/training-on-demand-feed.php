<?php
/*
 * Set cron for importing training_on_demand data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval_training_on_demand' );
function vfwp_intranet_add_cron_interval_training_on_demand( $schedules ) {
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

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_training_on_demand_data');

// Function to start process training_on_demand data.
function vfwp_intranet_cron_process_training_on_demand_data() {
    $training_on_demand_json_feed_api_endpoint = "https://www.ebi.ac.uk/ebisearch/ws/rest/ebiweb_training_online?format=json&query=domain_source:ebiweb_training_online&start=0&size=500&fieldurl=true&fields=title,subtitle,description,type,training_type,url,venue,timeframe,slug,tags,duration&facetcount=50&sort=title&facets=";
  
    // Fetch API content and decode it
    $training_on_demand_feed_content = file_get_contents($training_on_demand_json_feed_api_endpoint);
    $training_on_demand_data = json_decode($training_on_demand_feed_content, true);
    
    if (!empty($training_on_demand_data) && is_array($training_on_demand_data)) {
      insert_training_on_demand_posts_from_json($training_on_demand_data);
      ?>
      <div class="notice notice-success is-dismissible">
          <p><?php _e('EMBL-EBI on-demand training data imported successfully!', 'sample-text-domain'); ?></p>
      </div>
      <?php
  } else {
      ?>
      <div class="notice notice-error is-dismissible">
          <p><?php _e('An error occured by fetching the EMBL-EBI on-demand training data!', 'sample-text-domain'); ?></p>
      </div>
      <?php
  }
}

/*
 * Function to Insert/Update training_on_demand records in WP.
 */

function insert_training_on_demand_posts_from_json($training_on_demand_data) {
  if ( ! is_admin() ) {
     require_once( ABSPATH . 'wp-admin/includes/post.php' );
  }

  
    if (!empty($training_on_demand_data['entries']) && is_array($training_on_demand_data['entries'])) {
        foreach ($training_on_demand_data['entries'] as $key => $training_on_demand_ebi) {
        $title = $training_on_demand_ebi['fields']['title'][0];
        $url = basename($training_on_demand_ebi['fields']['url'][0]);
        $permalink = $training_on_demand_ebi['fields']['url'][0];
        $od_type = $training_on_demand_ebi['fields']['type'][0];
// Determine main type
if (in_array($od_type, ['Online tutorial'], true)) {
    $od_type = 'Online course';
}
elseif (in_array($od_type, ['Collection'], true)) {
    $od_type = 'Online course collection';
}

// Determine subtype
$od_subtype = '';
if ($training_on_demand_ebi['fields']['type'][0] === 'Collection') {
    $od_subtype = 'Collection';
} elseif ($training_on_demand_ebi['fields']['type'][0] === 'Online tutorial') {
    $od_subtype = 'Tutorial';
}
        $category = 'Bioinformatics';
        $audience = ['Data scientists', 'Life science researchers'];
        $provider = 'embl-ebi-training';
        $duration = $training_on_demand_ebi['fields']['duration'][0];
        if ($training_on_demand_ebi['fields']['duration'][0] === 'More than 3 hours') {
        $duration = '3 to 9 hours';
}
        $keywords = $training_on_demand_ebi['fields']['tags'];
        $tags = implode(", ", array_slice($keywords, 0, 3)); // temporary
        $keywords = implode(", ", $keywords);
        $overview = $training_on_demand_ebi['fields']['description'][0];
        $overview = strip_tags($overview);
    

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
            add_post_meta($post_id, 'vf-wp-training-topic', $category);
            add_post_meta($post_id, 'vf-wp-training-info', $overview);
            add_post_meta($post_id, 'vf-wp-training-keywords', $keywords);
            add_post_meta($post_id, 'vf-wp-training-on_demand_type', $od_type);
            add_post_meta($post_id, 'vf-wp-training-course_type', $od_subtype);
            add_post_meta($post_id, 'vf-wp-training-duration', $duration);
            add_post_meta($post_id, 'vf-wp-training-tags', $tags);

            // ACF multiple select field
                if (function_exists('update_field')) {
                    update_field('vf-wp-training-audience', $audience, $post_id);
                }
            // Check if the term exists before setting it
            $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
            if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
                // Set the custom taxonomy terms
                wp_set_object_terms($post_id, array($provider), 'training-organiser', false);
            }  
        
            }

        // update post if already exists
        else if (get_page_by_path($url, 'OBJECT', 'training')){
            $get_post = get_page_by_path($url, 'OBJECT', 'training');
            $existing_post_id = $get_post->ID;
            update_post_meta($existing_post_id, 'post_title', $title);
            update_post_meta($existing_post_id, 'vf-wp-training-url', $permalink);
            update_post_meta($existing_post_id, 'vf-wp-training-topic', $category);
            update_post_meta($existing_post_id, 'vf-wp-training-info', $overview);
            update_post_meta($existing_post_id, 'vf-wp-training-keywords', $keywords);
            update_post_meta($existing_post_id, 'vf-wp-training-on_demand_type', $od_type);
            update_post_meta($existing_post_id, 'vf-wp-training-course_type', $od_subtype);
            update_post_meta($existing_post_id, 'vf-wp-training-duration', $duration);
            update_post_meta($existing_post_id, 'vf-wp-training-tags', $tags);
            // ACF multiple select field
                if (function_exists('update_field')) {
                    update_field('vf-wp-training-audience', $audience, $existing_post_id);
                }

       
            if (!(metadata_exists( 'post', $existing_post_id, 'post_title'))) {
            add_post_meta($post_id, 'post_title', $title);
            }    

            // Check if the term exists before setting it
            $provider_term_exists = term_exists(strtolower($provider), 'training-organiser');
            if ($provider_term_exists !== 0 && $provider_term_exists !== null) {
                // Set the custom taxonomy terms
                wp_set_object_terms($existing_post_id, array($provider), 'training-organiser', false);
            }
        }}
    }
}