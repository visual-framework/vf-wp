<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Groups_Theme') ) :

require_once('groups-customize.php');
require_once('groups-templates.php');

class VF_Groups_Theme {

  private $theme_customize;
  private $theme_templates;

  public function __construct() {
    // Initialize sub-class instances
    $this->theme_customize = new VF_Groups_Customize();
    $this->theme_templates = new VF_Groups_Templates();

    // Add child theme hooks
    add_filter(
      'option_blogdescription',
      array($this, 'option_blogdescription'),
      10, 1
    );
    add_filter(
      'pre_get_posts',
      array($this, 'pre_get_posts')
    );
    add_action(
      'wp_enqueue_scripts',
      array($this, 'wp_enqueue_scripts')
    );
    add_filter(
      'nav_menu_css_class',
      array($this, 'nav_menu_css_class'),
      10, 4
    );
    add_filter(
      'nav_menu_link_attributes',
      array($this, 'nav_menu_link_attributes'),
      10, 4
    );
  }

  /**
   * Filter the blog description to load via Content Hub
   */
  public function option_blogdescription($value) {
    // remove filter to avoid update recursion
    remove_filter(
      'option_blogdescription',
      array($this, 'option_blogdescription'),
      10, 1
    );

    if ( ! class_exists('VF_Cache')) {
      return $value;
    }

    // generate API request
    $term_id = get_field('embl_taxonomy_term_what', 'option');
    $uuid = embl_taxonomy_get_uuid($term_id);

    if ( ! $uuid) {
      return $value;
    }

    $url = VF_Cache::get_api_url();
    $url .= '/pattern.html';
    $url = add_query_arg(array(
      'filter-uuid'         => $uuid,
      'filter-content-type' => 'profiles',
      'pattern'             => 'node-strapline',
      'source'              => 'contenthub',
    ), $url);

    // cache for one day
    $max_age = 60 * 60 * 24 * 1;

    // fetch content via the Content Hub cache
    $description = VF_Cache::fetch($url, $max_age);

    // strip HTML comments
    $description = preg_replace('#<!--(.*?)-->#s', '', $description);
    // strip edit link
    $description = preg_replace(
      '#<a[^>]*class="[^"]*embl-conditional-edit[^"]*"[^>]*>.*</a>#s',
      '', $description
    );
    // strip tags except for allowed
    $description = wp_kses(
      $description,
      array(
        'span' => array()
      )
    );
    $description = trim($description);

    // save updated description
    if ( ! empty($description) && $value !== $description) {
      $value = $description;
      update_option('blogdescription', $value);
    }

    return $value;
  }

  /**
   * Filter page query
   */
  public function pre_get_posts($query) {
    if (is_admin()) {
      return $query;
    }
    if ( ! $query->is_main_query()) {
      return $query;
    }
    // Exclude non posts from search
    if ($query->is_search()) {
      $query->set('post_type', 'post');
    }
    return $query;
  }

  /**
   * Load CSS and JavaScript assets
   */
  public function wp_enqueue_scripts() {
    $dir = untrailingslashit(get_template_directory_uri());
    // Register assets for "Jobs" template
    if (
      is_page_template('template-jobs.php') &&
      function_exists('embl_taxonomy')
    ) {
      $assets = $dir . '/assets/assets/vfwp-autocomplete';
      wp_register_script(
        'vfwp-autocomplete',
        $assets . '/vfwp-autocomplete.js',
        array(),
        '2.0.1',
        true
      );
      wp_register_style(
        'vfwp-autocomplete',
        $assets . '/vfwp-autocomplete.css',
        array(),
        '2.0.1',
        'all'
      );
      wp_enqueue_script('vfwp-autocomplete');
      wp_enqueue_style('vfwp-autocomplete');
    }
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_css_class($classes, $item, $args, $depth) {
    if (in_array($args->theme_location, array('primary', 'secondary'))) {
      return ['vf-navigation__item'];
    }
    return $classes;
  }

  /**
   * Add VF class to primary menu items
   */
  public function nav_menu_link_attributes($atts, $item, $args, $depth) {
    if (in_array($args->theme_location, array('primary', 'secondary'))) {
      $atts['class'] = 'vf-navigation__link';
    }
    return $atts;
  }

} // VF_Groups_Theme

endif;

?>
