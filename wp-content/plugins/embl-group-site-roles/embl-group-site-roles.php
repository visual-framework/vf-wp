<?php
/*
* Plugin Name: EMBL Group Site Roles
* Description: Custom roles setup for EMBL Groups websites
* Version: 0.0.1
* Text Domain: embl-group-site-roles
* Author: Joseph Rossetto <jros@ebi.ac.uk>
*/

// Make sure we don't expose any info if called directly
defined( 'ABSPATH' ) or die( 'Access denied.' );

define( 'EGSR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( EGSR_PLUGIN_DIR . './define-constants.php' );

register_deactivation_hook( __FILE__, 'egsr_deactivation' );    // Register deactivation
add_action( 'deactivated_plugin', 'egsr_deactivation_redirect' );

register_activation_hook( __FILE__, 'egsr_activation' );    // Register activation

add_action( 'activated_plugin', 'egsr_activation_redirect' );

require_once( EGSR_PLUGIN_DIR . './embl-group-site-roles.admin.php' );

add_action('admin_menu', 'egsr_admin_menu');

add_action('init', 'egsr_add_group_site_roles');         // Add custom roles

add_action('init', 'egsr_remove_wp_roles');   // Remove default WP roles

// ==========================================================================

/*
* Behaviour of plugin during activation: check if users with WP default roles exist already
*/
function egsr_deactivation(){
    egsr_add_default_wp_roles();
}

/*
* Behaviour of plugin during activation: check if users with WP default roles exist already
*/
function egsr_activation(){
    $wp_roles = new WP_Roles();

    // $wp_site_roles = egsr_custom_site_roles();
    $wp_site_roles = egsr_default_wp_site_roles();
    $user_exist = false;
    foreach ($wp_site_roles as $key => $value) {
        $role = get_role($key);
        if(isset($role->name) == $key){         // Check if Role exists already
            $args = array(
                'role'    => $key,
            );
            $users = get_users($args);
            if(count($users) > 0 ){            // Check if no Users with current role exist
                $user_exist = true;
            }
        }
    }

    if($user_exist){
        $site_url = get_site_url();
        $body_msg = '<p>In EMBL Group websites, default Wordpress roles "Author", "Contributor", "Editor" and "Subscriber" will be deprecated in favour of EMBL custom ones.</p>';
        $error_msg = '<p class="error">
        This plugin cannot be activated because users with either one of the above-mentioned Wordpress roles are active. <br>You can temporarily set those users as having no roles, and after activating the plugin replace them with EMBL custom ones:
        "EMBL Group Admin", "EMBL Group Editor" or "EMBL Staff"</p><p><a href="' . $site_url . '/wp-admin/users.php">Users List Page&raquo;</a></p>';
        deactivate_plugins( basename( __FILE__ ) );
        wp_die( '<h1>Error</h1>' . $body_msg . $error_msg, 'Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
    }
}

function egsr_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        // Redirects to /wp-admin/admin.php?page=embl-group-site-roles&egsr-activation=true.
        wp_safe_redirect( add_query_arg( array( 'page' => EGSR_CONFIG_PREFIX, EGSR_ACTIVATION => 'true' ), admin_url( 'users.php' ) ) );

        exit;
    }
}

function egsr_deactivation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        // Redirects to /wp-admin/admin.php?page=embl-group-site-roles&egsr-activation=true.
        // wp_safe_redirect( add_query_arg( array( 'page' => EGSR_CONFIG_PREFIX, EGSR_DEACTIVATION => 'true' ), admin_url( 'users.php' ) ) );

        // exit;

    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
//  WP Roles & capabilities overview https://wordpress.org/support/article/roles-and-capabilities/  //
//////////////////////////////////////////////////////////////////////////////////////////////////////
/*
* Add EMBL custom roles
*/
function egsr_add_group_site_roles()
{
    // Exit if script is run from other than Admin
    if ( !current_user_can('administrator') ) {
        return;
    }
    $group_site_roles = egsr_custom_site_roles();
    foreach ($group_site_roles as $key => $value) {
        $role = get_role($key);
        // Check if Role doesn't exist already
        if(!isset($role->name) == $key){
            // Add role + capabilities
            add_role(
                $key,
                $value['label'],
                $value['caps']
            );
        }
    }
}

