<?php
/*
Plugin Name: EMBL Taxonomy
Plugin URI: https://github.com/visual-framework/vf-wp
Description: Adds EMBL Taxonomy integration to tag posts with EMBL Taxonomy terms.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('EMBL_Taxonomy') ) :

class EMBL_Taxonomy {

  // Registered taxonomy name
  const TAXONOMY_NAME = 'embl_taxonomy';

  // Taxonomy term meta keys
  const META_IDS        = EMBL_Taxonomy::TAXONOMY_NAME . '_ids';
  const META_NAME       = EMBL_Taxonomy::TAXONOMY_NAME . '_name';
  const META_DEPRECATED = EMBL_Taxonomy::TAXONOMY_NAME . '_deprecated';

  // Register taxonomy for these post types
  const TAXONOMY_TYPES = array('post', 'page', 'vf_event');

  // Separator used to join term names
  const TAXONOMY_SEPARATOR = ' > ';

  // Show admin notice if taxonomy is outdated (seconds)
  const MAX_AGE = 5 * 24 * 60 * 60; // 5 days

  public $register;
  public $settings;

  public function __construct() {
    register_activation_hook( __FILE__, array( $this, 'activation' ) );
    register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

  }

  function initialize() {
    include_once('includes/register.php');
    include_once('includes/settings.php');
    $this->register = new EMBL_Taxonomy_Register();
    $this->settings = new EMBL_Taxonomy_Settings();
  }

  function activation() {
    // Do nothing...
  }

  function deactivation() {
    // Do nothing...
  }
}

/**
 * Return global instance (and initialize once)
 */
function embl_taxonomy() {
  global $embl_taxonomy;

  if ( ! isset($embl_taxonomy)) {
    $embl_taxonomy = new EMBL_Taxonomy();
    $embl_taxonomy->initialize();
  }
  return $embl_taxonomy;
}
embl_taxonomy();

/**
 * Force a manual sync with the API
 */
function embl_taxonomy_sync_terms() {
  return embl_taxonomy()->register->sync_taxonomy();
}

/**
 * Return WordPress taxonomy term with term meta assigned to the object
 */
function embl_taxonomy_get_term($term_id) {
  if (!$term_id) {
    return null;
  }
  // Ensure embl_taxonomy()->register and get_wp_taxonomy() are available
  $taxonomy_instance = embl_taxonomy();
  if (!$taxonomy_instance || !isset($taxonomy_instance->register)) {
      error_log('Register instance is not set or initialized.');
      return null;
  }
  $wp_terms = embl_taxonomy()->register->get_wp_taxonomy();
  // Get by `WP_Term->term_id`
  if (is_int($term_id)) {
    foreach ($wp_terms as $wp_term) {
      if ($wp_term->term_id === $term_id) {
        return $wp_term;
      }
    }
  }
  // Get by EMBL Taxonomy UUID
  if (is_string($term_id)) {
    foreach ($wp_terms as $wp_term) {
      $ids = $wp_term->meta[EMBL_Taxonomy::META_IDS];
      if ( ! is_array($ids) || ! count($ids)) {
        continue;
      }
      if (array_pop($ids) === $term_id) {
        return $wp_term;
      }
    }
  }
  // Get by EMBL Taxonomy pattern IDs
  if (is_array($term_id)) {
    foreach ($wp_terms as $wp_term) {
      $ids = $wp_term->meta[EMBL_Taxonomy::META_IDS];
      if ( ! is_array($ids) || ! count($ids)) {
        continue;
      }
      if (implode('-', $term_id) === implode('-', $ids)) {
        return $wp_term;
      }
    }
  }
  return null;
}

/**
 * Return uuid associated with a WordPress taxonomy term
 */
function embl_taxonomy_get_uuid($term_id) {
  $term = embl_taxonomy_get_term($term_id);
  if ( ! $term || ! $term->meta)  {
    return null;
  }
  $uuid = end($term->meta['embl_taxonomy_ids']);
  return $uuid;
}

/**
 * Return the VF stylesheet URL
 */
function embl_taxonomy_get_url() {
  return embl_taxonomy()->settings->get_field('embl_taxonomy');
}



$active_theme = wp_get_theme();
$is_news_theme = ($active_theme->get('Name') === 'VF-WP News');

