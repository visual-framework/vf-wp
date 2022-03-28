<?php
/*
Plugin Name: VF-WP Jobs
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

class VF_Jobs extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_jobs',
    'post_title' => 'Jobs',
  );

  // Plugin uses Content Hub API
  public function is_api() {
    return true;
  }

  function __construct(array $params = array()) {
    parent::__construct('vf_jobs');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
    if (array_key_exists('init', $params)) {
      $this->init();
    }

  }

  private function init() {
    parent::initialize();
    add_action('after_setup_theme',
      array($this, 'add_taxonomy_fields'),
      10
    );
    // Do no wrap in `vf-content` classes
    add_filter(
      'vf/theme/content/is_block_wrapped/name=acf/vf-jobs',
      '__return_false'
    );

  }

  /**
   * Action: `after_setup_theme`
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
      $who = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_who')
      );
      $what = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_what')
      );
      $where = embl_taxonomy_get_term(
        embl_taxonomy()->settings->get_field('embl_taxonomy_term_where')
      );

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


      $choices['term'] = sprintf(
        $format,
        __('Other jobs', 'vfwp'),
        __('select a term', 'vfwp')
      );

    }

    acf_add_local_field(
      array(
        'parent' => 'group_vf_jobs',
        'key' => 'field_vf_jobs_filter',
        'label' => __('Show', 'vfwp'),
        'name' => 'vf_jobs_filter_tax',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
          array(
            array(
              'field' => 'field_62419b02e9147',
              'operator' => '==',
              'value' => 'taxonomy',
            ),
          ),
        ),        'wrapper' => array(
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


    if (function_exists('embl_taxonomy')) {
      acf_add_local_field(
        array(
          'parent' => 'group_vf_jobs',
          'key' => 'field_vf_jobs_term',
          'label' => __('EMBL Taxonomy', 'vfwp'),
          'name' => 'vf_jobs_term',
          'type' => 'taxonomy',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_vf_jobs_filter',
                'operator' => '==',
                'value' => 'term',
              ),
            ),
          ),
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'taxonomy' => 'embl_taxonomy',
          'field_type' => 'select',
          'allow_null' => 1,
          'add_term' => 0,
          'save_terms' => 0,
          'load_terms' => 0,
          'return_format' => 'id',
          'multiple' => 0,
        )
      );
    }
  }

} // VF_Jobs

$plugin = new VF_Jobs(array('init' => true));

?>
