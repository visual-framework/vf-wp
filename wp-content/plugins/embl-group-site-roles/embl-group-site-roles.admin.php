<?php

/**
 * Project: EMBL Group Site Roles
 * Admin page
 *  
 * Author: EMBL.ORG team
 *
**/

// Make sure we don't expose any info if called directly
defined( 'ABSPATH' ) or die( 'Access denied. Scripts are not allowed.' );

function egsr_admin_menu(){
    add_users_page( 'EMBL Group Site Roles', 'EMBL Group Site Roles', 'list_users', 'embl-group-site-roles', 'egsr_admin_page' );
}

function egsr_admin_page()
{
?>
      <div class="wrap">
      <?php egsr_admin_page_heading(); ?>
      <?php if(isset($_GET['egsr-activation']) == 'true') { egsr_admin_page_activation(); } ?>
      <?php // if(isset($_GET['egsr-deactivation']) == 'true') { egsr_admin_page_deactivation(); } ?>
      <?php egsr_admin_page_user_check(); ?>
      <?php egsr_admin_page_body(); ?>
      </div>
<?php
}

function egsr_admin_page_heading()
{
?>
      <h1 class="wp-heading-inline">EMBL Group Site Roles</h1>
      <h3>Utility to override default Wordpress roles and implement EMBL Group website's custom ones.</h3>
<?php
}

function egsr_admin_page_body()
{
?>
      <p>In EMBL Group websites, default Wordpress roles "Author", "Contributor", "Editor" and "Subscriber" will be deprecated in favour of EMBL custom ones.</p>
      <p>These are the EMBL custom roles available:</p>
        <ul>
            <li><b><?=GROUP_EDITOR_ROLE_LABEL ?>:</b> Equivalent to WP Editor role. This role can create content...</li>
            <li><b><?=GROUP_ADMIN_ROLE_LABEL ?>:</b> Group Editor access + User management capabilities...</li>
        </ul>
<?php
}


function egsr_admin_page_activation()
{
?>
      <div class="updated">
      <p>Plugin <strong>activated</strong>.</p>
      </div>
<?php

}

function egsr_admin_page_deactivation()
{
?>
      <div class="updated">
      <p>Plugin <strong>Deactivated... Custom text</strong>.</p>
      </div>
<?php

}

function egsr_admin_page_user_check()
{
    $error_msg = '';
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
        $error_msg = '<p>
        Users with deprecated role as "Author", "Contributor", 
        "Editor" or "Subscriber" are active in this website. <br>You can temporarily replace them with EMBL custom roles: 
        "Group Admin", "Group Editor" or "Staff."</p><p><a href="' . $site_url . '/wp-admin/users.php">Users List Page&raquo;</a></p>';
        ?>
      <div class="update-nag">
      <p><?=$error_msg ?></p>
      </div>
<?php
    }

}
