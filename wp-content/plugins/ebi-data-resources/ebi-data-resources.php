<?php

/*
Plugin Name: EBI Data Resources
Description: A custom plugin that generates a new entity named "Data Resources".
Version: 1.0
Author: Maged Eladawy <maged@ebi.ac.uk>
 */
require 'export-handler.php';
require 'popularity-validator.php';
require 'licence-log-handler.php';
require 'special-field-handlers.php';

function ebi_data_resources_custom_post_type()
{
    $labels = [
        'name'               => __('Data resources and tools'),
        'singular_name'      => __('Data Resource'),
        'add_new'            => __('Add new resource'),
        'add_new_item'       => __('Add new resource'),
        'edit_item'          => __('Edit resource'),
        'new_item'           => __('New resource'),
        'view_item'          => __('View resource'),
        'search_items'       => __('Search resources'),
        'not_found'          => __('No resources found'),
        'not_found_in_trash' => __('No resources found in Trash'),
    ];

    $args = [
        'capability_type'   => 'data_resource',
        'capabilities'      => [
            'edit_post'          => 'edit_data_resource',
            'read_post'          => 'read_data_resource',
            'delete_post'        => 'delete_data_resource',
            'edit_posts'         => 'edit_data_resources',
            'edit_others_posts'  => 'edit_others_data_resources',
            'publish_posts'      => 'publish_data_resources',
            'read_private_posts' => 'read_private_data_resources',
            'create_posts'       => 'create_data_resources',
        ],
        'map_meta_cap'      => true,
        'labels'            => $labels,
        'public'            => true,
        'show_in_rest'      => true,
        'show_in_menu'      => true,
        'show_in_admin_bar' => true,
        'show_ui'           => true,
        'rest_base'         => 'data-resources',
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'data-resources'],
        'supports'          => ['title', 'editor', 'thumbnail'],
        'menu_icon'         => 'dashicons-book',
        // Use a Dashicons icon, see: https://developer.wordpress.org/resource/dashicons/
    ];

    register_post_type('data-resource', $args);

    ebi_ata_resource_add_roles_capabilities();
}

add_action('init', 'ebi_data_resources_custom_post_type');

function ebi_ata_resource_add_roles_capabilities()
{
    $capabilities = [
        'read',
        'create_data_resources',
        'delete_data_resources',
        'delete_others_data_resources',
        'delete_private_data_resources',
        'delete_published_data_resources',
        'edit_data_resources',
        'edit_others_data_resources',
        'edit_private_data_resources',
        'edit_published_data_resources',
        'publish_data_resources',
        'read_private_data_resources',
    ];

    $editor_excluded_capabilities = [
        'delete_data_resources',
        'delete_others_data_resources',
        'delete_private_data_resources',
        'delete_published_data_resources',
    ];

    $admin_role            = get_role('administrator');
    $resources_admin_role  = get_role('resources_admin');
    $resources_editor_role = get_role('resources_editor');

    foreach ($capabilities as $cap) {
        $admin_role->add_cap($cap);
        $resources_admin_role->add_cap($cap);
        $resources_editor_role->add_cap($cap);
    }

    foreach ($editor_excluded_capabilities as $cap) {
        $resources_editor_role->remove_cap($cap);
    }
}

// Add custom user roles: Resources Editor and Resources Admin
function ebi_data_resources_add_custom_roles()
{
    // Create the 'resources_admin' role
    add_role('resources_admin', 'Resource Admin');

    // Create the 'resources_editor' role
    add_role('resources_editor', 'Resource Editor');

    // Create the 'it_helpdesk' role
    add_role(
        'it_helpdesk',
        'IT Helpdesk',
        array(
            'read'         => true,
            'list_users'   => true,
            'create_users' => false,
            'edit_users'   => true,
            'delete_users' => false,
            'promote_users' => true
        )
    );
}
register_activation_hook(__FILE__, 'ebi_data_resources_add_custom_roles');


