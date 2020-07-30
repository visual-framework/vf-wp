<?php

get_header();

get_template_part('partials/vf-intro');

?>

<div class="embl-grid">
  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>
  <div class="vf-content">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            get_template_part('partials/vf-summary--document');
          }
        } else {
          echo '<p>', __('No documents found', 'vfwp'), '</p>';
        } ?>
  <div class="vf-grid"> <?php vf_pagination();?></div>
    <!--/vf-grid-->
  </div>
  <!--/vf-content-->
</div>
<!--/embl-grid-->


<?php

get_footer();

?>





