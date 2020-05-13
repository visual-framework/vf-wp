<?php
/*
Plugin Name: VF-WP Publications
Description: VF-WP theme block.
Version: 1.0.0-beta.2
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

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  public function __construct(array $params = array()) {
    parent::__construct('vf_publications');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
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