// Limit IT Helpdesk from managing certain roles
function modify_editable_roles($roles) {
    $user = wp_get_current_user();

    if(isset($user->roles[0]) && $user->roles[0] == 'it_helpdesk'){
        $allowed_roles = ['resources_admin', 'resources_editor', 'subscriber', 'it_helpdesk']; // Only these roles are allowed

        foreach($roles as $role_key => $role){
            if(!in_array($role_key, $allowed_roles)){
                unset($roles[$role_key]);
            }
        }
    }

    return $roles;
}
add_filter('editable_roles', 'modify_editable_roles');

// Limit IT Helpdesk from assigning certain roles
function limit_assignable_roles($all_roles){
    $user = wp_get_current_user();

    if(isset($user->roles[0]) && $user->roles[0] == 'it_helpdesk'){
        $allowed_roles = ['resources_admin', 'resources_editor', 'subscriber', 'it_helpdesk']; // Only these roles are allowed

        foreach($all_roles as $role_key => $role){
            if(!in_array($role_key, $allowed_roles)){
                unset($all_roles[$role_key]);
            }
        }
    }

    return $all_roles;
}
add_filter('map_meta_cap', 'limit_assignable_roles', 10, 4);

// Remove custom user roles when the plugin is deactivated
function ebi_data_resources_remove_custom_roles()
{
    remove_role('resources_editor');
    remove_role('resources_admin');
    remove_role('it_helpdesk');
}

register_deactivation_hook(
    __FILE__,
    'ebi_data_resources_remove_custom_roles'
);

// Set the ACF JSON save point to the plugin folder
function ebi_data_resources_acf_json_save_point($path)
{
    $path = plugin_dir_path(__FILE__).'acf-json';

    return $path;
}

add_filter(
    'acf/settings/save_json',
    'ebi_data_resources_acf_json_save_point'
);

// Load ACF JSON data from the plugin folder
function ebi_data_resources_acf_json_load_point($paths)
{
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__).'acf-json';

    return $paths;
}

add_filter(
    'acf/settings/load_json',
    'ebi_data_resources_acf_json_load_point'
);

function create_embl_teams_taxonomy()
{
    $labels = [
        'name'              => _x(
            'EMBL Teams',
            'taxonomy general name',
            'textdomain'
        ),
        'singular_name'     => _x(
            'EMBL Team',
            'taxonomy singular name',
            'textdomain'
        ),
        'search_items'      => __('Search EMBL Teams', 'textdomain'),
        'all_items'         => __('All EMBL Teams', 'textdomain'),
        'parent_item'       => __('Parent EMBL Team', 'textdomain'),
        'parent_item_colon' => __('Parent EMBL Team:', 'textdomain'),
        'edit_item'         => __('Edit EMBL Team', 'textdomain'),
        'update_item'       => __('Update EMBL Team', 'textdomain'),
        'add_new_item'      => __('Add New EMBL Team', 'textdomain'),
        'new_item_name'     => __('New EMBL Team Name', 'textdomain'),
        'menu_name'         => __('EMBL Teams', 'textdomain'),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'rewrite'           => ['slug' => 'embl-teams'],
    ];

    register_taxonomy('embl_teams', ['page'], $args);
}

add_action('init', 'create_embl_teams_taxonomy');

function embl_teams_taxonomy_add_custom_field()
{
    ?>
    <div class="form-field">
        <label for="tag-team-id"><?php
            _e('Team ID'); ?></label>
        <input type="text" name="team_id" id="tag-team-id" value=""/>
        <p><?php
            _e('Enter a unique Team ID for this EMBL Team.'); ?></p>
    </div>

    <div class="form-field">
        <label for="tag-team-leader-name"><?php
            _e('Team Leader Name'); ?></label>
        <input type="text" name="team_leader_name" id="tag-team-leader-name"
               value=""/>
        <p><?php
            _e('Enter the name of the team leader.'); ?></p>
    </div>

    <div class="form-field">
        <label for="tag-team-url"><?php
            _e('Team URL'); ?></label>
        <input type="text" name="team_url" id="tag-team-url" value=""/>
        <p><?php
            _e('Enter the URL of the team\'s page.'); ?></p>
    </div>
    <?php
}

add_action(
    'embl_teams_add_form_fields',
    'embl_teams_taxonomy_add_custom_field'
);

