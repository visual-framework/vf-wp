<?php

get_header();

global $vf_theme;

?>

<!-- SEARCH -->
<section
  class="vf-summary-container | vf-grid vf-grid__col-4 | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-background-color-ui--off-white vf-u-padding__top--500 vf-u-padding__bottom--500">

  <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Search</h2>
    </div>

  <div class="vf-section-content | vf-grid__col--span-2">
    <style>
      .vf-search--inline .vf-search__input {
        min-width: 300px;
      }

      .vf-search--inline .vf-form__select {
        padding: 8px 12px;
      }

      .vf-search--inline .vf-search__item:not(:first-child) {
        padding-left: 10px;
      }

      .vf-search--inline .vf-search__button {
        top: -6px;
      }
    </style>
    <div>
      <form role="search" method="get" class="vf-form  | vf-search vf-search--inline"
        action="<?php echo esc_url(home_url('/')); ?>">
        <div class="vf-form__item | vf-search__item">
          <input type="search" class="vf-form__input | vf-search__input"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <div class="vf-form__item | vf-search__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="all" selected="">Everything</option>
            <option value="teachingbase" name="post_type[]">TeachingBASE</option>
            <option value="insight-lecture" name="post_type[]">Insight Lecture</option>
            <option value="llabs-event" name="post_type[]">LearningLabs</option>
          </select>
        </div>
        <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
          value="<?php esc_attr_e('Search', 'vfwp'); ?>">
      </form>

    </div>
  </div>
</section>


<!-- NEWS -->

<section class="vf-summary-container" data-vf-google-analytics-region="news">
  <div class="vf-section-header">
    <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Latest updates from ELLS<svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
    <p class="vf-section-header__text"> <span class="vf-u-text--nowrap"> </span></p>
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
        <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
            title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
      </article>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
    </div>
</section>

<!-- RESOURCES -->
<section
  class="vf-card-container | vf-u-background-color-ui--off-white vf-u-fullbleed | vf-u-margin__bottom--0 | resources-container">
  <div class="vf-card-container__inner">

    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Resources</h2>
    </div>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">TeachingBASE</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">EMBL Insight Lectures</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Microscope in Action</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>
  </div>
</section>


<!-- Training -->

<section class="vf-card-container | vf-u-fullbleed | vf-u-margin__bottom--0 | training-container">
  <div class="vf-card-container__inner">

    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Training</h2>
    </div>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Learning Labs</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Connect Learning Labs</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Virtual Learning Labs</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>
  </div>
</section>

<!-- EXPERIENCE SCIENCE -->

<section
  class="vf-card-container | vf-u-fullbleed vf-u-background-color-ui--off-white | vf-u-margin__bottom--0 | experience-container">
  <div class="vf-card-container__inner">

    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Experience Science</h2>
    </div>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Events</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">EMBL Visits</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>

    <article class="vf-card vf-card--primary vf-card--bordered">

      <img
        src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/2020/04/SCHOOLS_1011_ells-learninglab_hd_01_Cool_500px.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__title"><a class="vf-card__link" href="JavaScript:Void(0);">Projects</a></h3>

        <p class="vf-card__text">Sapiente harum, omnis provident saepe aut eius aliquam sequi fugit incidunt reiciendis,
          mollitia quos?</p>
      </div>
    </article>
  </div>
</section>

</section>
</div>

<?php the_content(); ?>

<!-- Team up -->

<div class="vf-u-fullbleed | vf-u-padding__top--500 vf-u-padding__bottom--600">


  <section>
    <h3 class="vf-text-heading--3">Team up with us</h3>
  </section>
  <section class="vf-grid vf-grid__col-3 | vf-u-margin__top--600 | team-container">

    <div class="vf-box vf-box--easy vf-box-theme--primary vf-box--is-link">

      <h3 class="vf-box__heading"><a class="vf-box__link" href="/training">Volunteer wit us</a></h3>
      <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

    </div>
    <div class="vf-box vf-box--is-link vf-box-theme--primary vf-box--easy">

      <h3 class="vf-box__heading"><a class="vf-box__link" href="/research">Support us</a></h3>
      <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

    </div>

    <div class="vf-box vf-box--is-link vf-box-theme--primary vf-box--easy">

      <h3 class="vf-box__heading"><a class="vf-box__link" href="/services-facilities">Join us</a></h3>
      <p class="vf-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>

    </div>


  </section>
</div>




<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
