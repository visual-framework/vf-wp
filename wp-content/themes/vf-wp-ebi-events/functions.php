<?php

/* Events post type */
require_once('functions/event_type_taxonomy.php');
require_once('functions/cpt_events_register.php');
require_once('functions/email-reminders.php');
require_once('functions/wp_ics.php');

/* Adds jplist script */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
    wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}

/* Load ACF JSON from theme */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }

/* display acf in the menu */

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_filter('acf/settings/show_admin', '__return_true');
function my_acf_save_post( $post_id ) {

    $user = get_field( 'author', $post_id );
	if( $user ) {
		wp_update_post( array( 'ID'=>$post_id, 'post_author'=>$user['ID']) );
	}
}
add_action('acf/save_post', 'my_acf_save_post', 20);

/* Theme CSS file */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('vfwp'),
		wp_get_theme()->get('Version')
	);
	wp_enqueue_style(
		'vf-events-admin',
		get_stylesheet_directory_uri() . '/assets/admin.css',
		array('vfwp'),
		wp_get_theme()->get('Version')
	);
}


function get_pretty_date($date) {
    return date('l, F j, Y', strtotime($date));
  }

  /*
 * Redirect pages to external links
 */

add_action( 'template_redirect', 'redirect_externally' );
function redirect_externally(){
    $redirect = get_post_meta( get_the_ID(), 'vf_event_redirect', true );
    if (is_single()) {
    if( $redirect ){
        wp_redirect( $redirect );
    } }
}

// Adds year and month to the permalink

function event_custom_acf_field_link( $permalink, $post, $leavename ) {
    if ( stripos( $permalink, '%event_year%' ) == false )
        return $permalink;

    if ( is_object( $post ) && 'events' == $post->post_type ) {

        $default_year = get_the_date('Y');
        $default_month = get_the_date('m');
        // Year
        if( $event_year = get_post_meta( $post->ID, 'vf_event_start_date', true ) ){
            $year_start = strtotime($event_year);
            $YearDate = date('Y', $year_start);
            $permalink = str_replace( '%event_year%', $YearDate, $permalink );
        } else {
            $permalink = str_replace( '%event_year%', $default_year, $permalink );
        }
		

    }

    return $permalink;
}

// Adds event type field value to the permalink

add_filter('post_type_link', 'event_permalink_structure', 10, 3);
function event_permalink_structure($post_link, $post, $leavename) {
    if (false !== strpos($post_link, '%type%')) {
        $type_term = get_post_meta($post->ID, 'vf_event_event_type', true);
		$type_term = str_replace('_', '-', $type_term);
        if (!empty($type_term))
            $post_link = str_replace('%type%', $type_term, $post_link);
        else
            $post_link = str_replace('%type%', 'uncategorized', $post_link);
    } 
    return $post_link;
}


add_filter( 'post_type_link', 'event_custom_acf_field_link', 10, 4 );

function split_location_text($location_array, $event_type = 'public_event') {
  $location_string = "";
  if (!empty($location_array)) {
    if ($event_type == 'public_event') {
      // Only change the locations names for Public events.
      $location_string = implode(', ', array_map(function($el){ return update_location_text($el['label']); }, $location_array));
    }
    else {
      $location_string = implode(', ', array_map(function($el){ return $el['label']; }, $location_array));
    }
    return $location_string;
  }
}

function update_location_text($location) {
  $location_text = "";
  if(!empty($location)) {
    switch($location) {
      case "EMBL-EBI":
        $location_text = "United Kingdom";
        break;
      case "Other":
        $location_text = "Outside United Kingdom";
        break;
      default;
        $location_text = $location;
        break;
    }
    return $location_text;
  }
}

// Add custom columns
function add_custom_columns_to_events($columns) {
  return array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'event-start-date' => 'Event Start date',
    'event-end-date' => 'Event End date',
    'event-type' => 'Event Type',
    'event-location' => __( 'Event Location' ),
    'author' => __( 'Author' ),
    'date'     => __( 'Date' ),
  );
}

function events_custom_columns_data ($column, $post_id) {
  switch ($column) {
    case 'event-type':
      $field = get_field_object('vf_event_event_type', $post->ID);
      $field_sub_type = get_sub_type_value($field['value']['value']);
      echo $field['value']['label'] . ' - '. $field_sub_type['value']['label'];
      break;
    case 'event-location':
      $field = get_field_object('vf_event_location', $post->ID);
      $locations = split_location_text($field['value'], '');
      echo $locations;
      break;
    case 'event-start-date':
      $field = get_field_object('vf_event_start_date', $post->ID);
      echo $field['value'];
      break;
    case 'event-end-date':
      $field = get_field_object('vf_event_end_date', $post->ID);
      echo $field['value'];
      break;
  }
}
// Add columns to events post type
add_filter ('manage_events_posts_columns', 'add_custom_columns_to_events' );
add_action ('manage_events_posts_custom_column', 'events_custom_columns_data', 10, 2);

