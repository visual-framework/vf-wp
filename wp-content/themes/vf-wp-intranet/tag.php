<?php
$tag_id = get_queried_object()->term_id;



get_header();

?>
    <h1 class="vf-text vf-text-heading--1">
      <?php wp_title(''); ?>
    </h1>
<section class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
  <?php
    $pagesLoop = new WP_Query( array( 
        'post_type' => 'page', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC', 
        'paged' => $paged ) );
    if ( $pagesLoop->have_posts() ) : ?>
    <div class="vf-links vf-links--tight vf-links__list--s">
  <h3 class="vf-links__heading">Pages</h3>
  <ul class="vf-links__list vf-links__list--secondary | vf-list">
  <?php
        while ( $pagesLoop->have_posts() ) : $pagesLoop->the_post();
                    include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
  </ul>
</div>
<hr class="vf-divider">
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
        <div class="vf-links vf-links--tight vf-links__list--s">
      <h3 class="vf-links__heading">Documents</h3>
      <ul class="vf-links__list vf-links__list--secondary | vf-list">
      <?php
        while ( $documentsLoop->have_posts() ) : $documentsLoop->the_post();
                    include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
        </ul>
      </div>
      <hr class="vf-divider">
              <?php
         endif;
    wp_reset_postdata();
?>
  <?php
    $insitesLoop = new WP_Query( array( 
        'post_type' => 'insites', 
        'tag_id' => $tag_id,
        'orderby' => 'title',
        'order'   => 'ASC',  
        'paged' => $paged ) );
    if ( $insitesLoop->have_posts() ) : ?>
        <div class="vf-links vf-links--tight vf-links__list--s">
      <h3 class="vf-links__heading">INsites</h3>
      <ul class="vf-links__list vf-links__list--secondary | vf-list">
      <?php
        while ( $insitesLoop->have_posts() ) : $insitesLoop->the_post();
                    include(locate_template('partials/vf-summary--tag.php', false, false)); 
        endwhile; ?>
        </ul>
      </div>
              <?php
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
