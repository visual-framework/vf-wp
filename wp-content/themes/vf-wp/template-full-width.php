<?php
/**
* Template Name: Full-width page
*/

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

?>
<main class="vf-body">
  <section class="vf-grid vf-grid__col-1">
    <div class="vf-intro">
      <h1 class="vf-intro__heading"><?php the_title(); ?></h1>
    </div>
  </section>
  <!--/vf-grid-->
  <section class="vf-grid vf-grid__col-1">
    <div>
      <?php

      // the_content();
      $vf_theme->the_content();

      ?>
    </div>
  </section>
  <!--/vf-grid-->
  <?php if (comments_open() || get_comments_number()) { ?>
  <section class="vf-grid vf-grid__col-1">
    <div>
      <?php comments_template(); ?>
    </div>
  </section>
  <!--/vf-grid-->
  <?php } ?>
</main>
<!--/vf-body-->
<?php

get_footer();

?>