if ($active_theme == $is_news_theme) {
/**
 * Hides EMBL Taxonomy default meta box
 */

add_filter( 'rest_prepare_taxonomy', function( $response, $taxonomy, $request ){
	$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
	// Context is edit in the editor
	if( $context === 'edit' && $taxonomy->meta_box_cb === false ){
		$data_response = $response->get_data();
		$data_response['visibility']['show_ui'] = false;
		$response->set_data( $data_response );
		}
	return $response;
}, 10, 3 );



/**
 * Adds bulk actions
 */

// Step 1: Add "Mark as Deprecated", "Mark as Active", and "Mark as Hidden" to the Bulk Actions dropdown
add_filter('bulk_actions-edit-embl_taxonomy', function($bulk_actions) {
  $bulk_actions['mark_as_deprecated'] = __('Mark as Deprecated', 'text-domain');
  $bulk_actions['mark_as_active'] = __('Mark as Active', 'text-domain');
  $bulk_actions['mark_as_hidden'] = __('Mark as Hidden', 'text-domain');
  return $bulk_actions;
});

// Step 2: Handle the bulk edit actions
add_action('handle_bulk_actions-edit-embl_taxonomy', function($redirect_to, $doaction, $term_ids) {
  if (in_array($doaction, ['mark_as_deprecated', 'mark_as_active', 'mark_as_hidden'])) {
      // Set values based on the action
      $deprecated_value = $doaction === 'mark_as_deprecated' ? 1 : 0;
      $hidden_value = $doaction === 'mark_as_hidden' ? 1 : 0;

      foreach ($term_ids as $term_id) {
          if ($doaction === 'mark_as_deprecated') {
              update_field('field_embl_taxonomy_deprecated', $deprecated_value, 'embl_taxonomy_' . $term_id);
          } elseif ($doaction === 'mark_as_hidden') {
              update_field('field_embl_taxonomy_hidden', $hidden_value, 'embl_taxonomy_' . $term_id);
          } elseif ($doaction === 'mark_as_active') {
              // Mark both as active (0)
              update_field('field_embl_taxonomy_deprecated', 0, 'embl_taxonomy_' . $term_id);
              update_field('field_embl_taxonomy_hidden', 0, 'embl_taxonomy_' . $term_id);
          }
      }

      // Redirect back with a success message
      $redirect_to = add_query_arg(
          $doaction === 'mark_as_deprecated' ? 'bulk_mark_as_deprecated' : ($doaction === 'mark_as_hidden' ? 'bulk_mark_as_hidden' : 'bulk_mark_as_active'),
          count($term_ids),
          $redirect_to
      );
  }
  return $redirect_to;
}, 10, 3);

// Step 3: Display a confirmation message after bulk edit
add_action('admin_notices', function() {
  if (!empty($_GET['bulk_mark_as_deprecated'])) {
      $edited_count = intval($_GET['bulk_mark_as_deprecated']);
      printf('<div id="message" class="updated notice is-dismissible"><p>' .
          __('Marked %s terms as deprecated successfully.', 'text-domain') . '</p></div>', $edited_count);
  }
  if (!empty($_GET['bulk_mark_as_active'])) {
      $edited_count = intval($_GET['bulk_mark_as_active']);
      printf('<div id="message" class="updated notice is-dismissible"><p>' .
          __('Marked %s terms as active successfully.', 'text-domain') . '</p></div>', $edited_count);
  }
  if (!empty($_GET['bulk_mark_as_hidden'])) {
      $edited_count = intval($_GET['bulk_mark_as_hidden']);
      printf('<div id="message" class="updated notice is-dismissible"><p>' .
          __('Marked %s terms as hidden successfully.', 'text-domain') . '</p></div>', $edited_count);
  }
});


/**
 * Filters the list of terms in the taxonomy picker
 */
add_filter('acf/fields/taxonomy/query/name=vfwp-news_embl_taxonomy', function( $args, $field, $post_id ) {

  // Set up the arguments for retrieving terms
  $args_terms = array(
    'taxonomy'   => 'embl_taxonomy',
    'hide_empty' => 0,  // Include terms even if they are not assigned to any posts
  );

  // Get all terms in the 'embl_taxonomy' taxonomy
  $terms = get_terms( $args_terms );
  
  if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    $hidden_terms = [];

    // Loop through each term to check the custom field value
    foreach ( $terms as $term ) {
      // Get the value of the 'field_embl_taxonomy_hidden' ACF field for each term
      $custom_field = get_field('field_embl_taxonomy_hidden', $term);

      // If the field value is 1, add the term ID to the hidden terms array
      if ( $custom_field == '1' ) {
        $hidden_terms[] = $term->term_id;
      }
    }

    // If there are hidden terms, exclude them from the ACF picker
    if ( ! empty( $hidden_terms ) ) {
      $args['exclude'] = $hidden_terms;
    }
  }

  return $args;

}, 10, 3);

}


endif;

?>