function embl_teams_taxonomy_save_custom_field($term_id)
{
    if (isset($_POST['team_id'])) {
        update_term_meta(
            $term_id,
            'team_id',
            sanitize_text_field($_POST['team_id'])
        );
    }
}

add_action('created_embl_teams', 'embl_teams_taxonomy_save_custom_field');

function embl_teams_taxonomy_edit_custom_field($term)
{
    $team_id          = get_term_meta($term->term_id, 'team_id', true);
    $team_leader_name = get_term_meta(
        $term->term_id,
        'team_leader_name',
        true
    );
    $team_url         = get_term_meta($term->term_id, 'team_url', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag-team-id"><?php
                _e('Team ID'); ?></label></th>
        <td>
            <input type="text" name="team_id" id="tag-team-id" value="<?php
            echo esc_attr($team_id); ?>"/>
            <p class="description"><?php
                _e('Enter a unique Team ID for this EMBL Team.'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag-team-leader-name"><?php
                _e('Team Leader Name'); ?></label></th>
        <td>
            <input type="text" name="team_leader_name" id="tag-team-leader-name"
                   value="<?php
                   echo esc_attr($team_leader_name); ?>"/>
            <p class="description"><?php
                _e('Enter the name of the team leader.'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag-team-url"><?php
                _e('Team URL'); ?></label></th>
        <td>
            <input type="text" name="team_url" id="tag-team-url" value="<?php
            echo esc_attr($team_url); ?>"/>
            <p class="description"><?php
                _e('Enter the URL of the team\'s page.'); ?></p>
        </td>
    </tr>
    <?php
}

add_action(
    'embl_teams_edit_form_fields',
    'embl_teams_taxonomy_edit_custom_field'
);

function embl_teams_taxonomy_update_custom_field($term_id)
{
    if (isset($_POST['team_id'])) {
        update_term_meta(
            $term_id,
            'team_id',
            sanitize_text_field($_POST['team_id'])
        );
    }
    if (isset($_POST['team_leader_name'])) {
        update_term_meta(
            $term_id,
            'team_leader_name',
            sanitize_text_field($_POST['team_leader_name'])
        );
    }
    if (isset($_POST['team_url'])) {
        update_term_meta(
            $term_id,
            'team_url',
            sanitize_text_field($_POST['team_url'])
        );
    }
}

add_action('edited_embl_teams', 'embl_teams_taxonomy_update_custom_field');

function sync_embl_teams_taxonomy()
{
    $api_url = 'https://www.embl.org/api/v1/team-profiles?source=contenthub';

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log(
            'Error fetching data from EMBL API: '
            .$response->get_error_message()
        );

        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if ( ! isset($data['rows'])) {
        error_log('Error parsing data from EMBL API: Invalid JSON structure');

        return;
    }

    foreach ($data['rows'] as $row) {
        if (isset($row['team_id']) && isset($row['team_name'])) {
            // Check if a term with the same team_id already exists
            $existing_terms = get_terms([
                'taxonomy'   => 'embl_teams',
                'meta_query' => [
                    [
                        'key'     => 'team_id',
                        'value'   => $row['team_id'],
                        'compare' => '=',
                    ],
                ],
                'hide_empty' => false,
            ]);

            if (count($existing_terms) > 0) {
                // If the term already exists, update the term name and description (if needed)
                wp_update_term($existing_terms[0]->term_id, 'embl_teams', [
                    'name'        => $row['team_name'],
                    'description' => isset($row['team_strapline'])
                        ? $row['team_strapline'] : '',
                ]);
                // Update the term meta.
                update_term_meta(
                    $existing_terms[0]->term_id,
                    'team_id',
                    $row['team_id']
                );
                update_term_meta(
                    $existing_terms[0]->term_id,
                    'team_leader_name',
                    $row['team_leader_name']
                );
                update_term_meta(
                    $existing_terms[0]->term_id,
                    'team_url',
                    $row['team_url']
                );
            } else {
                // If the term doesn't exist, create a new term
                $new_term = wp_insert_term(
                    $row['team_name'],
                    'embl_teams',
                    [
                        'description' => isset($row['team_strapline'])
                            ? $row['team_strapline'] : '',
                    ]
                );

                if ( ! is_wp_error($new_term)) {
                    update_term_meta(
                        $new_term['term_id'],
                        'team_id',
                        $row['team_id']
                    );
                    update_term_meta(
                        $new_term['term_id'],
                        'team_leader_name',
                        $row['team_leader_name']
                    );
                    update_term_meta(
                        $new_term['term_id'],
                        'team_url',
                        $row['team_url']
                    );
                }
            }
        }
    }
}

function schedule_embl_teams_sync()
{
    if ( ! wp_next_scheduled('embl_teams_sync_event')) {
        wp_schedule_event(time(), 'daily', 'embl_teams_sync_event');
    }
}

add_action('wp_loaded', 'schedule_embl_teams_sync');

function embl_teams_sync_callback()
{
    sync_embl_teams_taxonomy();
}

add_action('embl_teams_sync_event', 'embl_teams_sync_callback');

function add_embl_teams_sync_button()
{
    ?>
    <button type="button" id="sync_embl_teams"
            class="button button-primary"><?php
        _e('Sync EMBL Teams', 'your-textdomain'); ?></button>
    <script>
        jQuery(document).ready(function ($) {
            $('#sync_embl_teams').on('click', function () {
                location.href = '<?php echo admin_url(
                    'edit-tags.php?taxonomy=embl_teams&post_type=post&sync_embl_teams=1&_wpnonce='
                    .wp_create_nonce('sync_embl_teams_nonce')
                ); ?>';
            });
        });
    </script>
    <?php
}

add_action('embl_teams_pre_add_form', 'add_embl_teams_sync_button');

function handle_embl_teams_manual_sync()
{
    if ( ! empty($_GET['sync_embl_teams'])
         && check_admin_referer('sync_embl_teams_nonce')
    ) {
        sync_embl_teams_taxonomy();
        add_action('admin_notices', 'embl_teams_sync_success_notice');
    }
}

add_action('admin_init', 'handle_embl_teams_manual_sync');

// Add init function to create the role 'it_helpdesk' if it doesn't exist.
function create_it_helpdesk_role()
{
    $role = get_role('it_helpdesk');

    if ( ! $role) {
        add_role(
            'it_helpdesk',
            'IT Helpdesk',
            array(
                'read'         => true,
                'list_users'   => true,
                'create_users' => false,
                'edit_users'   => true,
                'delete_users' => false,
                'promote_users' => true
            )
        );
    }
}
add_action('init', 'create_it_helpdesk_role');

// Filter the list of users displayed in the admin
function filter_user_query( $query ) {
    global $wpdb;
    $blog_id = get_current_blog_id();

    $user = wp_get_current_user();

    // If the user has the "IT Helpdesk" role
    if ( isset( $user->roles[0] ) && $user->roles[0] == 'it_helpdesk' ) {

        // Set the allowed roles
        $allowed_roles = array( 'resources_admin', 'resources_editor', 'subscriber', 'it_helpdesk');

        // Set the excluded roles
        $excluded_roles = array( 'group_admin', 'group_editor', 'administrator');

        // Add a meta query to only display users with the allowed roles
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
                'compare' => 'NOT EXISTS' // this takes care of the users without a role
            ),
            array(
                'relation' => 'AND'
            )
        );

        // Include allowed roles
        $include_query = array(
            'relation' => 'OR'
        );
        foreach ($allowed_roles as $role) {
            $include_query[] = array(
                'key'     => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
                'value'   => '"'.$role.'"',
                'compare' => 'LIKE'
            );
        }
        $meta_query[1][] = $include_query;

        // Exclude certain roles
        foreach ($excluded_roles as $role) {
            $meta_query[1][] = array(
                'key'     => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
                'value'   => '"'.$role.'"',
                'compare' => 'NOT LIKE'
            );
        }

        $query->set( 'meta_query', $meta_query);
    }
}
add_action( 'pre_get_users', 'filter_user_query' );




