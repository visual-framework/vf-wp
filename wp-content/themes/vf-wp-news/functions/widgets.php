<?php
 // MAGAZINE COVER WIDGET 1
 function mag1_widgets_init() {

	register_sidebar( array(
		'name'          => 'Magazine cover right',
		'id'            => 'magazine_cover_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'mag1_widgets_init' );

// MAGAZINE COVER WIDGET 2
function mag2_widgets_init() {

	register_sidebar( array(
		'name'          => 'Magazine cover left',
		'id'            => 'magazine_cover_2',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'mag2_widgets_init' );

 // TOPICS WIDGET 1
 function top1_widgets_init() {

	register_sidebar( array(
		'name'          => 'Topics left',
		'id'            => 'topics_left',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );

}
add_action( 'widgets_init', 'top1_widgets_init' );

 // TOPICS WIDGET 2
 function top2_widgets_init() {

	register_sidebar( array(
		'name'          => 'Topics right',
		'id'            => 'topics_right',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );

}
add_action( 'widgets_init', 'top2_widgets_init' );
?>