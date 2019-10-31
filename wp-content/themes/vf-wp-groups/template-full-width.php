<?php
/**
* Template Name: Full-width page
*/

get_header();

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--full-width">
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </main>
  </div>
</section>
<?php

get_footer();

?>
