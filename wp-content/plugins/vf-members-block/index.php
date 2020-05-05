<?php
/*
Plugin Name: VF-WP Members
Description: VF-WP theme block.
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

// Include the additional `VF_Person` block
require_once('vf-person/index.php');

class VF_Members extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_members',
    'post_title' => 'Members',
  );

  protected $API = array(
    'filter-content-type' => 'person',
    'source'              => 'contenthub',
  );

  function __construct(array $params = array()) {
    parent::__construct('vf_members');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_members_limit', $this->post()->ID));
    $order = get_field('vf_members_order', $this->post()->ID);
    $varition = get_field('vf_members_variation', $this->post()->ID);
    $leader = get_field('vf_members_leader', $this->post()->ID);
    $team = get_field('vf_members_team', $this->post()->ID);
    $term_id = get_field('vf_members_term', $this->post()->ID);
    $keyword = get_field('vf_members_keyword', $this->post()->ID);

    $keyword = trim($keyword);
    $leader = boolval($leader);
    if (empty($varition)) {
      $varition = 'l';
    }

    $vars = array(
      'pattern' => "vf-summary-profile-{$varition}",
      'limit' => $limit ? $limit : 30,
      'sort-field-value[changed]' => $order ? $order : 'DESC',
      'filter-fields-empty' => 'field_person_visible_internally',
    );

    if ($leader !== true) {
      $vars['filter-field-value-not[field_person_positions.entity.field_position_membership]'] = 'leader';
    }


    $key = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.title]';

    // Search based on EMBL Taxonomy (default or specified)
    if (function_exists('embl_taxonomy_get_term')) {
      // Use specified term
      $term = null;
      if ($team === 'taxonomy' && is_numeric($term_id)) {
        $term = embl_taxonomy_get_term(intval($term_id));
      }
      // Use default
      if ( ! $term instanceof WP_Term) {
        $term_id = get_field('embl_taxonomy_term_what', 'option');
        $term = embl_taxonomy_get_term($term_id);
      }
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    // Search by keyword
    if ($team === 'keyword' && ! empty($keyword)) {
      $vars[$key] = $keyword;
    }

    return parent::api_url(
      array_merge($vars, $query_vars)
    );
  }

} // VF_Members

$plugin = new VF_Members(array('init' => true));

?>
