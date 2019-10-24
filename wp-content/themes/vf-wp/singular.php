<?php

get_header();

the_post();

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text--heading-xl"><?php the_title(); ?></h1>

      <?php the_content(); ?>

      <?php
      if (comments_open() || get_comments_number()) {
        comments_template();
      }
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