function embl_teams_sync_success_notice()
{
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php
            _e(
                'EMBL Teams taxonomy has been successfully synced!',
                'your-textdomain'
            ); ?></p>
    </div>
    <?php
}

function get_acf_fields_by_group_key($group_key)
{
    // Check if ACF is installed and activated
    if (function_exists('acf_get_fields')) {
        return acf_get_fields($group_key);
    }

    return [];
}

function get_acf_fields_by_group_title($group_title)
{
    $field_groups = acf_get_field_groups();
    if ( ! empty($field_groups)) {
        foreach ($field_groups as $group) {
            if ($group['title'] === $group_title) {
                return acf_get_fields($group['key']);
            }
        }
    }

    return [];
}

function get_acf_field_value($field_name, $post_id)
{
    // Check if ACF is installed and activated
    if (function_exists('get_field')) {
        $taxonomy_fields = [
            'resource_primary_team',
            'resource_additional_teams',
            'resource_teams'
        ];

        if (in_array($field_name, $taxonomy_fields)) {
            $terms = get_field($field_name, $post_id);

            if (is_array($terms)) {
                foreach ($terms as $key => $term) {
                    if (is_object($term)) {
                        $terms[$key]->team_id = get_term_meta(
                            $term->term_id,
                            'team_id',
                            true
                        );
                        $terms[$key]->team_leader_name
                                              = get_term_meta(
                            $term->term_id,
                            'team_leader_name',
                            true
                        );
                        $terms[$key]->team_url
                                              = get_term_meta(
                            $term->term_id,
                            'team_url',
                            true
                        );
                    }
                }
            } elseif (is_object($terms)) {
                $terms->team_id          = get_term_meta(
                    $terms->term_id,
                    'team_id',
                    true
                );
                $terms->team_leader_name = get_term_meta(
                    $terms->term_id,
                    'team_leader_name',
                    true
                );
                $terms->team_url         = get_term_meta(
                    $terms->term_id,
                    'team_url',
                    true
                );
            }

            if ($terms) {
                return $terms;
            } else {
                return null;
            }
        } else {
            return get_field($field_name, $post_id);
        }
    }

    return null;
}


