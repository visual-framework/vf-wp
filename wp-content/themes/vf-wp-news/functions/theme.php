<?php

if( ! defined( 'ABSPATH' ) ) exit;

add_action('after_setup_theme', 'vf__after_setup_theme');

define('VF_THEME_COLOR', '009f4d');

function vf__after_setup_theme() {
  $theme = wp_get_theme();
  $domain = $theme->get('TextDomain');
  load_theme_textdomain($domain, get_template_directory() . '/languages');

  register_nav_menu('primary', __('Primary', $domain));
  register_nav_menu('secondary', __('Secondary', $domain));

  add_theme_support('title-tag');

  add_theme_support(
    'html5',
    array(
      'comment-list',
      'comment-form',
      'search-form',
      'gallery',
      'caption'
    )
  );

  // Gutenberg config
  add_theme_support('disable-custom-font-sizes');
  add_theme_support('disable-custom-colors');
  add_theme_support('responsive-embeds');
  add_theme_support('editor-styles');

  // No color palette
  add_theme_support('editor-color-palette', array());

  // VF font sizes
  add_theme_support('editor-font-sizes', array(
    array(
      'name' => __('small', $domain),
      'shortName' => __( 'S', $domain),
      'size' => 14,
      'slug' => 'small'
    ),
    array(
      'name' => __('large', $domain),
      'shortName' => __( 'L', $domain),
      'size' => 19,
      'slug' => 'large'
    ),
  ));
}

/**
 * Filter page query
 */
add_filter('pre_get_posts','vf__pre_get_posts');

function vf__pre_get_posts($query) {
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
 * Output inline <head> stuff
 */
add_action('wp_head', 'vf__wp_head__inline', 5);

function vf__wp_head__inline() {
  // Output inline JavaScript
  $path = get_template_directory() . '/assets/js/head.min.js';
  if (file_exists($path)) {
?>
<script>
<?php include($path); ?>

</script>
<?php
  }

  // Output theme customisation
  $theme_color = get_theme_mod('vf_theme_color', VF_THEME_COLOR);
?>
<style>
.vf-wp-theme .vf-box--secondary,
.vf-wp-theme .vf-masthead {
  --vf-masthead__color--background: #<?php echo $theme_color; ?>;
}
</style>
<?php
}

/**
 * Allow enqueued scripts to use `async` or `defer` attributes
 *  by prefixing `--async` or `--defer` to the `$handle`
 */
if ( ! is_admin()) {
  add_filter('script_loader_tag', 'vf__script_loader_tag', 10, 2);
}
function vf__script_loader_tag($tag, $handle) {
  if (preg_match('#--(async|defer)$#', $handle, $matches)) {
    $tag = str_replace('<script ', "<script {$matches[1]} ", $tag);
  }
  return $tag;
}

/**
 * Load CSS and JavaScript assets
 */
add_action('wp_enqueue_scripts', 'vf__wp_enqueue_scripts', 20);

function vf__wp_enqueue_scripts() {
  $theme = wp_get_theme();
  $dir = get_template_directory_uri();

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

  // Register script - let plugins enqueue as necessary
  wp_register_script(
    'accessible-autocomplete',
    $dir . '/assets/js/accessible-autocomplete.min.js',
    array(),
    '1.6.2',
    true
  );
  // Register VF JS
  wp_register_script(
    'vf-scripts',
    $dir . '/assets/scripts/scripts.js',
    array(),
    $theme->version,
    true
  );
  wp_register_style(
    'accessible-autocomplete',
    $dir . '/assets/css/vf-accessible-autocomplete.css',
    array('vfwp'),
    '1.6.2',
    'all'
  );
}

/**
 * Append <body> class for Visual Framework
 */
add_filter('body_class', 'vf__body_class');

function vf__body_class($classes) {
  $classes[] = 'vf-body';
  $classes[] = 'vf-wp-theme';
  if (is_singular('vf_block') || is_singular('vf_container')) {
    return $classes;
  }
  $classes[] = 'vf-u-background-color-ui--grey';
  return $classes;
}

/**
 * Add VF class to primary menu items
 */
add_filter('nav_menu_css_class', 'vf__nav_menu_css_class', 10, 4);

function vf__nav_menu_css_class($classes, $item, $args, $depth) {
  if (in_array($args->theme_location, array('primary', 'secondary'))) {
    return ['vf-navigation__item'];
  }
  return $classes;
}

/**
 * Add VF class to primary menu items
 */
add_filter('nav_menu_link_attributes', 'vf__nav_menu_link_attributes', 10, 4);

function vf__nav_menu_link_attributes($atts, $item, $args, $depth) {
  if (in_array($args->theme_location, array('primary', 'secondary'))) {
    $atts['class'] = 'vf-navigation__link';
  }
  return $atts;
}

/**
 * Filter the blog description to load via Content Hub
 */
add_filter('option_blogdescription', 'vf__blog_description', 10, 1);

function vf__blog_description($value) {
  // remove filter to avoid update recursion
  remove_filter('option_blogdescription', 'vf__blog_description', 10, 1);

  if ( ! class_exists('VF_Cache')) {
    return $value;
  }

  // generate API request
  $uuid = vf__get_site_uuid();
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
 * Shorthand to safely query the UUID of the active "who" term, if set
 */
function vf__get_site_uuid() {

  // first we want to be sure the taxonomy plugin is enabled
  if ( ! function_exists('embl_taxonomy_get_uuid')) {
    print '<!-- To get the UUID, you must enable the EMBL Taxonomy plugin -->' . PHP_EOL;
    return null;
  }

  $term_id = get_field('embl_taxonomy_term_what', 'option');

  if ( ! $term_id ) {
    print '<!-- If you wish to get the group UUID, you must set the group\'s "what" at /wp-admin/options-general.php?page=embl-settings -->';

  }

  $uuid = embl_taxonomy_get_uuid($term_id);

  return $uuid;
}


?>
