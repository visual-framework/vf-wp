<?php
/*
 * Set cron for importing people data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval' );
function vfwp_intranet_add_cron_interval( $schedules ) {
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

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_people_data');

// Function to start process people data.
function vfwp_intranet_cron_process_people_data() {

  // Get all existing posts with their BDR IDs
  $existing_posts = get_posts(array(
    'post_type' => 'people',
    'numberposts' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
  ));

  $existing_bdr_ids = array();
  foreach ($existing_posts as $post_id) {
    $bdr_id = get_post_meta($post_id, 'bdr_id', true);
    if ($bdr_id) {
      $existing_bdr_ids[$bdr_id] = $post_id;
    }
  }

  // Get all BDR IDs from the API across all pages
  $current_page = 0;
  $items_per_page = 100;
  $people_json_feed_api_endpoint = "https://content.embl.org/api/v1/people-all-info?items_per_page=$items_per_page";
  $api_bdr_ids = array();

  // Fetch API to get paging details.
  $people_feed_content = file_get_contents($people_json_feed_api_endpoint);
  $raw_data = json_decode($people_feed_content, true);
  if (!empty($raw_data) && is_array($raw_data)) {
    $pager_data_arr = $raw_data['pager'];
    $total_pages = $pager_data_arr['total_pages'];
    $total_items = $pager_data_arr['total_items'];

    // Loop through pages to collect all BDR IDs from the API
    for ($i = $current_page; $i <= $total_pages; $i++) {
      $raw_content = file_get_contents($people_json_feed_api_endpoint . "&page=$i");
      $raw_content_decoded = json_decode($raw_content, true);
      $people_data = $raw_content_decoded['rows'];

      if (!empty($people_data) && is_array($people_data)) {
        foreach ($people_data as $person) {
          $api_bdr_ids[] = $person['bdr_public_id'];
        }
      }
    }

    // Loop through pages to insert/update posts
    for ($i = $current_page; $i <= $total_pages; $i++) {
      insert_people_posts_from_json($people_json_feed_api_endpoint, $i, $existing_bdr_ids);
    }
  } else {
    echo "There was an error fetching paging details for the people API.";
  }

  // Delete posts that exist in WordPress but not in the API
  foreach ($existing_bdr_ids as $bdr_id => $post_id) {
    if (!in_array($bdr_id, $api_bdr_ids)) {
      wp_delete_post($post_id, true);
    }
  }
}

/*
 * Function to Insert/Update people records in WP.
 */

