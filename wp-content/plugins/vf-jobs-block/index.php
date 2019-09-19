<?php
/*
Plugin Name: VF-WP Jobs
Description: VF-WP theme block.
Version: 0.1.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Jobs extends VF_Plugin {

  private $term_who;
  private $term_what;
  private $term_where;

  protected $API = array(
    'pattern'             => 'vf-jobs-snippet',
    'filter-content-type' => 'jobs',
    'source'              => 'contenthub',
  );

  function __construct(array $params = array()) {
    parent::__construct('vf_jobs');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_jobs',
        'post_title' => 'Jobs'
      )
    );

    add_action('init', array($this, 'add_taxonomy_fields'), 11);
    add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 11);
  }

  function api_url(array $query_vars = array()) {
    $limit = intval(get_field('vf_jobs_limit', $this->post->ID));

    $vars = array(
      'limit' => $limit ? $limit : 10
    );

    $sort_key = 'sort-field-value[field_jobs_expiration]';
    $sort_order = 'ASC';
    $vars[$sort_key] = $sort_order;

    $filter_key = 'filter-all-fields';

    // Prioritize user keyword search if defined
    if ( ! empty($this->get_query_keyword())) {
      $vars[$filter_key] = $this->get_query_keyword();

    // Otherwise use defaults
    } else if (function_exists('embl_taxonomy')) {
      $term = null;
      $filter = get_field('vf_jobs_filter', $this->post->ID);
      switch ( $filter ) {
        case 'cluster':
          $term = $this->get_term('what');
          $parent_count = count($term->meta['embl_taxonomy_ids']) - 2;
          if ($parent_count > 0) {
            $term = embl_taxonomy_get_term($term->meta['embl_taxonomy_ids'][$parent_count]);
          }
          $filter_key = 'filter-all-fields';
          break;
        case 'what':
          $term = $this->get_term('what');
          $filter_key = 'filter-field-contains[field_jobs_group]';
          break;
        case 'where':
          $term = $this->get_term('where');
          $filter_key = 'filter-field-contains[field_jobs_duty_station]';
          break;
        case 'term':
          $term = get_field('vf_jobs_term', $this->post->ID);
          if (is_int($term)) {
            $term = embl_taxonomy_get_term($term);
          }
          break;
      }
      if ($term instanceof WP_Term) {
        // $where_last_word = explode(' ',$term->name);
        // $where_last_word = array_pop($where_last_word);
        $vars[$filter_key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    return parent::api_url(
      array_merge($vars, $query_vars)
    );
  }

  /**
   * Return the global config
   */
  function get_term($key = '') {
    if ( ! function_exists('embl_taxonomy_get_term')) {
      return null;
    }
    $key = "term_{$key}";
    if ( ! $this->$key instanceof WP_Term) {
      $this->$key = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field("embl_taxonomy_{$key}")
      );
    }
    return $this->$key;
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

  /**
   * Action: `wp_enqueue_scripts`
   * Enqueue the autocomplete CSS and JavaScript on the "Jobs" template
   */
  function enqueue_assets() {
    if ( ! function_exists('embl_taxonomy')) {
      return;
    }
    if (is_page_template('template-jobs.php')) {
      wp_enqueue_script('accessible-autocomplete');
      wp_enqueue_style('accessible-autocomplete');
    }
  }

  /**
   * Action: `init`
   * Add additional configuration if the EMBL Taxonomy plugin exists
   * Cannot run on `acf/init` because taxonomy is not registered
   */
  function add_taxonomy_fields() {

    // Define choices based on settings
    $choices = array(
      'all' => __('All jobs', 'vfwp')
    );

    if (function_exists('embl_taxonomy')) {
      // Get global settings
      $who = $this->get_term('who');
      $what = $this->get_term('what');
      $where = $this->get_term('where');

      $format = '%1$s (%2$s)';

      if ($where) {
        $choices['where'] = sprintf(
          $format,
          __('Jobs at my location', 'vfwp'),
          $where->name
        );
      }
      if ($what) {
        $choices['what'] = sprintf(
          $format,
          __('Jobs in my team', 'vfwp'),
          $what->name
        );

        // If there is a "what" and it has parents, enable a cluster choice
        $what_parent_count = count($what->meta['embl_taxonomy_ids']) - 2;
        if ($what_parent_count > 0) {
          $cluster = embl_taxonomy_get_term($what->meta['embl_taxonomy_ids'][$what_parent_count]);

          if ($cluster) {
            $choices['cluster'] = sprintf(
              $format,
              __('Jobs in my unit/cluster', 'vfwp'),
              $cluster->name
            );
          }
        }
      }

      // $choices['term'] = sprintf(
      //   $format,
      //   __('Other jobs', 'vfwp'),
      //   __('select a term', 'vfwp')
      // );
    }

    acf_add_local_field(
      array(
        'parent' => 'group_vf_jobs',
        'key' => 'field_vf_jobs_filter',
        'label' => __('Show', 'vfwp'),
        'name' => 'vf_jobs_filter',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => $choices,
        'allow_null' => 0,
        'other_choice' => 0,
        'default_value' => 'all',
        'layout' => 'vertical',
        'return_format' => 'value',
        'save_other_choice' => 0,
      )
    );

    // if (function_exists('embl_taxonomy')) {
    //   acf_add_local_field(
    //     array(
    //       'parent' => 'group_vf_jobs',
    //       'key' => 'field_vf_jobs_term',
    //       'label' => __('Show Other', 'vfwp'),
    //       'name' => 'vf_jobs_term',
    //       'type' => 'taxonomy',
    //       'instructions' => '',
    //       'required' => 0,
    //       'conditional_logic' => array(
    //         array(
    //           array(
    //             'field' => 'field_vf_jobs_filter',
    //             'operator' => '==',
    //             'value' => 'term',
    //           ),
    //         ),
    //       ),
    //       'wrapper' => array(
    //         'width' => '',
    //         'class' => '',
    //         'id' => '',
    //       ),
    //       'taxonomy' => 'embl_taxonomy',
    //       'field_type' => 'select',
    //       'allow_null' => 1,
    //       'add_term' => 0,
    //       'save_terms' => 0,
    //       'load_terms' => 0,
    //       'return_format' => 'id',
    //       'multiple' => 0,
    //     )
    //   );
    // }
  }

} // VF_Jobs

$plugin = new VF_Jobs(array('init' => true));

?>
