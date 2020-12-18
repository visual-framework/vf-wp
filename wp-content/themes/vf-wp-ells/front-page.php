<?php

get_header();

global $vf_theme;

?>
<!-- NEWS -->

<section class="vf-summary-container" data-vf-google-analytics-region="news">
<div class="vf-section-header">
  <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="vf-section-header__heading vf-section-header__heading--is-link">Featured updates from ELLS<svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>
  <p class="vf-section-header__text">  <span class="vf-u-text--nowrap"> </span></p>
</div>

<div class="vf-section-content">
  <div class="vf-grid vf-grid__col-4">
  <?php $mainloop = new WP_Query (array('posts_per_page' => 4)); 
    while ($mainloop->have_posts()) : $mainloop->the_post(); ?>
<article class="vf-summary vf-summary--news" style="display: block; display: unset;">
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
</article>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
</div>
</section>

<!-- RESOURCES -->

<section class="vf-card-container | vf-u-background-color--grey--lightest vf-u-fullbleed">
  <div class="vf-card-container__inner">

  <div class="vf-section-header">
  <h2 class="vf-section-header__heading" id="section-text"> Resources </h2>
  <p class="vf-section-header__text">Hello everyone who are doing?</p>
</div>
<article class="vf-card vf-card--primary vf-card--striped">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">TeachingBASE</a></h3>

    <p class="vf-card__text">Lorem ipsum dolor sit amet, <a class="vf-card__link" href="JavaScript:Void(0);">consectetur</a> adipisicing elit. Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>

<article class="vf-card vf-card--primary vf-card--striped">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">EMBL Insight Lectures</a></h3>

    <p class="vf-card__text">Lorem ipsum dolor sit amet, <a class="vf-card__link" href="JavaScript:Void(0);">consectetur</a> adipisicing elit. Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>

<article class="vf-card vf-card--primary vf-card--striped">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Microscope in Action</a></h3>

    <p class="vf-card__text">Lorem ipsum dolor sit amet, <a class="vf-card__link" href="JavaScript:Void(0);">consectetur</a> adipisicing elit. Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>

</div>
</section>
              

<?php the_content(); ?>   
<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

<?php

get_footer();

?>
