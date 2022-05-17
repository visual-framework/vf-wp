<?php
// global categories array
function categories_global() {
    global $categories_embl;
    $categories_embl = 'science,lab-matters,events,alumni';
}
add_action( 'init', 'categories_global' );

// global languages array
function languages_global() {
    global $excluded_translations_array;
    $excluded_translations = get_posts(array(
        'numberposts'   => -1,
        'post_type'     => 'post',
        'meta_query'    => array(
          'relation' => 'OR',
          array(
              'key'       => 'select_translations',
              'value'     => 'french',
              'compare' => 'LIKE'
          ),
          array(
              'key'       => 'select_translations',
              'value'     => 'italian',
              'compare' => 'LIKE'
          ),
          array(
              'key'       => 'select_translations',
              'value'     => 'catalan',
              'compare' => 'LIKE'
          ),
          array(
              'key'       => 'select_translations',
              'value'     => 'spanish',
              'compare' => 'LIKE'
          ),
          array(
              'key'       => 'select_translations',
              'value'     => 'german',
              'compare' => 'LIKE'
      
          )
      ) 
      ));
    $excluded_translations_array = array_column($excluded_translations, 'ID');
      
}
add_action( 'init', 'languages_global' );
?>