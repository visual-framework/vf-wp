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
<div class="embl-grid embl-grid--has-centered-content">

  <div>
    <?php get_template_part('partials/document-filter'); ?>
  </div>

  <div class="vf-content">
    <h4 class="vf-text vf-text-heading--4 vf-u-margin__top--0">Recently added:</h4>
    <div>
      <?php
      if ( have_posts() ) {
        while ( have_posts() ) {
          the_post();
          get_template_part('partials/vf-summary--document');
        }
      } 
      ?>
    </div>
    <div class="vf-grid"> <?php vf_pagination();?></div>
  </div>
  <!--/vf-content-->
</div>
<!--/vf-grid-->

<?php

get_footer();

?>
