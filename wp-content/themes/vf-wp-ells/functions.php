<?php

require_once('functions/custom-taxonomies.php');
require_once('functions/ells-breadcrumbs.php');
require_once('functions/learning-labs-post.php');
require_once('functions/teachingbase-post.php');
require_once('functions/insight-lecture-post.php');

// enable featured image
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );


// Newsletter container

if ( ! defined( 'ABSPATH' ) ) exit;

class VF_ELLS_Newsletter extends VF_Plugin{

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_ells_newsletter',
    'post_title' => 'ELLS_Newsletter',
    'post_type'  => 'vf_container'
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_ells_newsletter');
    if (array_key_exists('init', $params)) {
      parent::initialize();
    }
  }

  public function template_callback($block, $content, $is_preview = false, $acf_id) {
    ?><h3>to jest newslettter</h3><?php
   }

} // VF_ELLS_Newsletter

$plugin = new VF_ELLS_Newsletter(array('init' => true));

?>
<?php
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

?>