function register_data_resource_acf_fields_in_rest_api()
{
    // Fetch ACF fields by group key
    $acf_fields = get_acf_fields_by_group_key('group_641995b31b99d');

    // Alternatively, fetch ACF fields by group title
    // $acf_fields = get_acf_fields_by_group_title('Data Resources');

    if ( ! empty($acf_fields)) {
        foreach ($acf_fields as $field) {
            register_rest_field(
                'data-resource',
                $field['name'],
                [
                    'get_callback' => function ($post_data) use ($field) {
                        return get_acf_field_value(
                            $field['name'],
                            $post_data['id']
                        );
                    },
                    'schema'       => [
                        'description' => "ACF field '{$field['name']}'",
                        'type'        => 'mixed',
                    ],
                ]
            );
        }
    }
}

add_action('rest_api_init', 'register_data_resource_acf_fields_in_rest_api');

add_filter('manage_data-resource_posts_columns', 'add_data_resource_columns');

function add_data_resource_columns($columns)
{
    $columns['display_name']          = 'Display name';
    $columns['resource_primary_team'] = 'Primary team';
    $columns['post_date']             = 'Added on';
    $columns['post_modified']         = 'Last modified';
    unset($columns['date']);

    return $columns;
}


add_action(
    'manage_data-resource_posts_custom_column',
    'populate_data_resource_columns',
    10,
    2
);

function populate_data_resource_columns($column, $data_resource_id)
{
    if ($column === 'display_name') {
        echo get_post_meta($data_resource_id, 'display_name', true);
    }
    if ($column === 'resource_primary_team') {
        $primary_team_object = get_field(
            'resource_primary_team',
            $data_resource_id
        );
        echo $primary_team_object->name;
    }
    if ($column === 'post_date') {
        echo get_the_date('d/m/Y', $data_resource_id).' at '
             .get_the_date('H:i', $data_resource_id);
    }
    if ($column === 'post_modified') {
        echo get_the_modified_date('d/m/Y', $data_resource_id).' at '
             .get_the_modified_date('H:i', $data_resource_id);
    }
}