/*
* Add EMBL custom roles
*/
function egsr_add_default_wp_roles()
{
    // Exit if script is run from other than Admin
    if ( !current_user_can('administrator') ) {
        return;
    }
    $group_site_roles = egsr_default_wp_site_roles();
    foreach ($group_site_roles as $key => $value) {
        $role = get_role($key);
        // Check if Role doesn't exist already
        if(!isset($role->name) == $key){
            // Add role + capabilities
            add_role(
                $key,
                $value['label'],
                $value['caps']
            );
        }
    }
}


/*
* Change WP default roles
*/
function egsr_change_roles_names() {
    global $wp_roles;

    if ( ! isset( $wp_roles ) )
    $wp_roles = new WP_Roles();

    $wp_site_roles = egsr_default_wp_site_roles();
    foreach ($wp_site_roles as $key => $value) {
        // Change role name
        $wp_roles->roles[$key]['name'] = $wp_roles->roles[$key]['name'] .' (Deprecated)';
        $wp_roles->role_names['contributor'] = $wp_roles->role_names[$key] .'$wp_roles->roles[$key]';
    }
}

/*
* Add WP default roles
*/
function egsr_remove_wp_roles(){
    $wp_roles = new WP_Roles();
    // $wp_site_roles = egsr_custom_site_roles();
    $wp_site_roles = egsr_default_wp_site_roles();
    foreach ($wp_site_roles as $key => $value) {
        $role = get_role($key);
        if(isset($role->name) == $key){         // Check if Role exists already
            $wp_roles->remove_role($key);   // Remove role + capabilities
        }
    }
}

/*
* Define EMBL custom roles
*/
function egsr_custom_site_roles(){

    return Array (
        // STAFF_ROLE  => Array (
        //     'label' => STAFF_ROLE_LABEL,
        //     'caps' => egsr_staff_cap(),
        // ),
        GROUP_EDITOR_ROLE  => Array (
            'label' => GROUP_EDITOR_ROLE_LABEL,
            'caps' => egsr_group_editor_cap(),
        ),
        GROUP_ADMIN_ROLE  => Array (
            'label' => GROUP_ADMIN_ROLE_LABEL,
            'caps' => egsr_group_admin_cap(),
        ),
    );

}

/*
* Define WP default roles
*/
function egsr_default_wp_site_roles(){

    return Array (
        AUTHOR_ROLE  => Array (
            'label' => AUTHOR_ROLE_LABEL,
            'caps' => egsr_author_cap(),
        ),
        CONTRIBUTOR_ROLE  => Array (
            'label' => CONTRIBUTOR_ROLE_LABEL,
            'caps' => egsr_contributor_cap(),
        ),
        EDITOR_ROLE  => Array (
            'label' => EDITOR_ROLE_LABEL,
            'caps' => egsr_editor_cap(),
        ),
        SUBSCRIBER_ROLE  => Array (
            'label' => SUBSCRIBER_ROLE_LABEL,
            'caps' => egsr_subscriber_cap(),
        ),
    );

}

/*
* EMBL staff role capabilities. Limited permissions
*/
function egsr_staff_cap(){

    return Array (
        'read'  => true,
    );
}

