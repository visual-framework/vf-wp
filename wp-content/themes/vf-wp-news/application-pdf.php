<?php

get_header();

$attachment_id = wp_get_attachment_url();

?>

<main class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--800 | vf-u-margin__bottom--0">
  <div></div>
  <div class="vf-content | vf-u-margin__bottom--800">
    <h2><?php the_title(); ?></h2>
    <figcaption class="vf-figure__caption">
      <?php the_excerpt(); ?>
    </figcaption>
    <p><a href="<?php echo esc_url( $attachment_id ); ?>">Download file</a></p>
  </div>
</main>

<section class="vf-inlay">
  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>
</section>

<?php

get_footer();

?>
