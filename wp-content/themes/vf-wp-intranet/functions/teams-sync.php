<?php
/**
 * Sync Teams posts from the EMBL ContentHub team profiles API.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('VFWP_INTRANET_TEAMS_SYNC_ENDPOINT')) {
    define('VFWP_INTRANET_TEAMS_SYNC_ENDPOINT', 'https://www.embl.org/api/v1/team-profiles?source=contenthub');
}
if (!defined('VFWP_INTRANET_TEAMS_SYNC_HOOK')) {
    define('VFWP_INTRANET_TEAMS_SYNC_HOOK', 'vfwp_intranet_teams_sync_daily');
}
if (!defined('VFWP_INTRANET_TEAMS_SYNC_LOCK')) {
    define('VFWP_INTRANET_TEAMS_SYNC_LOCK', 'vfwp_intranet_teams_sync_lock');
}
if (!defined('VFWP_INTRANET_TEAMS_SYNC_SOURCE')) {
    define('VFWP_INTRANET_TEAMS_SYNC_SOURCE', 'contenthub');
}

add_action('init', 'vfwp_intranet_schedule_teams_sync');
function vfwp_intranet_schedule_teams_sync() {
    if (!wp_next_scheduled(VFWP_INTRANET_TEAMS_SYNC_HOOK)) {
        wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', VFWP_INTRANET_TEAMS_SYNC_HOOK);
    }
}

add_action('switch_theme', 'vfwp_intranet_clear_teams_sync_schedule');
function vfwp_intranet_clear_teams_sync_schedule() {
    wp_clear_scheduled_hook(VFWP_INTRANET_TEAMS_SYNC_HOOK);
}

add_action(VFWP_INTRANET_TEAMS_SYNC_HOOK, 'vfwp_intranet_sync_teams_from_contenthub');

function vfwp_intranet_sync_teams_from_contenthub() {
    if (get_transient(VFWP_INTRANET_TEAMS_SYNC_LOCK)) {
        return new WP_Error('vfwp_teams_sync_locked', __('Teams sync is already running.', 'vfwp'));
    }

    set_transient(VFWP_INTRANET_TEAMS_SYNC_LOCK, true, 30 * MINUTE_IN_SECONDS);

    $stats = array(
        'created_titles' => array(),
        'updated_titles' => array(),
        'deleted_titles' => array(),
        'skipped_titles' => array(),
        'error_messages' => array(),
    );

    $teams = vfwp_intranet_get_contenthub_teams();

    if (is_wp_error($teams)) {
        delete_transient(VFWP_INTRANET_TEAMS_SYNC_LOCK);
        update_option('vfwp_teams_sync_stats', $stats);
        update_option('vfwp_teams_last_sync_error', $teams->get_error_message());

        return $teams;
    }

    $existing_posts = get_posts(array(
        'post_type' => 'teams',
        'numberposts' => -1,
        'post_status' => 'any',
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'team_sync_source',
                'value' => VFWP_INTRANET_TEAMS_SYNC_SOURCE,
                'compare' => '=',
            ),
        ),
    ));

    $existing_team_ids = array();
    foreach ($existing_posts as $post_id) {
        $team_id = get_post_meta($post_id, 'team_api_id', true);
        if ($team_id !== '') {
            $existing_team_ids[(string) $team_id] = (int) $post_id;
        }
    }

    $api_team_ids = array();

    foreach ($teams as $team) {
        if (!is_array($team)) {
            continue;
        }

        $team_id = isset($team['team_id']) ? sanitize_text_field((string) $team['team_id']) : '';
        if ($team_id === '') {
            $stats['error_messages'][] = __('Skipped one team because it had no team_id.', 'vfwp');
            continue;
        }

        $api_team_ids[] = $team_id;

        $fields = vfwp_intranet_normalize_team_fields($team);
        $title = $fields['team_name'] !== '' ? $fields['team_name'] : sprintf(__('Team %s', 'vfwp'), $team_id);
        $post_name = vfwp_intranet_get_team_slug($fields['team_url'], $title);
        $post_id = isset($existing_team_ids[$team_id]) ? $existing_team_ids[$team_id] : 0;

        if ($post_id) {
            $changed = vfwp_intranet_team_post_has_changes($post_id, $fields, $title, $post_name);

            if ($changed) {
                $updated = wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $title,
                    'post_name' => $post_name,
                    'post_excerpt' => $fields['team_strapline'],
                    'post_content' => $fields['team_long_description'],
                ), true);

                if (is_wp_error($updated)) {
                    $stats['error_messages'][] = $updated->get_error_message();
                    continue;
                }

                vfwp_intranet_update_team_meta($post_id, $fields);
                $stats['updated_titles'][] = $title;
            } else {
                update_post_meta($post_id, 'team_last_synced', $fields['team_last_synced']);
                $stats['skipped_titles'][] = $title;
            }

            continue;
        }

        $new_post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_name' => $post_name,
            'post_status' => 'publish',
            'post_type' => 'teams',
            'post_excerpt' => $fields['team_strapline'],
            'post_content' => $fields['team_long_description'],
            'post_author' => 1,
        ), true);

        if (is_wp_error($new_post_id)) {
            $stats['error_messages'][] = $new_post_id->get_error_message();
            continue;
        }

        vfwp_intranet_update_team_meta($new_post_id, $fields);
        $stats['created_titles'][] = $title;
    }

    foreach ($existing_team_ids as $team_id => $post_id) {
        if (!in_array($team_id, $api_team_ids, true)) {
            $stats['deleted_titles'][] = get_the_title($post_id);
            wp_delete_post($post_id, true);
        }
    }

    update_option('vfwp_teams_sync_stats', $stats);
    update_option('vfwp_teams_last_sync_time', current_time('mysql', true));
    delete_option('vfwp_teams_last_sync_error');
    delete_transient(VFWP_INTRANET_TEAMS_SYNC_LOCK);

    return $stats;
}

function vfwp_intranet_get_contenthub_teams() {
    $response = wp_remote_get(VFWP_INTRANET_TEAMS_SYNC_ENDPOINT, array(
        'timeout' => 30,
        'headers' => array(
            'Accept' => 'application/json',
        ),
    ));

    if (is_wp_error($response)) {
        return $response;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code < 200 || $status_code >= 300) {
        return new WP_Error(
            'vfwp_teams_sync_http_error',
            sprintf(__('ContentHub teams API returned HTTP %d.', 'vfwp'), $status_code)
        );
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($data)) {
        return new WP_Error('vfwp_teams_sync_invalid_json', __('ContentHub teams API returned invalid JSON.', 'vfwp'));
    }

    if (isset($data['rows']) && is_array($data['rows'])) {
        return vfwp_intranet_validate_contenthub_teams_list($data['rows']);
    }

    if (isset($data['data']) && is_array($data['data'])) {
        return vfwp_intranet_validate_contenthub_teams_list($data['data']);
    }

    if (isset($data['items']) && is_array($data['items'])) {
        return vfwp_intranet_validate_contenthub_teams_list($data['items']);
    }

    if (vfwp_intranet_array_is_list($data)) {
        return vfwp_intranet_validate_contenthub_teams_list($data);
    }

    return new WP_Error('vfwp_teams_sync_unexpected_shape', __('ContentHub teams API response did not contain a team list.', 'vfwp'));
}

function vfwp_intranet_validate_contenthub_teams_list($teams) {
    if (empty($teams)) {
        return new WP_Error('vfwp_teams_sync_empty_response', __('ContentHub teams API returned an empty team list; sync was stopped to avoid deleting existing teams.', 'vfwp'));
    }

    return $teams;
}

function vfwp_intranet_array_is_list($array) {
    if (!is_array($array)) {
        return false;
    }

    $expected_key = 0;
    foreach ($array as $key => $_value) {
        if ($key !== $expected_key) {
            return false;
        }
        $expected_key++;
    }

    return true;
}

function vfwp_intranet_normalize_team_fields($team) {
    return array(
        'team_api_id' => isset($team['team_id']) ? sanitize_text_field((string) $team['team_id']) : '',
        'team_name' => isset($team['team_name']) ? sanitize_text_field((string) $team['team_name']) : '',
        'team_url' => isset($team['team_url']) ? esc_url_raw((string) $team['team_url']) : '',
        'team_leader_name' => isset($team['team_leader_name']) ? sanitize_text_field((string) $team['team_leader_name']) : '',
        'team_leader_photo' => isset($team['team_leader_photo']) ? esc_url_raw((string) $team['team_leader_photo']) : '',
        'team_strapline' => isset($team['team_strapline']) ? sanitize_textarea_field((string) $team['team_strapline']) : '',
        'team_long_description' => isset($team['team_long_description']) ? wp_kses_post((string) $team['team_long_description']) : '',
        'team_sync_source' => VFWP_INTRANET_TEAMS_SYNC_SOURCE,
        'team_last_synced' => current_time('mysql', true),
    );
}

function vfwp_intranet_get_team_slug($team_url, $fallback_title) {
    $path = $team_url ? parse_url($team_url, PHP_URL_PATH) : '';
    $slug = $path ? basename(untrailingslashit($path)) : '';

    if ($slug === '') {
        $slug = $fallback_title;
    }

    return sanitize_title($slug);
}

function vfwp_intranet_team_post_has_changes($post_id, $fields, $title, $post_name) {
    $post = get_post($post_id);
    if (!$post) {
        return true;
    }

    if ($post->post_title !== $title || $post->post_name !== $post_name || $post->post_excerpt !== $fields['team_strapline'] || $post->post_content !== $fields['team_long_description']) {
        return true;
    }

    foreach ($fields as $key => $value) {
        if ($key === 'team_last_synced') {
            continue;
        }

        if ((string) get_post_meta($post_id, $key, true) !== (string) $value) {
            return true;
        }
    }

    return false;
}

function vfwp_intranet_update_team_meta($post_id, $fields) {
    foreach ($fields as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}

add_action('rest_api_init', 'vfwp_intranet_register_teams_sync_routes');
function vfwp_intranet_register_teams_sync_routes() {
    register_rest_route('vfwp/v1', '/sync-teams', array(
        'methods' => 'POST',
        'callback' => 'vfwp_intranet_rest_sync_teams',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));

    register_rest_route('vfwp/v1', '/get-teams-sync-stats', array(
        'methods' => 'GET',
        'callback' => 'vfwp_intranet_rest_get_teams_sync_stats',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
}

function vfwp_intranet_rest_sync_teams(WP_REST_Request $request) {
    $stats = vfwp_intranet_sync_teams_from_contenthub();

    if (is_wp_error($stats)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => $stats->get_error_message(),
        ), $stats->get_error_code() === 'vfwp_teams_sync_locked' ? 429 : 500);
    }

    return new WP_REST_Response(array(
        'success' => true,
        'message' => __('Teams data synced successfully.', 'vfwp'),
        'stats' => vfwp_intranet_format_teams_sync_stats($stats),
        'last_sync_time' => get_option('vfwp_teams_last_sync_time', 'Never'),
    ), 200);
}

function vfwp_intranet_rest_get_teams_sync_stats() {
    return new WP_REST_Response(array(
        'completed' => !get_transient(VFWP_INTRANET_TEAMS_SYNC_LOCK),
        'stats' => vfwp_intranet_format_teams_sync_stats(get_option('vfwp_teams_sync_stats', array())),
        'last_sync_time' => get_option('vfwp_teams_last_sync_time', 'Never'),
        'last_error' => get_option('vfwp_teams_last_sync_error', ''),
    ), 200);
}

function vfwp_intranet_format_teams_sync_stats($stats) {
    $stats = is_array($stats) ? $stats : array();

    $created_titles = isset($stats['created_titles']) && is_array($stats['created_titles']) ? $stats['created_titles'] : array();
    $updated_titles = isset($stats['updated_titles']) && is_array($stats['updated_titles']) ? $stats['updated_titles'] : array();
    $deleted_titles = isset($stats['deleted_titles']) && is_array($stats['deleted_titles']) ? $stats['deleted_titles'] : array();
    $skipped_titles = isset($stats['skipped_titles']) && is_array($stats['skipped_titles']) ? $stats['skipped_titles'] : array();
    $error_messages = isset($stats['error_messages']) && is_array($stats['error_messages']) ? $stats['error_messages'] : array();

    return array(
        'created' => count($created_titles),
        'updated' => count($updated_titles),
        'deleted' => count($deleted_titles),
        'skipped' => count($skipped_titles),
        'created_titles' => $created_titles,
        'updated_titles' => $updated_titles,
        'deleted_titles' => $deleted_titles,
        'skipped_titles' => $skipped_titles,
        'error_messages' => $error_messages,
    );
}

add_action('admin_enqueue_scripts', 'vfwp_intranet_enqueue_teams_admin_script');
function vfwp_intranet_enqueue_teams_admin_script($hook) {
    if (!current_user_can('manage_options') || $hook !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'teams') {
        return;
    }

    wp_enqueue_script(
        'vfwp-teams-admin',
        get_theme_file_uri('/scripts/teams/team-admin.js'),
        array('jquery'),
        filemtime(get_theme_file_path('/scripts/teams/team-admin.js')),
        true
    );

    wp_localize_script('vfwp-teams-admin', 'vfwpTeamsSettings', array(
        'apiRoot' => esc_url_raw(rest_url('vfwp/v1/')),
        'token' => wp_create_nonce('wp_rest'),
        'messages' => array(
            'syncing' => __('Syncing teams from ContentHub...', 'vfwp'),
            'error' => __('There was an error syncing teams.', 'vfwp'),
        ),
    ));
}

add_action('admin_notices', 'vfwp_intranet_render_teams_sync_notice');
function vfwp_intranet_render_teams_sync_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'edit-teams') {
        return;
    }

    $last_sync = vfwp_intranet_format_teams_last_sync_time(get_option('vfwp_teams_last_sync_time', 'Never'));
    $last_error = get_option('vfwp_teams_last_sync_error', '');

    echo '<div class="notice notice-info vfwp-teams-sync-notice" style="padding-bottom:12px;">';
    echo '<p><span>' . sprintf(__('Last ContentHub teams sync: %s', 'vfwp'), esc_html($last_sync)) . '</span></p>';
    echo '<p>' . esc_html__('Only Teams marked as ContentHub-managed are updated or deleted by this sync. Manually-created Teams are left alone.', 'vfwp') . '</p>';
    if ($last_error) {
        echo '<p><strong>' . esc_html__('Last sync error:', 'vfwp') . '</strong> ' . esc_html($last_error) . '</p>';
    }
    echo '<button id="vfwp-sync-teams" class="button button-primary">' . esc_html__('Sync teams data', 'vfwp') . '</button>';
    echo '</div>';
}

function vfwp_intranet_format_teams_last_sync_time($last_sync_utc) {
    if ($last_sync_utc === 'Never' || $last_sync_utc === '') {
        return __('Never', 'vfwp');
    }

    try {
        $datetime = new DateTime($last_sync_utc, new DateTimeZone('UTC'));
        $timezone = wp_timezone();
        $datetime->setTimezone($timezone);

        return $datetime->format('d/m/Y H:i:s');
    } catch (Exception $exception) {
        return $last_sync_utc;
    }
}
