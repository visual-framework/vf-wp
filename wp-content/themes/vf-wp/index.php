<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>

<section class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
    <h1 class="vf-intro__heading"><?php echo esc_html($title); ?></h1>
  </div>
  <div></div>
</section>
<!--/embl-grid-->
<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
  <?php
  while (have_posts()) {
    the_post();
    get_template_part('partials/vf-summary--article');
    if ( ! $vf_theme->is_last_post()) {
      echo '<hr class="vf-divider">';
    }
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
