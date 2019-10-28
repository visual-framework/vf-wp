<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>
<section class="vf-grid">
  <main>
    <h1 class="vf-text vf-text-heading--1"><?php echo esc_html($title); ?></h1>
    <?php
    while (have_posts()) {
      the_post();
      include(locate_template('partials/vf-summary--article.php', false, false));
      if ( ! vf_last_post()) {
        echo '<hr class="vf-divider">';
      }
    }
    vf_pagination();
    ?>
  </main>
</section>
<?php

get_footer();

?>
