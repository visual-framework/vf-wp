<?php

get_header();

global $vf_theme;

?>


<!--/embl-grid-->
<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
  <?php
  if (have_posts()) {
    while (have_posts()) {
      the_post();
      get_template_part('partials/vf-summary--article');
      if ( ! $vf_theme->is_last_post()) {
        echo '<hr class="vf-divider">';
      }
    }
  } else {
    echo '<p>', __('No posts found', 'vfwp'), '</p>';
  }
  vf_pagination();
  ?>
  </div>
  <div></div>
</section>
<!--/vf-intro-->

<?php

get_footer();

?>
