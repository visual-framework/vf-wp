<?php 
	 add_action( 'wp_enqueue_scripts', 'vf_wp_news_enqueue_styles' );
	 function vf_wp_news_enqueue_styles() {
 		  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
 		  } 
 ?>