function insert_people_posts_from_json($people_json_feed_api_endpoint, $page_number, $existing_bdr_ids) {
  if ( ! is_admin() ) {
     require_once( ABSPATH . 'wp-admin/includes/post.php' );
  }

  $raw_content = file_get_contents($people_json_feed_api_endpoint . "&page=$page_number");
  $raw_content_decoded = json_decode($raw_content, true);
  $people_data = $raw_content_decoded['rows'];

  if (!empty($people_data) && is_array($people_data)) {
    foreach ($people_data as $person) {
      $title = $person['full_name'];
      $url = basename($person['url']);
      $cpid = $person['cpid'];
      $orcid = $person['orcid'];
      $photo = $person['photo'];
      $email = $person['email'];
      $biography = $person['biography'];
      $room = $person['room'];
      $bdr_id = $person['bdr_public_id'];
      $outstation = $person['outstation'];
      $telephones = $person['telephones'];
      $positions = $person['positions'];

      if (!empty($telephones[0])) {
        $telephone = $person['telephones'][0]['telephone'];
      }
      if (!empty($positions[0])) {
        $positions_name_1 = $person['positions'][0]['name'];
        $team_name_1 = $person['positions'][0]['team_name'];
        $is_primary_1 = $person['positions'][0]['is_primary'];
      }
      if (!empty($positions[1])) {
        $positions_name_2 = $person['positions'][1]['name'];
        $team_name_2 = $person['positions'][1]['team_name'];
        $is_primary_2 = $person['positions'][1]['is_primary'];
      }
      if (!empty($positions[2])) {
        $positions_name_3 = $person['positions'][2]['name'];
        $team_name_3 = $person['positions'][2]['team_name'];
        $is_primary_3 = $person['positions'][2]['is_primary'];
      }
      if (!empty($positions[3])) {
        $positions_name_4 = $person['positions'][3]['name'];
        $team_name_4 = $person['positions'][3]['team_name'];
        $is_primary_4 = $person['positions'][3]['is_primary'];
      }

      $new_post = [
        'post_title' => $title,
        'post_name' => $url,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'people',
      ];

      // Check if the post already exists in WordPress
      if (isset($existing_bdr_ids[$bdr_id])) {
        // Update the existing post
        $post_id = $existing_bdr_ids[$bdr_id];
        wp_update_post(array(
          'ID' => $post_id,
          'post_title' => $title,
          'post_name' => $url,
        ));

        update_post_meta($post_id, 'post_title', $title);
        update_post_meta($post_id, 'url', $url);
        update_post_meta($post_id, 'full_name', $title);
        update_post_meta($post_id, 'cpid', $cpid);
        update_post_meta($post_id, 'orcid', $orcid);
        update_post_meta($post_id, 'photo', $photo);
        update_post_meta($post_id, 'email', $email);
        update_post_meta($post_id, 'biography', $biography);
        update_post_meta($post_id, 'room', $room);
        update_post_meta($post_id, 'outstation', $outstation);
        update_post_meta($post_id, 'bdr_id', $bdr_id);

        if (!empty($positions[0])) {
          update_post_meta($post_id, 'positions_name_1', $positions_name_1);
          update_post_meta($post_id, 'team_name_1', $team_name_1);
          update_post_meta($post_id, 'is_primary_1', $is_primary_1);
        }
        if (!empty($positions[1])) {
          update_post_meta($post_id, 'positions_name_2', $positions_name_2);
          update_post_meta($post_id, 'team_name_2', $team_name_2);
          update_post_meta($post_id, 'is_primary_2', $is_primary_2);
        }
        if (!empty($positions[2])) {
          update_post_meta($post_id, 'positions_name_3', $positions_name_3);
          update_post_meta($post_id, 'team_name_3', $team_name_3);
          update_post_meta($post_id, 'is_primary_3', $is_primary_3);
        }
        if (!empty($positions[3])) {
          update_post_meta($post_id, 'positions_name_4', $positions_name_4);
          update_post_meta($post_id, 'team_name_4', $team_name_4);
          update_post_meta($post_id, 'is_primary_4', $is_primary_4);
        }
        if (!empty($telephones[0])) {
          update_post_meta($post_id, 'telephone', $telephone);
        }
      } else {
        // Insert new post
        $post_id = wp_insert_post($new_post);
        add_post_meta($post_id, 'post_title', $title);
        add_post_meta($post_id, 'url', $url);
        add_post_meta($post_id, 'full_name', $title);
        add_post_meta($post_id, 'cpid', $cpid);
        add_post_meta($post_id, 'orcid', $orcid);
        add_post_meta($post_id, 'photo', $photo);
        add_post_meta($post_id, 'email', $email);
        add_post_meta($post_id, 'biography', $biography);
        add_post_meta($post_id, 'room', $room);
        add_post_meta($post_id, 'outstation', $outstation);
        add_post_meta($post_id, 'bdr_id', $bdr_id);

        if (!empty($positions[0])) {
          add_post_meta($post_id, 'positions_name_1', $positions_name_1);
          add_post_meta($post_id, 'team_name_1', $team_name_1);
          add_post_meta($post_id, 'is_primary_1', $is_primary_1);
        }
        if (!empty($positions[1])) {
          add_post_meta($post_id, 'positions_name_2', $positions_name_2);
          add_post_meta($post_id, 'team_name_2', $team_name_2);
          add_post_meta($post_id, 'is_primary_2', $is_primary_2);
        }
        if (!empty($positions[2])) {
          add_post_meta($post_id, 'positions_name_3', $positions_name_3);
          add_post_meta($post_id, 'team_name_3', $team_name_3);
          add_post_meta($post_id, 'is_primary_3', $is_primary_3);
        }
        if (!empty($positions[3])) {
          add_post_meta($post_id, 'positions_name_4', $positions_name_4);
          add_post_meta($post_id, 'team_name_4', $team_name_4);
          add_post_meta($post_id, 'is_primary_4', $is_primary_4);
        }
        if (!empty($telephones[0])) {
          add_post_meta($post_id, 'telephone', $telephone);
        }
      }
    }
  }
}

// adds settings to run cron and sync people data manually

add_action('admin_menu', 'sync_people_menu');

function sync_people_menu(){
  add_submenu_page('edit.php?post_type=people', 'People settings', 'Sync', 'manage_options', 'sync-people-slug', 'sync_people_admin_page');
}

function sync_people_admin_page() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.')    );
  }
  // Start building the page
  echo '<div class="wrap">';
  echo '<h2>Sync people data</h2>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['sync_people']) && check_admin_referer('sync_people_clicked')) {
    // the button has been pressed AND we've passed the security check
    vfwp_intranet_cron_process_people_data();
  }
  echo '<form action="edit.php?post_type=people&page=sync-people-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('sync_people_clicked');
  echo '<input type="hidden" value="true" name="sync_people" />';
  submit_button('Sync');
  echo '</form>';
  echo '</div>';

}

add_action('admin_menu', 'delete_people_menu');

function delete_people_menu(){
  add_submenu_page('edit.php?post_type=people', 'People settings', 'Delete', 'manage_options', 'delete-people-slug', 'delete_people_admin_page');
}

function delete_people_admin_page() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.')    );
  }
  // Start building the page
  echo '<div class="wrap">';
  echo '<h2>Delete all people posts</h2>';
  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['delete_people']) && check_admin_referer('delete_people_clicked')) {
    // the button has been pressed AND we've passed the security check
    delete_all_people_posts();
  }
  echo '<form action="edit.php?post_type=people&page=delete-people-slug" method="post">';
  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('delete_people_clicked');
  echo '<input type="hidden" value="true" name="delete_people" />';
  submit_button('Delete');
  echo '</form>';
  echo '</div>';

}

?>