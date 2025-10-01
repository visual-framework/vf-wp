<?php
/* Adds scripts */

// Include custom jQuery in the head
function include_jquery() {
    wp_enqueue_script(
        'jquery', 
        'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', 
        array(), 
        null, 
        false // load in head (false) instead of footer
    );
}
add_action('wp_enqueue_scripts', 'include_jquery');

function vf_enqueue_jquery_ui() {
    // jQuery UI CSS
    wp_enqueue_style(
        'jquery-ui-css',
        'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
        array(),
        '1.13.2'
    );

    // jQuery UI JS
    wp_enqueue_script(
        'jquery-ui-js',
        'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js',
        array('jquery'), // ensures jQuery loads first
        '1.13.2',
        false // load in head (false) to match your jQuery
    );

// Custom autocomplete JS (runs after jQuery UI)
// Custom autocomplete JS (runs after jQuery UI)
wp_add_inline_script('jquery-ui-js', <<<JS
jQuery(document).ready(function($) {
  $("#search-od").autocomplete({
    source: trainingKeywords,
    minLength: 1, // start suggesting after 1 character

  });
});
JS
);


}
add_action('wp_enqueue_scripts', 'vf_enqueue_jquery_ui');

require_once('functions/custom-taxonomies.php');
require_once('functions/custom-post-types.php');
require_once('functions/training.php');
require_once('functions/training-on-demand-feed.php');




//inlcude jplist
function add_scripts() {
  wp_enqueue_script('jplist', get_theme_file_uri( '/scripts/jplist.min.js'));
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );

// CHILD THEME CSS FILE

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

	$parent_style = 'parent-style';

  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style',
	get_stylesheet_directory_uri() . '/style.css',
	array( $parent_style ),
	wp_get_theme()->get('Version')
);
}

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

/**
 * Load ACF JSON from theme
 */
function vf_wp_documents__acf_settings_load_json($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
  }

function vf_get_training_keywords_js() {
    $args = array(
        'post_type' => 'training',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $posts = get_posts($args);
    $keywords = array();

    foreach ($posts as $post_id) {
        $value = get_field('vf-wp-training-keywords', $post_id);
        if ($value) {
            $parts = array_map('trim', explode(',', $value));
            $keywords = array_merge($keywords, $parts);
        }
    }

    $keywords = array_unique($keywords);
    sort($keywords, SORT_STRING | SORT_FLAG_CASE);
    $keywords = array_values($keywords);

    echo '<script>var trainingKeywords = ' . json_encode($keywords) . ';</script>';
}
// Hook into wp_head instead of wp_footer
add_action('wp_head', 'vf_get_training_keywords_js');




