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


// POST THUMBNAILS
// 
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// ESTIMATED READING TIME 
 
function reading_time() {
	$content = get_post_field( 'post_content', $post->ID );
	$word_count = str_word_count( strip_tags( $content ) );
	$readingtime = ceil($word_count / 200);
		if ($readingtime == 1) {
	$timer = " min";
		} else {
	$timer = " min";
}
	$totalreadingtime = $readingtime . $timer;
		return $totalreadingtime;
}

if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

//ASSIGNING CLASSES TO CATEGORIES

add_filter('wp_list_categories', 'add_slug_class_wp_list_categories');
function add_slug_class_wp_list_categories($list) {

    $cats = get_categories('hide_empty=0');
    foreach($cats as $cat) {
        $find = 'cat-item-' . $cat->term_id . '"';
        $replace = 'cat-item-' . $cat->slug . ' cat-item-' . $cat->term_id . '"';
        $list = str_replace( $find, $replace, $list );
        $find = 'cat-item-' . $cat->term_id . ' ';
        $replace = 'cat-item-' . $cat->slug . ' cat-item-' . $cat->term_id . ' ';
        $list = str_replace( $find, $replace, $list );
    }

    return $list;
}

 ?>


<?php 

// POPULAR POSTS 

function shapeSpace_popular_posts($post_id) {
	$count_key = 'popular_posts';
	$count = get_post_meta($post_id, $count_key, true);
	if ($count == '') {
		$count = 0;
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
	} else {
		$count++;
		update_post_meta($post_id, $count_key, $count);
	}
}

function shapeSpace_track_posts($post_id) {
	if (!is_single()) return;
	if (empty($post_id)) {
		global $post;
		$post_id = $post->ID;
	}
	shapeSpace_popular_posts($post_id);
}
add_action('wp_head', 'shapeSpace_track_posts');



//ADDING CLASS TO CATEGORY
//
function add_class_to_category( $thelist, $separator, $parents){
    $class_to_add = 'vf-link';
    return str_replace('<a href="',  '<a class="'. $class_to_add. '" href="', $thelist);
}

add_filter('the_category', __NAMESPACE__ . '\\add_class_to_category',10,3);


 // MAGAZINE COVER WIDGET 1
function mag1_widgets_init() {

	register_sidebar( array(
		'name'          => 'Magazine right',
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

// REMOVE LIST FROM CATEGORY

foreach((get_the_category()) as $category) { 
    echo $category->category_nicename . ' '; 
    echo get_category_link($category->cat_ID);;
} 

// DISPLAY CUSTOM FIELDS IN THE MENU

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_filter('acf/settings/show_admin', '__return_true');
function my_acf_save_post( $post_id ) {
    // get new value
    $user = get_field( 'author', $post_id );
	if( $user ) {
		wp_update_post( array( 'ID'=>$post_id, 'post_author'=>$user['ID']) ); 
	}
}
add_action('acf/save_post', 'my_acf_save_post', 20);

// ARCHIVES PAGE
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'archives',
        array(
            'labels' => array(
                'name' => __( 'archives' ),
                'singular_name' => __( 'archives' )
            ),
        'public' => true,
        'has_archive' => true,
        )
    );
}
// CUSTOM ACF COLOR PICKER SWATCHES

function my_acf_collor_pallete_script() {
    ?>
    <script type="text/javascript">
    (function($){
        
        acf.add_filter('color_picker_args', function( args, $field ){

            // do something to args
            args.palettes = ['#007B53', '#54585A','#A6093D','#193F90','#563D82','#B65417', '#6CC24A', '#D0D0CE','#E58F9E','#8BB8E8','#CBA3D8','#EFC06E']
            
            console.log(args);
            // return
            return args;
        });
        
    })(jQuery);
    </script>
    <?php
}

add_action('acf/input/admin_footer', 'my_acf_collor_pallete_script');

function my_acf_collor_pallete_css() {
    ?>
    <style>
		
		
        .acf-color_picker .iris-picker .iris-border{
            width: 200px !important;
            height: 10px !important;
        }
        .acf-color_picker .wp-picker-input-wrap,
        .acf-color_picker .iris-picker .iris-slider,
        .acf-color_picker .iris-picker .iris-square{
            display:none !important;
        }
    </style>
    <?php
}

add_action('acf/input/admin_head', 'my_acf_collor_pallete_css');

?>