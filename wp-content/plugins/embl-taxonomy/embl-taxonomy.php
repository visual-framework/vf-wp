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
  const TAXONOMY_TYPES = array('post', 'page');

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

endif;

?>