add_filter(
    'manage_edit-data-resource_sortable_columns',
    'make_primary_team_column_sortable'
);
function make_primary_team_column_sortable($columns)
{
    $columns['post_date']             = 'post_date';
    $columns['post_modified']         = 'post_modified';
    $columns['display_name']          = 'display_name';
    $columns['resource_primary_team'] = 'resource_primary_team';

    return $columns;
}

add_action('pre_get_posts', 'sort_by_primary_team_column');
function sort_by_primary_team_column($query)
{
    if ( ! is_admin() && ! $query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('display_name' == $orderby) {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'display_name');
        $query->set('meta_type', 'CHAR');
    }

    if ('resource_primary_team' == $orderby) {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'resource_primary_team');
        $query->set('meta_type', 'CHAR');
    }
}


add_action('parse_query', 'search_data_resources');
function search_data_resources($query)
{
    global $pagenow, $post_type;

    if ( ! is_admin() || $pagenow != 'edit.php'
         || $query->query['post_type'] != $post_type
         || $query->is_main_query()
    ) {
        return;
    }

    if (isset($query->query_vars['s'])
        && ! empty($query->query_vars['s'])
    ) {
        $meta_query = [
            'relation' => 'OR',
            [
                'key'     => 'display_name',
                'value'   => $query->query_vars['s'],
                'compare' => 'LIKE',
            ],

            [
                'key'     => 'resource_primary_team',
                'value'   => $query->query_vars['s'],
                'compare' => 'LIKE',
            ],
        ];
        $query->set('meta_query', $meta_query);
    }
}

function custom_js() {
    wp_enqueue_script(
        'ebi_data_resources_popularity_validator',
        plugins_url('/js/ebi-data-resources/popularity-validator.js', __FILE__),
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'ebi_data_resources_export_handler',
        plugins_url('/js/ebi-data-resources/export-handler.js', __FILE__),
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'ebi_data_resources_special_field_handlers',
        plugins_url(
            '/js/ebi-data-resources/special-field-handlers.js',
            __FILE__
        ),
        ['jquery'],
        '1.0',
        true
    );

    // Localize the script and pass the base path to admin-ajax.php
    wp_localize_script(
        'ebi_data_resources_export_handler',
        'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}

add_action('admin_head', 'custom_js');

function hide_admin_fields() {
    $screen = get_current_screen();

    // Check if the post type is 'data_resource'
    if ( 'data-resource' == $screen->post_type ) {
        echo '
        <style type="text/css">
            .block-editor-writing-flow {
                display: none;
            }
        </style>';
    }
}

add_action( 'admin_head', 'hide_admin_fields' );

/**
 * Function to bulk update of Data Resource for Popular flag.
 * Use /wp-admin/admin-post.php?action=data_resource_remove_popular to run this
 */
function vfwp_data_resource_bulk_update() {
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( 'Not enough permissions' );
    }
  
    $args = array(
        'post_type' => 'data-resource',
        'posts_per_page' => -1,
        'hide_empty' => false,
        'meta_query'     => [
            [
                'key'     => 'resource_popular',
                'value'   => 1,
                'compare' => '=',
            ]
        ],
    );
  
    $query = new WP_Query( $args );
    if($query->have_posts()) {
      echo "<pre>";
      echo "<b>Updating records</b><br><br>";
      while ($query->have_posts()) : $query->the_post();
        the_title();
        echo "<br>";
        update_field('resource_popular', 0, get_the_ID());
        wp_update_post(array('ID' => get_the_ID()));
      endwhile;
      echo "</pre>";
    }
  
    $args['meta_query'] = [[
          'key'     => 'resource_popular',
          'value'   => 1,
          'compare' => '=']];
    $query_after_update = new WP_Query( $args );
    if(!$query_after_update->have_posts()) {
      wp_die( '<b>No more popular data resources found after update</b>', 'Bulk Update');
    }
  }
  
  add_action( 'admin_post_data_resource_remove_popular', 'vfwp_data_resource_bulk_update' );