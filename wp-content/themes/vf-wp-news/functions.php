<?php 
	if( ! defined( 'ABSPATH' ) ) exit;

	add_action( 'wp_enqueue_scripts', 'vf_wp_news_enqueue_styles' );
		function vf_wp_news_enqueue_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
	} 

	// require_once('functions/walker-comment.php');
	// require_once('functions/helpers.php');
	require_once('functions/theme.php');
	// require_once('functions/admin.php');
	// require_once('functions/widgets.php');
	// require_once('functions/pagination.php');
	// require_once('functions/comment-form.php');

?>