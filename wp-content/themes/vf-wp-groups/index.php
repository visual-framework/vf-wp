<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1">
        <?php echo esc_html($title); ?>
      </h1>
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
    </main>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <aside class="vf-inlay__content--additional">
      <?php vf_sidebar('sidebar-blog'); ?>
    </aside>
    <?php } ?>
  </div>
</section>
<?php

get_footer();

?>
