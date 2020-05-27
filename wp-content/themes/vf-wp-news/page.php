<?php

get_header();

the_post();


?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">

      <h1 class="vf-text vf-text--display-l"><?php the_title(); ?></h1>

      <?php the_content(); ?>

    </main>
    <?php if (is_active_sidebar('sidebar-page')) { ?>
    <aside class="vf-inlay__content--additional">
      <?php vf_sidebar('sidebar-page'); ?>
    </aside>
    <?php } ?>
  </div>
</section>
<?php get_footer(); ?>
