<?php
/*
 * Set cron for importing people data
 */

add_filter( 'cron_schedules', 'vfwp_intranet_add_cron_interval' );
function vfwp_intranet_add_cron_interval( $schedules ) {
  $schedules['every_hour'] = array(
    'interval' => 3600, // Every 1h
    'display'  => __( 'Every 1 hour' ),
  );
  return $schedules;
}

// Check if cron scheduled or not.
if ( ! wp_next_scheduled( 'vfwp_intranet_cron_process' ) ) {
  wp_schedule_event( time(), 'every_hour', 'vfwp_intranet_cron_process' );
}

add_action('vfwp_intranet_cron_process', 'vfwp_intranet_cron_process_people_data');

/*
 * Function to process people data from the new single JSON endpoint
 */


// --- CRON / API SYNC FUNCTION ---
function vfwp_intranet_cron_process_people_data_async() {
    $lock_key = 'vfwp_intranet_cron_lock';

    if (get_transient($lock_key)) {
        return;
    }

    set_transient($lock_key, true, 60 * MINUTE_IN_SECONDS);

    $api_url = 'https://dev.content.embl.org/static-api/ebi-all-staff-list-intranet.json';

    $existing_posts = get_posts(array(
        'post_type' => 'people',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    ));

    $existing_bdr_ids = array();
    foreach ($existing_posts as $post_id) {
        $bdr_id = get_post_meta($post_id, 'bdr_id', true);
        if ($bdr_id) $existing_bdr_ids[$bdr_id] = $post_id;
    }

    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        delete_transient($lock_key);
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['rows']) || !is_array($data['rows'])) {
        delete_transient($lock_key);
        return;
    }

    $api_bdr_ids = array();
    $stats = array(
        'created_titles' => array(),
        'updated_titles' => array(),
        'deleted_titles' => array(),
        'skipped_titles' => array(),
    );

    foreach ($data['rows'] as $person) {
        $bdr_id = $person['bdr_public_id'];
        if (!$bdr_id) continue;

        $api_bdr_ids[] = $bdr_id;

        $title = !empty($person['known_as']) ? $person['known_as'] : ($person['full_name'] ?? '');
        $url = basename(parse_url($person['url'], PHP_URL_PATH));
        $cpid = $person['cpid'] ?? '';
        $orcid = $person['orcid'] ?? '';
        $photo = $person['photo'] ?? '';
        $email = $person['email'] ?? '';
        $biography = $person['biography'] ?? '';
        $room = $person['room'] ?? '';
        $outstation = $person['outstation'] ?? '';
        $telephones = $person['telephones'] ?? '';
        $positions = $person['positions'] ?? array();
        $contact_form = $person['contact_form'] ?? '';

        // Normalize telephone
        $telephone = '';
        if (is_array($telephones) && !empty($telephones[0]['telephone'])) {
            $telephone = $telephones[0]['telephone'];
        } elseif (is_string($telephones)) {
            $telephone = $telephones;
        }

        $primary_position = $positions[0] ?? null;
        $positions_name_1 = $primary_position['job_title'] ?? '';
        $team_name_1 = $primary_position['team_name'] ?? '';
        $is_primary_1 = $primary_position['is_primary'] ?? '';

        $post_id = $existing_bdr_ids[$bdr_id] ?? null;

        if ($post_id) {
            // Check if any field changed
            $changed = false;
            $fields_to_check = [
                'full_name' => $title,
                'cpid' => $cpid,
                'orcid' => $orcid,
                'photo' => $photo,
                'email' => $email,
                'biography' => $biography,
                'room' => $room,
                'outstation' => $outstation,
                'bdr_id' => $bdr_id,
                'contact_form' => $contact_form,
                'telephone' => $telephone,
                'positions_name_1' => $positions_name_1,
                'team_name_1' => $team_name_1,
                'is_primary_1' => $is_primary_1,
            ];

            foreach ($fields_to_check as $key => $value) {
                if (get_post_meta($post_id, $key, true) !== $value) {
                    $changed = true;
                    break;
                }
            }

            if ($changed) {
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $title,
                    'post_name' => $url,
                ));

                foreach ($fields_to_check as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }

                $stats['updated_titles'][] = $title;
            } else {
                $stats['skipped_titles'][] = $title;
            }
        } else {
            $new_post_id = wp_insert_post(array(
                'post_title' => $title,
                'post_name' => $url,
                'post_status' => 'publish',
                'post_type' => 'people',
                'post_content' => '',
                'post_author' => 1,
            ));

            if ($new_post_id && !is_wp_error($new_post_id)) {
                foreach ([
                    'full_name' => $title,
                    'cpid' => $cpid,
                    'orcid' => $orcid,
                    'photo' => $photo,
                    'email' => $email,
                    'biography' => $biography,
                    'room' => $room,
                    'outstation' => $outstation,
                    'bdr_id' => $bdr_id,
                    'contact_form' => $contact_form,
                    'telephone' => $telephone,
                    'positions_name_1' => $positions_name_1,
                    'team_name_1' => $team_name_1,
                    'is_primary_1' => $is_primary_1,
                ] as $key => $value) {
                    update_post_meta($new_post_id, $key, $value);
                }

                $stats['created_titles'][] = $title;
            }
        }
    }

    // Delete posts no longer in API
    foreach ($existing_bdr_ids as $bdr_id => $post_id) {
        if (!in_array($bdr_id, $api_bdr_ids)) {
            wp_delete_post($post_id, true);
            $stats['deleted_titles'][] = get_the_title($post_id);
        }
    }

    update_option('vfwp_people_sync_stats', $stats);
    update_option('vfwp_last_sync_time', current_time('mysql', true));

    delete_transient($lock_key);
}



