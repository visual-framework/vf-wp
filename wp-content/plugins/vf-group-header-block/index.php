<?php
/*
Plugin Name: VF-WP Group Header
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

class VF_Group_Header extends VF_Plugin {

  private $is_minimal = false;

  protected $API = array(
    'filter-content-type' => 'person',
    'source'              => 'contenthub',
  );

  function __construct(array $params = array()) {
    parent::__construct('vf_group_header');
    if (array_key_exists('minimal', $params) && $params['minimal']) {
      $this->is_minimal = true;
    }
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  function is_minimal() {
    return $this->is_minimal;
  }

  private function init() {
    parent::initialize(
      array(
        'file'       => __FILE__,
        'post_name'  => 'vf_group_header',
        'post_title' => 'Group Header'
      )
    );

    add_action('admin_head', array($this, 'admin_head'), 15);
  }

  function api_url(array $query_vars = array()) {
    $vars = array(
      'pattern'  => 'vf-summary-profile-r',
      'limit' => 1,
      'sort-field-value[changed]' => 'DESC',
      'filter-field-value[field_person_positions.entity.field_position_membership]' => 'leader'
    );

    if ($this->is_minimal()) {
      $vars['pattern'] = 'vf-summary-profile-l';
    }

    if (function_exists('embl_taxonomy_get_term')) {
      $term_id = get_field('embl_taxonomy_term_what', 'option');
      $term = embl_taxonomy_get_term($term_id);
      if ($term && array_key_exists(EMBL_Taxonomy::META_NAME, $term->meta)) {
        $key = 'filter-field-contains[field_person_positions.entity.field_position_team.entity.title]';
        $vars[$key] = $term->meta[EMBL_Taxonomy::META_NAME];
      }
    }

    return parent::api_url(
      array_merge($vars, $query_vars)
    );
  }

  function heading_html() {
    $heading = get_field('vf_group_header_heading', $this->post->ID);
    $heading = esc_html($heading);
    $heading = trim($heading);
    $heading = "<h1 class=\"vf-lede\">{$heading}</h1>";

    if ( ! vf_html_empty($heading) || ! class_exists('VF_Cache')) {
      return $heading;
    }

    // if heading is empty, use the contenthub description
    $term_id = get_field('embl_taxonomy_term_what', 'option');
    $uuid = embl_taxonomy_get_uuid($term_id);

    if ( ! $uuid) {
      return $heading;
    }

    $url = VF_Cache::get_api_url();
    $url .= '/pattern.html';
    $url = add_query_arg(array(
      'filter-uuid'         => $uuid,
      'filter-content-type' => 'profiles',
      'pattern'             => 'node-teaser',
      'source'              => 'contenthub',
    ), $url);

    $heading = VF_Cache::fetch($url);

    $heading = preg_replace(
      '#<p>#',
      '<h1 class="vf-lede">',
      $heading
    );

    $heading = preg_replace(
      '#</p>#',
      ' <a class="vf-link" href="'.home_url('/about/').'">' . __('Read more', 'vfwp') . '</a>.</h1>',
      $heading
    );

    return $heading;
  }

  function admin_head() {
?>
<style>
.wp-block[data-type="vf/group-header"] {
  max-width: none;
}
</style>
<?php
  }

} // VF_Group_Header

$plugin = new VF_Group_Header(array('init' => true));

?>
