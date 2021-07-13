<?php get_header(); 

$total_results = $wp_query->found_posts;

?>

<?php get_template_part('partials/vf-intro');
 ?>

<section class="embl-grid embl-grid--has-centered-content | vf-content">
  <div></div>
  <div>
    <p><?php echo $total_results; ?> result(s) found for "<?php echo esc_html(get_search_query()); ?>""</p>
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--document.php', false, false)); 
          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
    <div class="vf-grid"> <?php vf_pagination();?></div>
  </div>
  <div></div>
</section>

<?php get_footer(); ?>
