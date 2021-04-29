<?php
get_header();

$country = get_field('amb_country');

?>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <div>
        <h1><?php the_title(); ?></h1>
        <div>
          <?php if ($country) { ?>
          <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Country:</span>&nbsp;<span
              class="vf-u-text-color--grey"><?php echo ($country->name); ?></span></p>
          <?php } ?>
      </div>
      <?php 
        the_content();
      ?>
    </div>
  </div>
  <div>
    <figure class="vf-figure">
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-figure__image' ) ); ?>
    </figure>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
