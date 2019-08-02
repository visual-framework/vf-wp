<?php
/*
Plugin Name: VF-WP Publications
Description: VF-WP theme block.
Version: 0.1.0
Author: EMBL-EBI Web Development
Plugin URI: https://git.embl.de/grp-stratcom/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Publications extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'embl-team-publications'
    // 'filter-content-type' => 'resource'
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_publications');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_publications',
        'post_title' => 'Team publications'
      )
    );
  }

  // Query the contentHub API, samples:
  //   - Group: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-team-publications&title=Web%20Development
  //   - ORCID: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&orcid=0000-0001-5454-2815
  //   - Name: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&title=Maria-Jesus
  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_publications_limit', $this->post->ID));
    $order = get_field('vf_publications_order', $this->post->ID);

    $vars = array(
      'limit' => $limit ? $limit : 30,
      'sort-field-value[changed]' => $order ? $order : 'DESC'
      // 'filter-field-value-not[field_person_positions.entity.field_position_membership]' => 'leader'
    );

    if (function_exists('embl_taxonomy_get_term')) {
      $term_id = get_field('embl_taxonomy_term_what', 'option');
      $term = embl_taxonomy_get_term($term_id);
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $key = 'title';
        $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    return parent::api_url(
      array_merge($vars, $query_vars)
    );
  }

  /**
   * Return a list of years to filter by
   * TODO: integrate with Content Hub
   */
  function get_years() {
    return array(
      '2017' => '2017',
      '2018' => '2018',
      '2019' => '2019'
    );
  }

  /**
   * Return the year value to filter by from the query string
   */
  function get_query_year() {
    $filter_year = 0;
    if (array_key_exists('filter_year', $_GET)) {
      $filter_year = intval($_GET['filter_year']);
    }
    return $filter_year;
  }

  /**
   * Return the keyword value to filter by from the query string
   */
  function get_query_keyword() {
    $str = '';
    if (array_key_exists('filter_keyword', $_GET)) {
      $str = vf_search_keyword($_GET['filter_keyword']);
    }
    return $str;
  }

} // VF_Publications

$plugin = new VF_Publications(array('init' => true));

?>
