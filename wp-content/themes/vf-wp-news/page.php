<?php

get_header();

the_post();

?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
  <?php if (is_active_sidebar('sidebar-page')) { ?>
  <div>
    <?php vf_sidebar('sidebar-page'); ?>
  </div>
  <?php } ?>
</div>
<?php get_footer(); ?>
