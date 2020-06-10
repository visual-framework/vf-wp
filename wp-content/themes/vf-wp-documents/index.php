<?php

if (
  is_tax('document_topic') ||
  is_tax('document_type')
) {
  get_template_part('archive-document');
  return;
}

get_header();

get_template_part('partials/vf-intro');

?>
<div class="embl-grid">

  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>

  <div class="vf-content">

<h4 class="vf-text vf-text-heading--4 vf-u-margin__top--0">Recently added:</h4>

    <div class="vf-grid vf-grid__col-2">

<?php


// Define custom query parameters
$args = array( 
	'post_type' => 'document',
	'posts_per_page' => 12
);

// Get current page and append to custom query parameters array
$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

// Instantiate custom query
$custom_query = new WP_Query( $args );

// Pagination fix
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $custom_query;

// Output custom query loop
if ( $custom_query->have_posts() ) :
    while ( $custom_query->have_posts() ) :
        $custom_query->the_post();
        get_template_part('partials/vf-summary--document');
    endwhile;
endif;
// Reset postdata
wp_reset_postdata();

// Custom query loop pagination ?>
    </div>
    <!--/vf-grid-->

    <div class="vf-grid"> <?php vf_pagination();?></div>

 <?php 
 // Reset main query object
$wp_query = NULL;
$wp_query = $temp_query; ?>

  </div>
  <!--/vf-content-->
</div>
<!--/embl-grid-->

<?php

get_footer();

?>
