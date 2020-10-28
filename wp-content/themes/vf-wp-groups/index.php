<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800">
    <div class="vf-grid__col--span-2">
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
    </div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <div>
      <?php vf_sidebar('sidebar-blog'); ?>
    </div>
    <?php } ?>
</div>
<?php

get_footer();

?>
