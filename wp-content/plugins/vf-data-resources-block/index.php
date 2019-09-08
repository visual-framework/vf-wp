<?php
/*
Plugin Name: VF-WP Data resources
Description: VF-WP theme block.
Version: 0.1.1
Author: EMBL-EBI Web Development
Plugin URI: https://git.embl.de/grp-stratcom/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Data_resources extends VF_Plugin {

  protected $API = array(
    'pattern'             => 'vf-summary-image',
    'filter-content-type' => 'resource',
    'source'              => 'contenthub',
  );

  function __construct(array $params = array()) {
    parent::__construct('vf_data_resources');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_data_resources',
        'post_title' => 'Data resources'
      )
    );
  }

  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_data_resources_limit', $this->post->ID));
    $order = get_field('vf_data_resources_order', $this->post->ID);

    $vars = array(
      'limit' => $limit ? $limit : 30,
      'sort-field-value[changed]' => $order ? $order : 'DESC'
      // 'filter-field-value-not[field_person_positions.entity.field_position_membership]' => 'leader'
    );

    if (function_exists('embl_taxonomy_get_term')) {
      $term_id = get_field('embl_taxonomy_term_what', 'option');
      $term = embl_taxonomy_get_term($term_id);
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $key = 'filter-field-contains[field_resource_team.entity.title]';
        $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    return parent::api_url(
      array_merge($vars, $query_vars)
    );
  }

} // VF_Data_resources

$plugin = new VF_Data_resources(array('init' => true));

?>
