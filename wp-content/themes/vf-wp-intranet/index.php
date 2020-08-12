<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>
<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2">
    <h1 class="vf-text vf-text-heading--1">
      <?php echo esc_html($title); ?>
    </h1>
    <?php
    while (have_posts()) {
      the_post();
      get_template_part('partials/vf-summary--article');
    }
    vf_pagination();
    ?>
  </div>
  <div>

  </div>
</section>
<?php

get_footer();

?>
