<?php
/*
Plugin Name: VF-WP Publications
Description: VF-WP theme block.
Version: 0.1.3
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Publications extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_publications',
    'post_title' => 'Team publications',
  );

  protected $API = array(
    'source' => 'contenthub',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_publications');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

  // Query the contentHub API, samples:
  //   - Group: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-team-publications&title=Web%20Development
  //   - ORCID: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&orcid=0000-0001-5454-2815
  //   - Name: https://dev.content.embl.org/api/v1/pattern.html?pattern=embl-person-publications&title=Maria-Jesus
  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_publications_limit', $this->post()->ID));
    $order = get_field('vf_publications_order', $this->post()->ID);

    // load
    $searchType = get_field('vf_publications_type', $this->post()->ID) ?? 'team';
    $searchQuery = get_field('vf_publications_query', $this->post()->ID);

    if ($searchType == 'team' || $searchType == 'person_name') {
      $queryKey = 'title';
    } else if ($searchType == 'orcid') {
      $queryKey = 'orcid';
    }

    if ($searchType == 'team' ) {
      $this->API['pattern'] = 'embl-team-publications';
    } else if ($searchType == 'orcid' || $searchType == 'person_name') {
      $this->API['pattern'] = 'embl-person-publications';
      // no sapces allowed
      $searchQuery = str_replace(' ', '-', $searchQuery);
    }

    $vars = array(
      'limit' => $limit ? $limit : 100,
      'sort-field-value[changed]' => $order ? $order : 'DESC'
      // 'filter-field-value-not[field_person_positions.entity.field_position_membership]' => 'leader'
    );

    // if a specific query has been given, use it
    if ($searchQuery) {
      $vars[$queryKey] = $searchQuery;
    // otherwise if query for team use the name of the site, if set
    } else if ($searchType == 'team' && function_exists('embl_taxonomy_get_term')) {
      $term_id = get_field('embl_taxonomy_term_what', 'option');
      $term = embl_taxonomy_get_term($term_id);
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $vars['title'] = $term->meta[EMBL_Taxonomy::META_NAME];
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
