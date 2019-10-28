<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP') ) :

class VF_Theme {

  protected $domain = '';

  public function __construct() {
    add_action(
      'after_setup_theme',
      array($this, 'after_setup_theme')
    );
    add_action(
      'wp_head',
      array($this, 'wp_head'),
      5
    );
    if ( ! is_admin()) {
      add_filter(
        'script_loader_tag',
        array($this, 'script_loader_tag'),
        10, 2
      );
    }
    add_action(
      'wp_enqueue_scripts',
      array($this, 'wp_enqueue_scripts'),
      20
    );
    add_filter(
      'body_class',
      array($this, 'body_class')
    );
    add_filter(
      'option_blogdescription',
      array($this, 'option_blogdescription'),
      10, 1
    );
  }

  public function after_setup_theme() {
    // Setup theme translation domain
    $theme = wp_get_theme();
    $this->domain = $theme->get('TextDomain');
    $dir = untrailingslashit(get_template_directory());
    load_theme_textdomain($this->domain, "{$dir}/languages");

    register_nav_menu('primary', __('Primary', $this->domain));
    register_nav_menu('secondary', __('Secondary', $this->domain));

    /**
     * Setup default theme supports
     */
    $supports = array(
      'title-tag',
      'disable-custom-font-sizes',
      'disable-custom-colors',
      'responsive-embeds',
      array(
        'html5',
        array(
          'comment-list',
          'comment-form',
          'search-form',
          'gallery',
          'caption'
        )
      )
    );

    // Add default color palette
    add_filter(
      'vf/theme/supports',
      array($this, 'editor_color_palette'),
      1, 1
    );

    // Add default font sizes
    add_filter(
      'vf/theme/supports',
      array($this, 'editor_font_sizes'),
      1, 1
    );

    // Filter and validate supports
    $supports = apply_filters('vf/theme/supports', $supports);
    if ( ! is_array($supports)) {
      $supports = array();
    }

    // Add theme support options
    foreach ($supports as $option) {
      if (is_string($option)) {
        add_theme_support($option);
      }
      if (is_array($option) && count($option) > 1 && is_string($option[0])) {
        add_theme_support($option[0], $option[1]);
      }
    }

  }

  /**
   * Filter: `vf/theme/supports`
   * Setup and filter initial Gutenberg editor color palette
   * https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-color-palettes
   */
  public function editor_color_palette($supports) {
    $color_palette = array(
      array(
        'name' => __('EMBL Grey', $this->domain),
        'slug' => 'embl-grey',
        'color' => '#707372',
      ),
      array(
        'name' => __('EMBL Green', $this->domain),
        'slug' => 'embl-green',
        'color' => '#009f4d',
      ),
      array(
        'name' => __('EMBL Blue', $this->domain),
        'slug' => 'embl-blue',
        'color' => '#307fe2',
      ),
      array(
        'name' => __('EMBL Red', $this->domain),
        'slug' => 'embl-red',
        'color' => '#e40046',
      ),
      // array(
      //   'name' => __('EMBL Purple', $this->domain),
      //   'slug' => 'embl-purple',
      //   'color' => '#8246af',
      // ),
      // array(
      //   'name' => __('EMBL Orange', $this->domain),
      //   'slug' => 'embl-orange',
      //   'color' => '#ffa300',
      // ),
      // array(
      //   'name' => __('EMBL Yellow', $this->domain),
      //   'slug' => 'embl-yellow',
      //   'color' => '#ffcd00',
      // ),
    );
    // Apply filters
    $color_palette = apply_filters(
      'vf/theme/editor_color_palette',
      $color_palette
    );
    // Append to theme supports
    $supports[] = array(
      'editor-color-palette',
      $color_palette
    );
    return $supports;
  }

  /**
   * Filter: `vf/theme/supports`
   * Setup and filter initial Gutenberg editor font sizes
   * https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-font-sizes
   */
  public function editor_font_sizes($supports) {
    $font_sizes = array(
      array(
        'name' => __('Extra Small', $this->domain),
        'shortName' => __('XS', $this->domain),
        'size' => 13.99,
        'slug' => 'extra-small'
      ),
      array(
        'name' => __('Small', $this->domain),
        'shortName' => __('S', $this->domain),
        'size' => 14,
        'slug' => 'small'
      ),
      array(
        'name' => __('Regular', $this->domain),
        'shortName' => __('R', $this->domain),
        'size' => 16,
        'slug' => 'regular'
      ),
      array(
        'name' => __('Large', $this->domain),
        'shortName' => __('L', $this->domain),
        'size' => 19,
        'slug' => 'large'
      ),
      array(
        'name' => __('Extra Large', $this->domain),
        'shortName' => __('XL', $this->domain),
        'size' => 32,
        'slug' => 'extra-large'
      ),
    );
    // Apply filters
    $font_sizes = apply_filters(
      'vf/theme/editor_font_sizes',
      $font_sizes
    );
    // Append to theme supports
    $supports[] = array(
      'editor-font-sizes',
      $font_sizes
    );
    return $supports;
  }

  /**
   * Output inline JavaScript to `<head>`
   */
  public function wp_head() {
    $path = untrailingslashit(get_template_directory());
    $path = "{$path}/assets/js/head.js";
    if (file_exists($path)) {
      echo "<script>\n";
      include($path);
      echo "</script>\n";
    }
  }

  /**
   * Load CSS and JavaScript assets
   */
  public function wp_enqueue_scripts() {
    $theme = wp_get_theme();
    $dir = untrailingslashit(get_template_directory_uri());

    // Use jQuery supplied by theme and enqueue in footer
    wp_deregister_script('jquery');
    wp_register_script(
      'jquery',
      $dir . '/assets/js/jquery-3.3.1.min.js',
      false,
      '3.3.1',
      true
    );
    wp_enqueue_script('jquery');

    // Add VF stylesheet if global option exists
    if (function_exists('vf_get_stylesheet') && vf_get_stylesheet()) {
      wp_enqueue_style(
        'vf',
        vf_get_stylesheet(),
        array(),
        $theme->version,
        'all'
      );
    }

    // Add VF JavaScript if global option exists
    if (function_exists('vf_get_javascript') && vf_get_javascript()) {
      wp_enqueue_script(
        'vf',
        vf_get_javascript(),
        array('jquery'),
        $theme->version,
        true
      );
    }

    // Add theme specific stylesheet
    wp_enqueue_style(
      'vfwp',
      $dir . '/assets/css/styles.css',
      array(),
      $theme->version,
      'all'
    );

    // Enqueue VF JS
    wp_enqueue_script(
      'vf-scripts',
      $dir . '/assets/scripts/scripts.js',
      array(),
      $theme->version,
      true
    );
  }

  /**
   * Append <body> class for Visual Framework
   */
  public function body_class($classes) {
    $classes[] = 'vf-body';
    $classes[] = 'vf-wp-theme';
    return $classes;
  }

  /**
   * Allow enqueued scripts to use `async` or `defer` attributes
   *  by prefixing `--async` or `--defer` to the `$handle`
   */
  public function script_loader_tag($tag, $handle) {
    if (preg_match('#--(async|defer)$#', $handle, $matches)) {
      $tag = str_replace('<script ', "<script {$matches[1]} ", $tag);
    }
    return $tag;
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

} // VF_WP

endif;

global $vf_theme;
if ( ! isset($vf_theme)) {
  $vf_theme = new VF_Theme();
}

?>
