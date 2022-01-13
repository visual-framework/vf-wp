<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$title = get_the_title();
?>
<?php 

  
$open_wrap = function($html, $block_name) {
  $html = '
<div class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
' . $html;
return $html;
};

$close_wrap = function($html, $block_name) {
  $html .= '
  </div>
  <div></div>
</div>
<!--/embl-grid-->';
return $html;
};

add_filter(
'vf/__experimental__/theme/content/open_block_wrap',
$open_wrap,
10, 2
);

add_filter(
'vf/__experimental__/theme/content/close_block_wrap',
$close_wrap,
10, 2
);

?>

<?php

  if ( has_block( 'acf/vfwp-intro', $post ) ) {  
  parse_blocks( 'acf/vfwp-intro' ); } 

  else if ( has_block( 'acf/vfwp-page-header', $post ) ) {
  parse_blocks( 'acf/vfwp-page-header' ); }

  else if ( has_block( 'acf/vfwp-hero', $post ) ) {
    parse_blocks( 'acf/vfwp-hero' ); }

else { ?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--0">
  <div></div>
  <div>
    <?php
    $tags = get_the_tags($post->ID);
    if ($tags) {
    $tagslist = array();
    foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-badge">' . $tag->name . '</a>';
    }
    echo implode('  ', $tagslist);
    } 
    ?>
    <h1 class="vf-text vf-text-heading--1">
      <?php echo $title;?>
    </h1>
  </div>
  <div></div>
</section>

<?php }

// If post password required and it doesn't match the cookie.
if ( post_password_required( get_the_ID())) {
    echo get_the_password_form( get_the_ID());
} else {
  $people_array = array();

// the query
$args=array('post_type' => 'people', 'numberposts'=> -1);

$posts = get_posts( $args );

if ( $posts ) {
    foreach ( $posts as $post ) {
        $people_array[] = $post->post_title;
    }
    // echo print_r($people_array);
    $my_array = array("Ilka Singer", "John Doe");
    // echo print_r($my_array);
    $result = array_intersect($people_array, $my_array); 
    print_r($result);  

}

  // $people_array = array();
  // $people_posts = get_posts( array( 'post_type' => 'people', 'numberposts' => -1) ); 
  //   foreach ($people_posts as $people_post):
  //     $posts = people_post->post_title;
  //     foreach ($posts as $post_title ):
  //     $people_array[] = $post_title;
  //     // $res = $people_post->post_title;
  //     // $fruits = explode(', ', $res);
  //     // // $result = array_merge(...$fruits);

  //     // echo gettype($people_post->post_title);
  //   endforeach; 
  //   echo print_r($people_array);
  //   endforeach; 

$vf_theme->the_content();
}
?>
<?php 

get_footer();

?>