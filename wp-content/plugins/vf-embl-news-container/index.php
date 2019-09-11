<?php
/*
Plugin Name: VF-WP EMBL News
Description: VF-WP theme global container.
Version: 0.1.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_EMBL_News extends VF_Plugin {

  private $exists = array(
    'field_display_title',
    'field_teaser',
    'field_canonical_location'
  );

  protected $API = array(
    'pattern'                                => 'vf-news-item-default',
    'filter-content-type'                    => 'article',
    'filter-field-value[field_article_type]' => 'article_timely',
    'sort-field-value[created]'              => 'DESC',
    'source'                                 => 'contenthub',
  );

  function __construct(array $params = array()) {
    parent::__construct('vf_embl_news');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_embl_news',
        'post_title' => 'EMBL News',
        'post_type'  => 'vf_container'
      )
    );

    add_action('init', array($this, 'add_taxonomy_fields'), 11);
  }

  function api_url(array $query_vars = array()) {
    $limit = intval(
      get_field('vf_embl_news_limit', $this->post->ID)
    );

    $vars = array(
      'filter-fields-exists' => implode(',', $this->exists),
      'limit' => $limit ? $limit : 3
    );

    $filter_key = 'filter-field-contains[field_teaser]';

    // Use "Keyword" filter
    $keyword = vf_search_keyword(
      get_field('vf_embl_news_keyword', $this->post->ID)
    );
    if ( ! empty($keyword)) {
      $vars[$filter_key] = $keyword;
    }

    // Prioritise "Term" filter
    if (function_exists('embl_taxonomy_get_term')) {
      $term = embl_taxonomy_get_term(
        get_field('vf_embl_news_term', $this->post->ID)
      );
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $vars[$filter_key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    return parent::api_url(array_merge($vars, $query_vars));
  }

  /**
   * Action: `init`
   * Cannot run on `acf/init` because taxonomy is not registered
   */
  function add_taxonomy_fields() {
    // Add filter based on EMBL Taxonomy terms

    /**
     * Code Commented based on requirement of https://gitlab.ebi.ac.uk/emblorg/backlog/issues/173

    if (function_exists('embl_taxonomy')) {
      acf_add_local_field(
        array(
          'parent' => 'group_vf_embl_news',
          'key' => 'field_vf_embl_news_term',
          'label' => __('Topic', 'vfwp'),
          'name' => 'vf_embl_news_term',
          'type' => 'taxonomy',
          'instructions' => __('Filter articles by this term – takes priority over <b>Keyword</b>.', 'vfwp'),
          'required' => 0,
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
    */


    // Add Factoid clone field
    if (class_exists('VF_Factoid')) {
      acf_add_local_field(
        array(
          'parent' => 'group_vf_embl_news',
          'key' => 'field_vf_embl_news_factoid',
          'label' => 'Factoid',
          'name' => 'vf_embl_news_factoid',
          'type' => 'clone',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'clone' => array(
            0 => 'group_vf_factoid',
          ),
          'display' => 'group',
          'layout' => 'block',
          'prefix_label' => 0,
          'prefix_name' => 0,
        )
      );
    }
  }

} // VF_EMBL_News

$plugin = new VF_EMBL_News(array('init' => true));

?>
