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
        'post_title' => 'Publications'
      )
    );
  }

  /**
   * Return a list of years to filter by
   * TODO: integrate with Content Hub
   */
  function get_years() {
    return array(
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
