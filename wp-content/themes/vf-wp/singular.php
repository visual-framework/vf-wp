<?php

get_header();

?>
<section class="vf-grid">
  <main>
    <h1 class="vf-text vf-text--heading-xl"><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php
    if (comments_open() || get_comments_number()) {
      comments_template();
    }
    ?>
  </main>
</section>
<?php

get_footer();

?>