// Remove columns
add_filter( 'manage_posts_columns', 'custom_post_columns', 10, 2 );
function custom_post_columns($columns, $post_type ) {
  switch ($post_type) {
    case 'events':
      unset($columns['tags']);
      break;
  }
  return $columns;
}

function get_sub_type_value($event_type) {
  $sub_type_field = "";
  if(!empty($event_type)) {
    switch($event_type) {
      case "seminar":
        $sub_type_field = get_field_object('vf_event_seminar_subtype', $post->ID);
        break;
      case "public_event":
        $sub_type_field = get_field_object('vf_event_public_subtype', $post->ID);
        break;
      case "internal_event";
        $sub_type_field = get_field_object('vf_event_internal_subtype', $post->ID);
        break;
    }
    return $sub_type_field;
  }
}

// Add custom search filter for event-types
add_action( 'restrict_manage_posts', 'filter_post_by_custom_field_event_type' , 10, 2);
function filter_post_by_custom_field_event_type( $post_type, $which ) {

  if ( 'events' === $post_type ) {
    $meta_key = 'event-type';
    $options = generate_event_type_filter();

    echo "<select name='{$meta_key}' id='{$meta_key}' class='postform'>";
    foreach ( $options as $value => $name ) {
      printf(
        '<option value="%1$s" %2$s>%3$s</option>',
        esc_attr($value),
        ( ( isset( $_GET[$meta_key] ) && ( $_GET[$meta_key] === $value ) ) ? ' selected="selected"' : '' ),
        esc_html($name)
      );
    }
    echo '</select>';
  }
}

// Generate dropdown of all event types & sub-types.
function generate_event_type_filter() {
  $event_type_raw = get_field_object('field_619cc059aea2d');
  $seminar_type_raw = get_field_object('field_619cc059aeafd');
  $public_type_raw = get_field_object('field_619cc059aeb94');
  $internal_type_raw = get_field_object('field_61a125bb76e05');
  $all_choices[] = 'All Event Type';
  foreach($event_type_raw['choices'] as $key => $value) {
    $all_choices[$key] = $value;
    if ($key == "seminar") {
      foreach($seminar_type_raw['choices'] as $skey => $svalue) {
        $all_choices[$key . '-' . $skey] = $value . ' -> ' . $svalue;
      }
    }
    if ($key == "public_event") {
      foreach($public_type_raw['choices'] as $pkey => $pvalue) {
        $all_choices[$key . '-' . $pkey] = $value . ' -> ' . $pvalue;
      }
    }
    if ($key == "internal_event") {
      foreach($internal_type_raw['choices'] as $ikey => $ivalue) {
        $all_choices[$key . '-' . $ikey] = $value . ' -> ' . $ivalue;
      }
    }
  }
  return $all_choices;
}

// Add query param to filter event-types
add_filter( 'parse_query', 'filter_parse_query_custom_field_event_type');
function filter_parse_query_custom_field_event_type($query) {
  global $pagenow;

  $meta_key = 'event-type';
  $valid_event_type = array_keys(generate_event_type_filter());
  $event_type_value = (!empty($_GET[$meta_key]) && in_array($_GET[$meta_key], $valid_event_type)) ? $_GET[$meta_key] : '';

  if ( is_admin() && 'edit.php' === $pagenow && isset($_GET['post_type']) && 'events' === $_GET['post_type'] && $event_type_value ) {

    $type_raw = explode('-', $event_type_value);
    $parent_type = $type_raw[0];
    $child_type = isset($type_raw[1]) ? $type_raw[1] : '';
    $query->query_vars['meta_key'] = 'vf_event_event_type';
    $query->query_vars['meta_value'] = $parent_type;
    $query->query_vars['meta_compare'] = '=';

    switch($parent_type) {
      case "seminar":
        if(!empty($child_type)) {
          $query->query_vars['meta_compare'] = '=';
          $query->query_vars['meta_key'] = 'vf_event_seminar_subtype';
          $query->query_vars['meta_value'] = $child_type;
        }
        break;
      case "public_event":
        if(!empty($child_type)) {
          $query->query_vars['meta_compare'] = '=';
          $query->query_vars['meta_key'] = 'vf_event_public_subtype';
          $query->query_vars['meta_value'] = $child_type;
        }
        break;
      case "internal_event";
        if(!empty($child_type)) {
          $query->query_vars['meta_compare'] = '=';
          $query->query_vars['meta_key'] = 'vf_event_internal_subtype';
          $query->query_vars['meta_value'] = $child_type;
        }
        break;
    }
  }
}

function adjust_admin_style() {
  echo '<style>
    .column-title {
        width: 55em;
    }
  </style>';
}
add_action('admin_head', 'adjust_admin_style');
