<?php
/*
Plugin Name: VF-WP Publications Group EBI
Description: Query for team publications based of EMBL-EBI specific data source (EBI Content Database).
Version: 0.1.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Publications_group_ebi extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'ebi-team-publications',
    'source'              => 'contenthub',
    // 'filter-content-type' => 'resource'
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_publications_group_ebi');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_publications_group_ebi',
        'post_title' => 'EBI Team publications'
      )
    );
  }

  // Query the contentHub API, samples:
  //   - Group: https://dev.content.embl.org/api/v1/pattern.html?pattern=ebi-team-publications&title=Web%20Development
  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_publications_group_ebi_limit', $this->post->ID));
    $order = get_field('vf_publications_group_ebi_order', $this->post->ID);

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

} // VF_Publications_group_ebi

$plugin = new VF_Publications_group_ebi(array('init' => true));

?>