/*
* EMBL Group Member role capabilities (= WP Editor)
*/
function egsr_group_editor_cap(){
    return Array (
        'delete_others_pages'       => true,
        'delete_others_posts'       => true,
        'delete_pages'              => true,
        'delete_posts'              => true,
        'delete_private_pages'      => true,
        'delete_private_posts'      => true,
        'delete_published_pages'    => true,
        'delete_published_posts'    => true,
        'edit_others_pages'         => true,
        'edit_others_posts'         => true,
        'edit_pages'                => true,
        'edit_posts'                => true,
        'edit_private_pages'        => true,
        'edit_private_posts'        => true,
        'edit_published_pages'      => true,
        'edit_published_posts'      => true,
        'manage_categories'         => true,
        'manage_links'              => true,
        'moderate_comments'         => true,
        'publish_pages'             => true,
        'publish_posts'             => true,
        'read'                      => true,
        'read_private_pages'        => true,
        'read_private_posts'        => true,
        'unfiltered_html'           => true,
        'upload_files'              => true,
        'edit_theme_options'        => true,
    );
}


/*
* EMBL Group Admin role capabilities
*/
function egsr_group_admin_cap(){
    return Array (
        'add_users'                 => true,
        'create_users'              => true,
        'delete_users'              => true,
        'delete_others_pages'       => true,
        'delete_others_posts'       => true,
        'delete_pages'              => true,
        'delete_posts'              => true,
        'delete_private_pages'      => true,
        'delete_private_posts'      => true,
        'delete_published_pages'    => true,
        'delete_published_posts'    => true,
        'edit_others_pages'         => true,
        'edit_others_posts'         => true,
        'edit_pages'                => true,
        'edit_posts'                => true,
        'edit_private_pages'        => true,
        'edit_private_posts'        => true,
        'edit_published_pages'      => true,
        'edit_published_posts'      => true,
        'edit_users'                => true,
        'list_users'                => true,
        'manage_categories'         => true,
        'manage_links'              => true,
        'moderate_comments'         => true,
        'publish_pages'             => true,
        'publish_posts'             => true,
        'read'                      => true,
        'read_private_pages'        => true,
        'read_private_posts'        => true,
        'unfiltered_html'           => true,
        'upload_files'              => true,
        'edit_theme_options'        => true,

        // VF-WP theme and plugin capabilities
        'edit_vf_blocks'            => true,
        'publish_vf_blocks'         => true,
        'edit_vf_containers'        => true,
        'publish_vf_containers'     => true,
    );
}



////////////////////////////////////////////////////////////////////////////////
//                  Default WP role's capabilities                            //
////////////////////////////////////////////////////////////////////////////////

/*
* Default WP Admin role capabilities for Author
*/
function egsr_author_cap(){
    return Array (
        'delete_posts'              => true,
        'delete_published_posts'    => true,
        'edit_posts'                => true,
        'edit_published_posts'      => true,
        'publish_posts'             => true,
        'read'                      => true,
        'upload_files'              => true,
    );
}

/*
* Default WP Admin role capabilities for Contributor
*/
function egsr_contributor_cap(){
    return Array (
        'delete_posts'              => true,
        'edit_posts'                => true,
        'read'                      => true,
    );
}


/*
* Default WP Admin role capabilities for Editor
*/
function egsr_editor_cap(){
    return Array (
        'delete_others_pages'       => true,
        'delete_others_posts'       => true,
        'delete_pages'              => true,
        'delete_posts'              => true,
        'delete_private_pages'      => true,
        'delete_private_posts'      => true,
        'delete_published_pages'    => true,
        'delete_published_posts'    => true,
        'edit_others_pages'         => true,
        'edit_others_posts'         => true,
        'edit_pages'                => true,
        'edit_posts'                => true,
        'edit_private_pages'        => true,
        'edit_private_posts'        => true,
        'edit_published_pages'      => true,
        'edit_published_posts'      => true,
        'manage_categories'         => true,
        'manage_links'              => true,
        'moderate_comments'         => true,
        'publish_pages'             => true,
        'publish_posts'             => true,
        'read'                      => true,
        'read_private_pages'        => true,
        'read_private_posts'        => true,
        'unfiltered_html'           => true,
        'upload_files'              => true,
    );
}

/*
* Default WP role capabilities for Subscriber
*/
function egsr_subscriber_cap(){
    return Array (
        'read'  => true,
    );
}
