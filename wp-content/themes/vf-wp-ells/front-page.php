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

<section class="vf-card-container | vf-u-background-color-ui--grey--light | vf-u-fullbleed vf-u-margin__bottom--0">
  <div class="vf-card-container__inner">

  <div class="vf-section-header">
  <h2 class="vf-section-header__heading" id="section-text"> Resources </h2>
  <p class="vf-section-header__text">Ut congue, sapien ut placerat hendrerit, lectus ex convallis erat, eu volutpat dui quam non lectus.</p>
</div>
<article class="vf-card">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">TeachingBASE</a></h3>

    <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>

<article class="vf-card">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">EMBL Insight Lectures</a></h3>

    <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>

<article class="vf-card">

  <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg" alt="Image alt text" class="vf-card__image" loading="lazy">
  <div class="vf-card__content | vf-stack vf-stack--400">

    <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Microscope in Action</a></h3>

    <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis, mollitia quos?</p>
  </div>
</article>
</div>
</section>

<!-- Training -->

<div class="homepage-summaries | vf-u-fullbleed vf-u-background-color-ui--off-white | vf-u-padding__top--500 vf-u-padding__bottom--600">

<style>
/* fullbleed image */
.homepage-summaries {
  background: none !important;
}
/* .homepage-summaries::before {
background:url(https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/20201116_Banners_EMBL.org_Option2-03-scaled.jpg); 
background-position: 50%; background-size: cover;
} */
</style>

<section>
  <p><span class="vf-text-heading--3">Training</span></p>
  <p class="vf-section-header__text"> Ut congue, sapien ut placerat hendrerit, lectus ex convallis erat, eu volutpat dui quam non lectus. <span class="vf-u-text--nowrap"> </span></p>
</section>
  <section class="vf-grid vf-grid__col-3 | vf-u-margin__top--600">

    <div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">
    
      <h3 class="vf-box__heading"><a class="vf-box__link" href="/training">Learning Labs</a></h3>
      <p class="vf-box__text">Donec vehicula, nulla at tempor fringilla, quam mauris convallis lectus, in interdum eros lorem ullamcorper ligula. Mauris nec enim erat</p>
    </div>
<div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">

  <h3 class="vf-box__heading"><a class="vf-box__link" href="/research">Connect Learning Labs</a></h3>
  <p class="vf-box__text">Donec vehicula, nulla at tempor fringilla, quam mauris convallis lectus, in interdum eros lorem ullamcorper ligula. Mauris nec enim erat</p>
</div>

<div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">

  <h3 class="vf-box__heading"><a class="vf-box__link" href="/services-facilities">Virtual Learning Labs</a></h3>
  <p class="vf-box__text">Donec vehicula, nulla at tempor fringilla, quam mauris convallis lectus, in interdum eros lorem ullamcorper ligula. Mauris nec enim erat</p>
</div>

  </section>
</div>

<!-- CONTENT -->

<div class="vf-u-fullbleed vf-u-background-color-ui--grey--light | vf-u-padding__top--500 vf-u-padding__bottom--600">

<section>
  <p><span class="vf-text-heading--3">Experience science</span></p>
  <p class="vf-section-header__text"> Ut congue, sapien ut placerat hendrerit, lectus ex convallis erat, eu volutpat dui quam non lectus. <span class="vf-u-text--nowrap"> </span></p>
</section>
  <section class="vf-grid vf-grid__col-3 | vf-u-margin__top--600">

    <div>
    <div class="vf-section-header | vf-u-padding__bottom--400"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" id="section-link" > Events <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
    </svg></a></div>    
    <article class="vf-summary vf-summary--event vf-u-background-color-ui--grey--light">

  <p class="vf-summary__date">2 Nov.-7 Dec. 2020</p>
  <h3 class="vf-summary__title"><a href="###" class="vf-summary__link">ELLS virtual Learning LAB</a></h3>
  <p class="vf-summary__text">“Introducing your microbiome”</p>
  <p class="vf-summary__location">Virtual international teacher training course</p>

</article>

    </div>
    <article class="vf-summary">
    <div class="vf-section-header | vf-u-padding__bottom--400"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" id="section-link" > EMBL Visits <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
    </svg></a></div>
      <p class="vf-summary__text">The European Learning Laboratory for the Life Sciences (ELLS) – EMBL’s education facility – is offering guided visits to EMBL for school groups at selected Fridays throughout the year.</p>
    </article>
    <article class="vf-summary">
    <div class="vf-section-header | vf-u-padding__bottom--400"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="JavaScript:Void(0);" id="section-link" > Projects <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
    </svg></a></div>
      <p class="vf-summary__text">ELLS currently is and has been involved in several projects funded by the European Commission under the Horizon 2020 EU Research and Innovation programme.</p>
    </article>
</div>

  </section>
</div>

<?php the_content(); ?>   

<!-- Team up -->

  <div class="vf-u-fullbleed | vf-u-padding__top--500 vf-u-padding__bottom--600">


<section>
  <h3 class="vf-text-heading--3">Team up with us</h3>
</section>
  <section class="vf-grid vf-grid__col-4 | vf-u-margin__top--600">

    <div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">
    
      <h3 class="vf-box__heading"><a class="vf-box__link" href="/training">Volunteer wit us</a></h3>
            <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

    </div>
<div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">

  <h3 class="vf-box__heading"><a class="vf-box__link" href="/research">Support us</a></h3>
        <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

</div>

<div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">

  <h3 class="vf-box__heading"><a class="vf-box__link" href="/services-facilities">Join us</a></h3>
        <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

</div>

<div class="vf-box vf-box--is-link vf-box-theme--quinary vf-box--normal">

  <h3 class="vf-box__heading"><a class="vf-box__link" href="/services-facilities">Contact</a></h3>
        <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

</div>

  </section>
</div>


              

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>

