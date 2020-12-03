<?php

get_header();

?>
<div class="vf-grid">
    <div>
      <h1 class="vf-text vf-text-heading--1">
        <?php the_title(); ?>
      </h1>
      <div class="vf-content">
        <?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>
      </div>
    </div>
</div>
<?php

get_footer();

?>
