<?php
/**
 * VF Theme
 * Provides the global `$vf_theme` instance.
 */

if( ! defined( 'ABSPATH' ) ) exit;

require_once('theme-content.php');
require_once('theme-comments.php');
require_once('theme-pagination.php');
require_once('theme-widgets.php');

if ( ! class_exists('VF_Theme') ) :

class VF_Theme {

  // Sub-class instances
  private $theme_content;
  private $theme_comments;
  private $theme_pagination;
  private $theme_widgets;

  // Translation domain
  private $domain = '';

  /**
   * Wrapper for `apply_filters` allowing for experimental tag
   */
  static public function apply_filters() {
    $args = func_get_args();
    $tag1 = array_shift($args);
    $value = apply_filters($tag1, ...$args);
    // Update value and apply experimental filters
    $tag2 = preg_replace('#^vf/#', 'vf/__experimental__/', $tag1);
    if ($tag2 !== $tag1) {
      $args[0] = $value;
      $value = apply_filters($tag2, ...$args);
    }
    return $value;
  }

  /**
   * Wrapper for `do_action` allowing for experimental tag
   */
  static public function do_action() {
    $args = func_get_args();
    $tag1 = array_shift($args);
    do_action($tag1, ...$args);
    $tag2 = preg_replace('#^vf/#', 'vf/__experimental__/', $tag1);
    if ($tag2 !== $tag1) {
      do_action($tag2, ...$args);
    }
  }

  public function __construct() {

    // Initialize sub-class instances
    $this->theme_content    = new VF_Theme_Content();
    $this->theme_comments   = new VF_Theme_Comments();
    $this->theme_pagination = new VF_Theme_Pagination();
    $this->theme_widgets    = new VF_Theme_Widgets();

    // Add theme hooks
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
      'get_the_excerpt',
      array($this, 'get_the_excerpt')
    );

    // Hook for child themes to use the `VF_Theme` class
    // Child `functions.php` runs before the parent theme
    VF_Theme::do_action('vf/theme/init');
  }

  /**
   * Filter the excerpt
   */
  function get_the_excerpt($excerpt, $limit = 300) {
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    if ($limit > 0 && strlen($excerpt) > $limit) {
      $excerpt = substr($excerpt, 0, $limit);
      $excerpt = substr($excerpt, 0, strripos($excerpt, ' '));
      $excerpt = "{$excerpt}&hellip;";
    }
    $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
    if (empty($excerpt)) {
      return '';
    }
    return $excerpt;
  }

  /**
   * Return the default page title for blog archive templates
   */
  public function get_title() {
    $title = get_the_title(get_option('page_for_posts'));
    if (is_search()) {
      $title = sprintf(
        __('Search: %s', 'vfwp'),
        get_search_query()
      );
    } elseif (is_category()) {
      $title = sprintf(
        __('Category: %s', 'vfwp'),
        single_term_title('', false)
      );
    } elseif (is_tag()) {
      $title = sprintf(
        __('Tag: %s', 'vfwp'),
        single_term_title('', false)
      );
    } elseif (is_author()) {
      $title = sprintf(
        __('Author: %s', 'vfwp'),
        get_the_author_meta('display_name')
      );
    } elseif (is_year()) {
      $title = sprintf(
        __('Year: %s', 'vfwp'),
        get_the_date('Y')
      );
    } elseif (is_month()) {
      $title = sprintf(
        __('Month: %s', 'vfwp'),
        get_the_date('F Y')
      );
    } elseif (is_day()) {
      $title = sprintf(
        __('Day: %s', 'vfwp'),
        get_the_date()
      );
    } elseif (is_post_type_archive()) {
      $title = sprintf(
        __('Type: %s', 'vfwp'),
        post_type_archive_title('', false)
      );
    } elseif (is_tax()) {
      $tax = get_taxonomy(get_queried_object()->taxonomy);
      $title = sprintf(
        __('%s Archives:', 'vfwp'),
        $tax->labels->singular_name
      );
    }
    $title = VF_Theme::apply_filters('vf/theme/get_title', $title);
    return $title;
  }

  /**
   * Render the post content
   */
  public function the_content() {
    $this->theme_content->the_content();
  }

  /**
   * Return true if last post in `while (have_posts())` loop?
   */
  public function is_last_post() {
    global $wp_query;
    if ($wp_query instanceof WP_Query) {
      return $wp_query->current_post + 1 >= $wp_query->post_count;
    }
    return false;
  }

  /**
   * Setup theme
   */
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
      'editor-styles',
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
    $supports = VF_Theme::apply_filters('vf/theme/supports', $supports);
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

    // Add Visual Framework editor styles
    if (in_array('editor-styles', $supports)) {
      add_editor_style('assets/css/styles.css');
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
        'slug' => 'grey',
        'color' => '#707372',
      ),
      array(
        'name' => __('EMBL Green', $this->domain),
        'slug' => 'green',
        'color' => '#009f4d',
      ),
      array(
        'name' => __('EMBL Blue', $this->domain),
        'slug' => 'blue',
        'color' => '#307fe2',
      ),
      array(
        'name' => __('EMBL Red', $this->domain),
        'slug' => 'red',
        'color' => '#e40046',
      ),
      // array(
      //   'name' => __('EMBL Purple', $this->domain),
      //   'slug' => 'purple',
      //   'color' => '#8246af',
      // ),
      // array(
      //   'name' => __('EMBL Orange', $this->domain),
      //   'slug' => 'orange',
      //   'color' => '#ffa300',
      // ),
      // array(
      //   'name' => __('EMBL Yellow', $this->domain),
      //   'slug' => 'yellow',
      //   'color' => '#ffcd00',
      // ),
    );
    // Apply filters
    $color_palette = VF_Theme::apply_filters(
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
    $font_sizes = VF_Theme::apply_filters(
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
   * Action: `wp_head`
   */
  public function wp_head() {
    // IE11 polyfill
    // https://github.com/visual-framework/vf-wp/issues/238
    echo '<script nomodule src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>';
    // Inline scripts
    $path = untrailingslashit(get_template_directory());
    $path = "{$path}/assets/scripts/head.js";
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
    if ( ! is_admin()) {
      wp_deregister_script('jquery');
      wp_register_script(
        'jquery',
        $dir . '/assets/scripts/jquery-3.4.1.min.js',
        false,
        '3.4.1',
        true
      );
      wp_enqueue_script('jquery');
    }

    // remove all default gutenberg styling
    // this file: wp-includes/css/dist/block-library/style.min.css
    wp_dequeue_style('wp-block-library');

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

    // run only on the intranet theme
    $theme = wp_get_theme(); // gets the current theme
    if ( 'VF-WP Intranet' == $theme->name || 'VF-WP Intranet' == $theme->parent_theme ) {
    wp_enqueue_script(
      'nearest-location',
      $dir . '/assets/scripts/nearest-location.js',
      array(),
      $theme->version,
      true
    );
   }
  }

  /**
   * Append <body> class for Visual Framework
   */
  public function body_class($classes) {
    $classes[] = 'vf-wp-theme';
    $classes[] = 'vf-body vf-stack vf-stack--400';
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

} // VF_Theme

endif;

?>
