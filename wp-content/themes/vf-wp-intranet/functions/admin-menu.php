<?php
/**
 * Admin menu customizations for intranet roles.
 */

if (!defined('ABSPATH')) {
	exit;
}

function vfwp_intranet_remove_role_menu_items() {
	if (current_user_can('group_admin')) {
		remove_menu_page('edit.php?post_type=teams');
		remove_menu_page('edit.php?post_type=people');
	} elseif (current_user_can('group_editor')) {
		remove_menu_page('edit.php?post_type=teams');
		remove_menu_page('edit.php?post_type=people');
		remove_menu_page('edit.php?post_type=insites');
		remove_menu_page('edit.php?post_type=my_contact');
	}
}
add_action('admin_menu', 'vfwp_intranet_remove_role_menu_items');