// --- REST ENDPOINTS ---
add_action('rest_api_init', function(){
    register_rest_route('vfwp/v1','/sync-people',[
        'methods'=>'POST',
        'callback'=>'vfwp_sync_people_data',
        'permission_callback'=>function(){ return current_user_can('manage_options'); },
    ]);

    register_rest_route('vfwp/v1','/delete-people',[
        'methods'=>'POST',
        'callback'=>'vfwp_delete_people_data',
        'permission_callback'=>function(){ return current_user_can('manage_options'); },
    ]);

register_rest_route('vfwp/v1', '/get-sync-stats', [
    'methods'  => 'GET',
    'callback' => function () {
        $lock_key = 'vfwp_intranet_cron_lock';
        $running  = get_transient($lock_key) ? true : false;

        $stats = get_option('vfwp_people_sync_stats', [
            'created_titles' => [],
            'updated_titles' => [],
            'deleted_titles' => [],
            'skipped_titles' => [],
        ]);

        return new WP_REST_Response([
            'completed' => !$running, 
            'stats'     => [
                'created'        => count($stats['created_titles'] ?? []),
                'updated'        => count($stats['updated_titles'] ?? []),
                'deleted'        => count($stats['deleted_titles'] ?? []),
                'created_titles' => $stats['created_titles'] ?? [],
                'updated_titles' => $stats['updated_titles'] ?? [],
                'deleted_titles' => $stats['deleted_titles'] ?? [],
            ],
            'last_sync_time' => get_option('vfwp_last_sync_time', 'Never'),
        ]);
    },
    'permission_callback' => function () {
        return current_user_can('manage_options');
    },
]);
});

function vfwp_sync_people_data(WP_REST_Request $request) {
    $lock_key = 'vfwp_intranet_cron_lock';

    if (get_transient($lock_key)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Sync is already running in the background.',
        ], 429);
    }

    vfwp_intranet_cron_process_people_data_async();

    return new WP_REST_Response([
        'success' => true,
        'message' => 'Sync started in the background. Please wait...',
    ], 200);
}


function vfwp_delete_people_data() {
    $query = new WP_Query([
        'post_type'=>'people',
        'posts_per_page'=>-1,
        'fields'=>'ids',
        'post_status'=>'any'
    ]);

    if(!$query->have_posts()){
        return ['success'=>false,'message'=>'No people data found to delete.'];
    }

    $deleted_titles = [];
    foreach($query->posts as $post_id){
        $deleted_titles[] = get_the_title($post_id);
        wp_delete_post($post_id,true);
    }

    // Save delete stats for display
    update_option('vfwp_people_sync_stats', [
        'created_titles'=>[],
        'updated_titles'=>[],
        'deleted_titles'=>$deleted_titles,
    ]);
    update_option('vfwp_last_sync_time', current_time('mysql',true));

    return ['success'=>true,'message'=>'All people data deleted successfully.'];
}

// --- ADMIN NOTICES / BUTTONS ---
add_action('admin_enqueue_scripts', function($hook){
    if($hook!=='edit.php' || !isset($_GET['post_type']) || $_GET['post_type']!=='people') return;

    wp_enqueue_script(
        'vfwp-people-admin',
        get_theme_file_uri('/scripts/people/people-admin.js'),
        ['jquery'],
        filemtime(get_theme_file_path('/scripts/people/people-admin.js')),
        true
    );

wp_localize_script('vfwp-people-admin', 'vfwpPeopleSettings', [
    'apiRoot'  => esc_url_raw(rest_url('vfwp/v1/')),
    'token'    => wp_create_nonce('wp_rest'),
    'messages' => [
        'syncing'  => __('Syncing in progress...', 'vfwp'),
        'deleting' => __('Deleting – please do not close this window.', 'vfwp'),
        'error'    => __('There was an error processing your request.', 'vfwp'),
    ],
]);

});

add_action('admin_notices', function(){
    $screen = get_current_screen();
    if($screen->id!=='edit-people') return;

    $last_sync_utc = get_option('vfwp_last_sync_time','Never');
    if($last_sync_utc!=='Never'){
        $datetime = new DateTime($last_sync_utc,new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone('Europe/Berlin'));
        $last_sync = $datetime->format('d/m/Y H:i:s');
    } else {
        $last_sync = 'Never';
    }

    echo '<div class="notice notice-info" style="padding-bottom:12px;">
        <p><span>'.sprintf(__('Last synced: %s','vfwp'),esc_html($last_sync)).'</span></p>
        <button id="vfwp-sync-people" class="button button-primary">'.__('Sync people data','vfwp').'</button>
        <button id="vfwp-delete-people" class="button button-danger" style="color:#fff;border-color:#d41645;background:#d41645;">'.__('Delete all posts','vfwp').'</button>
    </div>';
});
