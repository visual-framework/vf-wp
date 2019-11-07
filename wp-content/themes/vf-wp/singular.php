<?php

get_header();

global $vf_theme;

?>
<main class="vf-body">
  <section class="vf-intro | embl-grid embl-grid--has-centered-content">
    <div></div>
    <div>
      <h1 class="vf-intro__heading"><?php the_title(); ?></h1>
    </div>
    <div></div>
  </section>
  <!--/embl-grid-->
  <section class="embl-grid embl-grid--has-centered-content">
    <div></div>
    <div>
      <?php

      // the_content();
      $vf_theme->the_content();

      ?>
    </div>
    <div></div>
  </section>
  <!--/embl-grid-->
  <?php if (comments_open() || get_comments_number()) { ?>
  <section class="embl-grid embl-grid--has-centered-content">
    <div></div>
    <div>
      <?php comments_template(); ?>
    </div>
    <div></div>
  </section>
  <!--/embl-grid-->
  <?php } ?>
</main>
<!--/vf-body-->
<?php

get_footer();

?>
