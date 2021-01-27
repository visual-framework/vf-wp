<?php
$tag_id = get_queried_object()->term_id;



get_header();

?>
<section class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1 class="vf-text vf-text-heading--1">
      <?php wp_title(''); ?>
    </h1>
    <h3>Pages</h3>
  <?php
    $pagesLoop = new WP_Query( array( 
        'post_type' => 'page', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC', 
        'paged' => $paged ) );
    if ( $pagesLoop->have_posts() ) :
        while ( $pagesLoop->have_posts() ) : $pagesLoop->the_post();
                    include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }      
         else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
         }
        endwhile;
         endif;
    wp_reset_postdata();
?>

    <h3>Documents</h3>
  <?php
    $documentsLoop = new WP_Query( array( 
        'post_type' => 'documents', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $documentsLoop->have_posts() ) :
        while ( $documentsLoop->have_posts() ) : $documentsLoop->the_post();
                    include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }         
         else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
         }
        endwhile;
         endif;
    wp_reset_postdata();
?>
    <h3>Insites</h3>
  <?php
    $insitesLoop = new WP_Query( array( 
        'post_type' => 'insites', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $insitesLoop->have_posts() ) :
        while ( $insitesLoop->have_posts() ) : $insitesLoop->the_post();
                    include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }         
         else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
         }
        endwhile;
         endif;
    wp_reset_postdata();
?>

<h3>Community blog</h3>
  <?php
    $blogLoop = new WP_Query( array( 
        'post_type' => 'community-blog', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $blogLoop->have_posts() ) :
        while ( $blogLoop->have_posts() ) : $blogLoop->the_post();
                    include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }         
         else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
         }
        endwhile;
         endif;
    wp_reset_postdata();
?>


  </div>
  <div>

  </div>
</section>
<?php

get_footer();

?>
