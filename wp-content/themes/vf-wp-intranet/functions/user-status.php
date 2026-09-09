<?php
/**
 * User status admin screen.
 */

if (!defined('ABSPATH')) {
	exit;
}

function vfwp_intranet_get_protected_user_status_emails() {
	return array(
		'admin@mysite.com',
		'internalcomms@embl.org',
		'staffassociation@embl-hamburg.de',
		'intl-relations@embl.org',
		'council@embl.org',
	);
}

function vfwp_intranet_is_protected_user_status_email($email) {
	$email = strtolower(sanitize_email((string) $email));
	return $email !== '' && in_array($email, vfwp_intranet_get_protected_user_status_emails(), true);
}

function vfwp_intranet_current_user_is_administrator() {
	$current_user = wp_get_current_user();
	return $current_user instanceof WP_User && in_array('administrator', (array) $current_user->roles, true);
}

function vfwp_intranet_current_user_can_delete_status_user($user) {
	if (!$user instanceof WP_User || vfwp_intranet_is_protected_user_status_email($user->user_email) || get_current_user_id() === (int) $user->ID) {
		return false;
	}

	if (vfwp_intranet_current_user_is_administrator() && current_user_can('delete_users')) {
		return true;
	}

	return current_user_can('delete_users') && current_user_can('delete_user', $user->ID);
}

add_action('admin_menu', 'vfwp_intranet_add_user_status_page');
function vfwp_intranet_add_user_status_page() {
	add_users_page(
		__('User status', 'vfwp'),
		__('User status', 'vfwp'),
		'list_users',
		'vfwp-user-status',
		'vfwp_intranet_render_user_status_page'
	);
}

