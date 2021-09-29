<?php
$tag_id = get_queried_object()->term_id;

get_header();

?>

<h1 class="vf-text vf-text-heading--1">
  <?php wp_title(''); ?>
</h1>

<section class="vf-content">
  <?php
    $pagesLoop = new WP_Query( array( 
        'post_type' => 'page', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC', 
        'paged' => $paged ) );
    if ( $pagesLoop->have_posts() ) : ?>
  <div class="embl-grid">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Pages</h2>
    </div>
    <div class="vf-grid vf-grid__col-3 vf-u-grid-gap--400">
      <?php
        while ( $pagesLoop->have_posts() ) : $pagesLoop->the_post();
        include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
    </div>
  </div>
  <?php
      endif;
      wp_reset_postdata();
  ?>

  <?php
    $documentsLoop = new WP_Query( array( 
        'post_type' => 'documents', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $documentsLoop->have_posts() ) : ?>
  <hr class="vf-divider">

  <div class="embl-grid">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Documents</h2>
    </div>
    <div class="vf-grid vf-grid__col-3 vf-u-grid-gap--400">
      <?php
        while ( $documentsLoop->have_posts() ) : $documentsLoop->the_post();
        include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
    </div>
  </div>
  <?php
      endif;
      wp_reset_postdata();
  ?>
  <?php
    $insitesLoop = new WP_Query( array( 
        'post_type' => 'internal-news', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $insitesLoop->have_posts() ) : ?>
  <hr class="vf-divider">
  <div class="embl-grid">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">INsites</h2>
    </div>
    <div class="vf-grid vf-grid__col-3 vf-u-grid-gap--400">
      <?php
        while ( $insitesLoop->have_posts() ) : $insitesLoop->the_post();
        include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
    </div>
  </div>
  <?php
      endif;
      wp_reset_postdata();
  ?>
</section>
<?php

get_footer();

?>
