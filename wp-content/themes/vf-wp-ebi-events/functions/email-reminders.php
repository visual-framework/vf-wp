<?php
ini_set("memory_limit", "-1");
set_time_limit(0);
/**
 * Registers the `email_reminders` post type.
 */
function email_reminders_init() {
	register_post_type(
		'email-reminders',
		[
			'labels'                => [
				'name'                  => __( 'Events Email Reminders', 'vf-wp-ebi-events' ),
				'singular_name'         => __( 'Events Email Reminders', 'vf-wp-ebi-events' ),
				'all_items'             => __( 'All Events Email Reminders', 'vf-wp-ebi-events' ),
				'archives'              => __( 'Events Email Reminders Archives', 'vf-wp-ebi-events' ),
				'attributes'            => __( 'Events Email Reminders Attributes', 'vf-wp-ebi-events' ),
				'insert_into_item'      => __( 'Insert into Events Email Reminders', 'vf-wp-ebi-events' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Events Email Reminders', 'vf-wp-ebi-events' ),
				'featured_image'        => _x( 'Featured Image', 'email-reminders', 'vf-wp-ebi-events' ),
				'set_featured_image'    => _x( 'Set featured image', 'email-reminders', 'vf-wp-ebi-events' ),
				'remove_featured_image' => _x( 'Remove featured image', 'email-reminders', 'vf-wp-ebi-events' ),
				'use_featured_image'    => _x( 'Use as featured image', 'email-reminders', 'vf-wp-ebi-events' ),
				'filter_items_list'     => __( 'Filter Events Email Reminders list', 'vf-wp-ebi-events' ),
				'items_list_navigation' => __( 'Events Email Reminders list navigation', 'vf-wp-ebi-events' ),
				'items_list'            => __( 'Events Email Reminders list', 'vf-wp-ebi-events' ),
				'new_item'              => __( 'New Events Email Reminders', 'vf-wp-ebi-events' ),
				'add_new'               => __( 'Add New', 'vf-wp-ebi-events' ),
				'add_new_item'          => __( 'Add New Events Email Reminders', 'vf-wp-ebi-events' ),
				'edit_item'             => __( 'Edit Events Email Reminders', 'vf-wp-ebi-events' ),
				'view_item'             => __( 'View Events Email Reminders', 'vf-wp-ebi-events' ),
				'view_items'            => __( 'View Events Email Reminders', 'vf-wp-ebi-events' ),
				'search_items'          => __( 'Search Events Email Reminders', 'vf-wp-ebi-events' ),
				'not_found'             => __( 'No Events Email Reminders found', 'vf-wp-ebi-events' ),
				'not_found_in_trash'    => __( 'No Events Email Reminders found in trash', 'vf-wp-ebi-events' ),
				'parent_item_colon'     => __( 'Parent Events Email Reminders:', 'vf-wp-ebi-events' ),
				'menu_name'             => __( 'Events Email Reminders', 'vf-wp-ebi-events' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
      'show_in_admin_bar'     => true,
      'capability_type'     => 'page',
      'supports'            => ['title', 'editor', 'page-attributes', 'excerpt', 'revisions', 'thumbnail'],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-email',
			'show_in_rest'          => true,
			'rest_base'             => 'email-reminders',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'email_reminders_init' );

/**
 * Sets the post updated messages for the `email_reminders` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `email_reminders` post type.
 */
function email_reminders_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['email-reminders'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Events Email Reminders updated. <a target="_blank" href="%s">View Events Email Reminders</a>', 'vf-wp-ebi-events' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'vf-wp-ebi-events' ),
		3  => __( 'Custom field deleted.', 'vf-wp-ebi-events' ),
		4  => __( 'Events Email Reminders updated.', 'vf-wp-ebi-events' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Events Email Reminders restored to revision from %s', 'vf-wp-ebi-events' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Events Email Reminders published. <a href="%s">View Events Email Reminders</a>', 'vf-wp-ebi-events' ), esc_url( $permalink ) ),
		7  => __( 'Events Email Reminders saved.', 'vf-wp-ebi-events' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Events Email Reminders submitted. <a target="_blank" href="%s">Preview Events Email Reminders</a>', 'vf-wp-ebi-events' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Events Email Reminders scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Events Email Reminders</a>', 'vf-wp-ebi-events' ), date_i18n( __( 'M j, Y @ G:i', 'vf-wp-ebi-events' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Events Email Reminders draft updated. <a target="_blank" href="%s">Preview Events Email Reminders</a>', 'vf-wp-ebi-events' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'email_reminders_updated_messages' );

/**
 * Sets the bulk post updated messages for the `email_reminders` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `email_reminders` post type.
 */
function email_reminders_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['email-reminders'] = [
		/* translators: %s: Number of Events Email Reminders. */
		'updated'   => _n( '%s Events Email Reminders updated.', '%s Events Email Reminders updated.', $bulk_counts['updated'], 'vf-wp-ebi-events' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Events Email Reminders not updated, somebody is editing it.', 'vf-wp-ebi-events' ) :
						/* translators: %s: Number of Events Email Reminders. */
						_n( '%s Events Email Reminders not updated, somebody is editing it.', '%s Events Email Reminders not updated, somebody is editing them.', $bulk_counts['locked'], 'vf-wp-ebi-events' ),
		/* translators: %s: Number of Events Email Reminders. */
		'deleted'   => _n( '%s Events Email Reminders permanently deleted.', '%s Events Email Reminders permanently deleted.', $bulk_counts['deleted'], 'vf-wp-ebi-events' ),
		/* translators: %s: Number of Events Email Reminders. */
		'trashed'   => _n( '%s Events Email Reminders moved to the Trash.', '%s Events Email Reminders moved to the Trash.', $bulk_counts['trashed'], 'vf-wp-ebi-events' ),
		/* translators: %s: Number of Events Email Reminders. */
		'untrashed' => _n( '%s Events Email Reminders restored from the Trash.', '%s Events Email Reminders restored from the Trash.', $bulk_counts['untrashed'], 'vf-wp-ebi-events' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'email_reminders_bulk_updated_messages', 10, 2 );

// Add custom columns
function add_custom_columns_to_events_reminders($columns) {
  return array(
    'cb' => '<input type="checkbox" />',
    'event_title' => __( 'Event' ),
    'event_start_date' => __( 'Event start date' ),
    'email_from_user' => 'Email from user',
    'email_to_users' => 'Email to user(s)',
    'reminder_lead_time' => 'Reminder Lead time',
    'reminder_time_set_on' => __( 'Reminder set on' ),
    'reminder_triggered_on' => __( 'Reminder triggered on' ),
//    'date'     => __( 'Reminder record added on' ),
    'reminder_status' => __( 'Reminder Status' ),
    'reminder_debug_information' => __( 'Debug information' ),
  );
}
/*
 * event_reference_id
 * email_from_user
 * email_to_users
 * reminder_lead_time - 604800 monday_week_of_event morning_of_event
 * reminder_time_set_on
 * reminder_triggered_on
 * reminder_status
 * reminder_debug_information
 */
function events_reminders_custom_columns_data ($column, $post_id) {
  switch ($column) {
    case 'event_title':
      $field = get_field_object('email_from_user', $post->ID);
      echo $field['value'];
      break;
    case 'event_start_date':
      $field = get_field_object('email_from_user', $post->ID);
      echo $field['value'];
      break;
    case 'email_from_user':
      $field = get_field_object('email_from_user', $post->ID);
      echo $field['value'];
      break;
    case 'email_to_users':
      $field = get_field_object('email_to_users', $post->ID);
      echo $field['value'];
      break;
    case 'reminder_lead_time':
      $field = get_field_object('reminder_lead_time', $post->ID);
      echo $field['value']['label'];
      break;
    case 'reminder_time_set_on':
      $field = get_field_object('reminder_time_set_on', $post->ID);
      echo $field['value'];
      break;
    case 'reminder_triggered_on':
      $field = get_field_object('reminder_triggered_on', $post->ID);
      echo $field['value'];
      break;
    case 'reminder_status':
      $field = get_field_object('reminder_status', $post->ID);
      echo $field['value'];
      break;
    case 'reminder_debug_information':
      $field = get_field_object('reminder_debug_information', $post->ID);
      echo $field['value'];
      break;
  }
}
// Add columns to events post type
add_filter ('manage_email-reminders_posts_columns', 'add_custom_columns_to_events_reminders' );
add_action ('manage_email-reminders_posts_custom_column', 'events_reminders_custom_columns_data', 10, 2);