function vfwp_intranet_render_user_status_page() {
	if (!current_user_can('list_users')) {
		wp_die(esc_html__('You do not have permission to view this page.', 'vfwp'));
	}

	$action_message = vfwp_intranet_handle_user_status_actions();
	$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
	$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
	$per_page = 50;

	$people_by_email = vfwp_intranet_get_people_by_email();
	$left_users = vfwp_intranet_get_left_users($people_by_email, $search);
	$total_items = count($left_users);
	$total_pages = max(1, (int) ceil($total_items / $per_page));
	$paged = min($paged, $total_pages);
	$users_page = array_slice($left_users, ($paged - 1) * $per_page, $per_page);

	?>
	<div class="wrap">
		<h1><?php echo esc_html__('User status', 'vfwp'); ?></h1>

		<?php if (!empty($action_message)) : ?>
			<div class="notice notice-<?php echo esc_attr($action_message['type']); ?> is-dismissible">
				<p><?php echo esc_html($action_message['message']); ?></p>
			</div>
		<?php endif; ?>

		<p>
			<?php echo esc_html__('Showing WordPress users whose email address does not match any published People email.', 'vfwp'); ?>
		</p>
		<ul class="subsubsub">
			<li>
				<a href="<?php echo esc_url(admin_url('users.php?page=vfwp-user-status')); ?>" class="current">
					<?php echo esc_html__('Left', 'vfwp'); ?> <span class="count">(<?php echo esc_html($total_items); ?>)</span>
				</a>
			</li>
		</ul>

		<form method="get" class="search-form">
			<input type="hidden" name="page" value="vfwp-user-status">
			<p class="search-box">
				<label class="screen-reader-text" for="user-search-input"><?php echo esc_html__('Search users', 'vfwp'); ?></label>
				<input type="search" id="user-search-input" name="s" value="<?php echo esc_attr($search); ?>">
				<?php submit_button(__('Search users', 'vfwp'), '', '', false, array('id' => 'search-submit')); ?>
			</p>
		</form>

		<form method="post">
			<?php wp_nonce_field('vfwp_user_status_bulk_action', 'vfwp_user_status_nonce'); ?>
			<input type="hidden" name="page" value="vfwp-user-status">
			<input type="hidden" name="vfwp_user_status_action" value="bulk_delete">

			<div class="tablenav top">
				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-top" class="screen-reader-text"><?php echo esc_html__('Select bulk action', 'vfwp'); ?></label>
					<select name="bulk_action" id="bulk-action-selector-top">
						<option value="-1"><?php echo esc_html__('Bulk actions', 'vfwp'); ?></option>
						<option value="delete_left"><?php echo esc_html__('Delete selected users', 'vfwp'); ?></option>
					</select>
					<?php submit_button(__('Apply', 'vfwp'), 'action', '', false); ?>
				</div>
				<?php vfwp_intranet_render_user_status_pagination($total_items, $total_pages, $paged, $search); ?>
				<br class="clear">
			</div>

			<table class="wp-list-table widefat fixed striped users">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<input type="checkbox">
						</td>
						<th scope="col"><?php echo esc_html__('User', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Email', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Roles', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Registered', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('People match', 'vfwp'); ?></th>
						<th scope="col"><?php echo esc_html__('Status', 'vfwp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($users_page)) : ?>
						<tr>
							<td colspan="7"><?php echo esc_html__('No users without a People email match found.', 'vfwp'); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ($users_page as $user_status) : ?>
							<?php vfwp_intranet_render_user_status_row($user_status); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-bottom" class="screen-reader-text"><?php echo esc_html__('Select bulk action', 'vfwp'); ?></label>
					<select name="bulk_action_bottom" id="bulk-action-selector-bottom">
						<option value="-1"><?php echo esc_html__('Bulk actions', 'vfwp'); ?></option>
						<option value="delete_left"><?php echo esc_html__('Delete selected users', 'vfwp'); ?></option>
					</select>
					<?php submit_button(__('Apply', 'vfwp'), 'action', '', false); ?>
				</div>
				<?php vfwp_intranet_render_user_status_pagination($total_items, $total_pages, $paged, $search); ?>
				<br class="clear">
			</div>
		</form>
	</div>
	<?php
}

function vfwp_intranet_handle_user_status_actions() {
	if (empty($_POST['vfwp_user_status_action']) && empty($_GET['vfwp_user_status_action'])) {
		return null;
	}

	if (!current_user_can('delete_users')) {
		return array(
			'type' => 'error',
			'message' => __('You do not have permission to delete users.', 'vfwp'),
		);
	}

	$action = '';
	$user_ids = array();

	if (!empty($_POST['vfwp_user_status_action'])) {
		check_admin_referer('vfwp_user_status_bulk_action', 'vfwp_user_status_nonce');

		$bulk_action = isset($_POST['bulk_action']) ? sanitize_key(wp_unslash($_POST['bulk_action'])) : '-1';
		if ($bulk_action === '-1' && isset($_POST['bulk_action_bottom'])) {
			$bulk_action = sanitize_key(wp_unslash($_POST['bulk_action_bottom']));
		}

		if ($bulk_action !== 'delete_left') {
			return array(
				'type' => 'warning',
				'message' => __('Please choose a bulk action.', 'vfwp'),
			);
		}

		$action = 'delete_left';
		$user_ids = isset($_POST['users']) ? array_map('absint', (array) wp_unslash($_POST['users'])) : array();
	} else {
		$action = isset($_GET['vfwp_user_status_action']) ? sanitize_key(wp_unslash($_GET['vfwp_user_status_action'])) : '';
		$user_ids[] = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;

		$nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
		if (!wp_verify_nonce($nonce, 'vfwp_user_status_delete_user_' . $user_ids[0])) {
			return array(
				'type' => 'error',
				'message' => __('The delete link has expired. Please try again.', 'vfwp'),
			);
		}
	}

	if ($action !== 'delete_left') {
		return null;
	}

	$user_ids = array_values(array_filter(array_unique($user_ids)));
	if (empty($user_ids)) {
		return array(
			'type' => 'warning',
			'message' => __('No users selected.', 'vfwp'),
		);
	}

	require_once ABSPATH . 'wp-admin/includes/user.php';

	$people_by_email = vfwp_intranet_get_people_by_email();
	$deleted = 0;
	$skipped = 0;

	foreach ($user_ids as $user_id) {
		$user = get_userdata($user_id);
		if (!$user || !vfwp_intranet_current_user_can_delete_status_user($user)) {
			$skipped++;
			continue;
		}

		if (vfwp_intranet_user_has_people_match($user, $people_by_email)) {
			$skipped++;
			continue;
		}

		if (wp_delete_user($user_id, $current_user_id)) {
			$deleted++;
		} else {
			$skipped++;
		}
	}

	if ($deleted === 0) {
		return array(
			'type' => 'warning',
			'message' => __('No users were deleted. Only users without a People email match can be deleted from this screen.', 'vfwp'),
		);
	}

	if ($skipped > 0) {
		return array(
			'type' => 'warning',
			'message' => sprintf(
				/* translators: 1: deleted user count, 2: skipped user count */
				_n('%1$d user deleted. %2$d user skipped.', '%1$d users deleted. %2$d users skipped.', $deleted, 'vfwp'),
				$deleted,
				$skipped
			),
		);
	}

	return array(
		'type' => 'success',
		'message' => sprintf(
			/* translators: %d: deleted user count */
			_n('%d user deleted.', '%d users deleted.', $deleted, 'vfwp'),
			$deleted
		),
	);
}

function vfwp_intranet_get_left_users($people_by_email, $search = '') {
	$users = get_users(array(
		'fields' => 'all_with_meta',
		'orderby' => 'display_name',
		'order' => 'ASC',
	));

	$left_users = array();

	foreach ($users as $user) {
		if (vfwp_intranet_is_protected_user_status_email($user->user_email)) {
			continue;
		}

		if (!vfwp_intranet_user_matches_search($user, $search)) {
			continue;
		}

		if (vfwp_intranet_user_has_people_match($user, $people_by_email)) {
			continue;
		}

		$left_users[] = array(
			'user' => $user,
			'registered' => strtotime($user->user_registered . ' UTC'),
			'status' => 'Left',
		);
	}

	return $left_users;
}

function vfwp_intranet_user_matches_search($user, $search) {
	if ($search === '') {
		return true;
	}

	$haystack = strtolower($user->display_name . ' ' . $user->user_login . ' ' . $user->user_email);
	return strpos($haystack, strtolower($search)) !== false;
}

function vfwp_intranet_get_people_by_email() {
	$people = get_posts(array(
		'post_type' => 'people',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			array(
				'key' => 'email',
				'compare' => 'EXISTS',
			),
		),
	));

	$people_by_email = array();

	foreach ($people as $post_id) {
		$email = sanitize_email((string) get_post_meta($post_id, 'email', true));
		if ($email === '') {
			continue;
		}

		$people_by_email[strtolower($email)] = true;
	}

	return $people_by_email;
}

function vfwp_intranet_user_has_people_match($user, $people_by_email) {
	$email_key = strtolower(sanitize_email($user->user_email));
	return $email_key !== '' && !empty($people_by_email[$email_key]);
}

function vfwp_intranet_render_user_status_row($user_status) {
	$user = $user_status['user'];
	$user_edit_link = get_edit_user_link($user->ID);
	$can_delete = vfwp_intranet_current_user_can_delete_status_user($user);
	$role_names = wp_roles()->role_names;
	$user_roles = array();

	foreach ($user->roles as $role) {
		$user_roles[] = isset($role_names[$role]) ? translate_user_role($role_names[$role]) : $role;
	}

	$delete_url = wp_nonce_url(
		add_query_arg(
			array(
				'page' => 'vfwp-user-status',
				'vfwp_user_status_action' => 'delete_left',
				'user_id' => $user->ID,
			),
			admin_url('users.php')
		),
		'vfwp_user_status_delete_user_' . $user->ID
	);
	?>
	<tr>
		<th scope="row" class="check-column">
			<?php if ($can_delete) : ?>
				<input type="checkbox" name="users[]" value="<?php echo esc_attr($user->ID); ?>">
			<?php else : ?>
				<input type="checkbox" disabled title="<?php echo esc_attr__('This user cannot be deleted by your account.', 'vfwp'); ?>" aria-label="<?php echo esc_attr__('This user cannot be deleted by your account.', 'vfwp'); ?>">
			<?php endif; ?>
		</th>
		<td>
			<strong>
				<a href="<?php echo esc_url($user_edit_link); ?>">
					<?php echo esc_html($user->display_name ?: $user->user_login); ?>
				</a>
			</strong>
			<div class="row-actions">
				<span class="edit">
					<a href="<?php echo esc_url($user_edit_link); ?>"><?php echo esc_html__('Edit', 'vfwp'); ?></a>
				</span>
				<?php if ($can_delete) : ?>
					<span class="delete">
						| <a href="<?php echo esc_url($delete_url); ?>" class="submitdelete" onclick="return confirm('<?php echo esc_js(__('Delete this user? Content will be reassigned to you.', 'vfwp')); ?>');"><?php echo esc_html__('Delete', 'vfwp'); ?></a>
					</span>
				<?php endif; ?>
			</div>
		</td>
		<td><a href="mailto:<?php echo esc_attr($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a></td>
		<td><?php echo esc_html(implode(', ', $user_roles)); ?></td>
		<td><?php echo esc_html(vfwp_intranet_format_user_status_date($user_status['registered'], __('Unknown', 'vfwp'))); ?></td>
		<td><?php echo esc_html__('No match', 'vfwp'); ?></td>
		<td>
			<span class="vfwp-user-status-badge vfwp-user-status-badge--left">
				<?php echo esc_html($user_status['status']); ?>
			</span>
		</td>
	</tr>
	<?php
}

function vfwp_intranet_format_user_status_date($timestamp, $empty_label) {
	$timestamp = (int) $timestamp;
	if ($timestamp <= 0) {
		return $empty_label;
	}

	return wp_date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
}

function vfwp_intranet_render_user_status_pagination($total_items, $total_pages, $paged, $search) {
	if ($total_items <= 0) {
		return;
	}

	$page_links = paginate_links(array(
		'base' => add_query_arg('paged', '%#%', admin_url('users.php?page=vfwp-user-status')),
		'format' => '',
		'prev_text' => __('&laquo;', 'vfwp'),
		'next_text' => __('&raquo;', 'vfwp'),
		'total' => $total_pages,
		'current' => $paged,
		'add_args' => array_filter(array(
			's' => $search !== '' ? $search : null,
		)),
	));

	?>
	<div class="tablenav-pages">
		<span class="displaying-num">
			<?php
			printf(
				/* translators: %s: item count */
				esc_html(_n('%s item', '%s items', $total_items, 'vfwp')),
				esc_html(number_format_i18n($total_items))
			);
			?>
		</span>
		<?php if ($page_links) : ?>
			<span class="pagination-links"><?php echo wp_kses_post($page_links); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

add_action('admin_head-users_page_vfwp-user-status', 'vfwp_intranet_user_status_admin_styles');
function vfwp_intranet_user_status_admin_styles() {
	?>
	<style>
		.vfwp-user-status-badge {
			border-radius: 999px;
			display: inline-block;
			font-size: 12px;
			font-weight: 600;
			line-height: 1;
			padding: 5px 9px;
		}
		.vfwp-user-status-badge--left {
			background: #fbeaea;
			color: #9f1f1f;
		}
	</style>
	<?php
}
