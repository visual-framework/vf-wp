<?php

get_header();


?>

<div class="embl-grid">
  <div>
  </div>
  <div class="vf-content">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            the_title();
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
