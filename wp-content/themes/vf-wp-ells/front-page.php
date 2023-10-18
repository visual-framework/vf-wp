<?php

get_header();

global $vf_theme;

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>
<!-- SEARCH -->

<section
  class="vf-summary-container | vf-grid vf-grid__col-4 | vf-u-fullbleed | vf-u-background-color-ui--off-white | vf-u-padding__top--500 vf-u-padding__bottom--500 vf-u-margin__bottom--500">
  <div>
  </div>

  <div class="vf-section-content | vf-grid__col--span-2">
    <div>
      <form role="search" method="get" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar"
        action="https://www.embl.org/ells/">
        <div class="vf-form__item">
          <div class="vf-form__item | vf-search__item">
            <input type="search" class="vf-form__input | vf-search__input" value="" name="s"
              placeholder="Search EMBL science education">
          </div>
          <div class="vf-form__item | vf-search__item">
            <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
            <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
              <option value="all" selected="">Everything</option>
              <option value="page" name="post_type[]">Pages</option>
              <option value="post" name="post_type[]">News</option>
              <option value="teachingbase" name="post_type[]">TeachingBASE</option>
              <option value="insight-lecture" name="post_type[]">Insight Lecture</option>
              <option value="learninglab" name="post_type[]">LearningLabs</option>
            </select>
          </div>
          <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm" value="Search">
        </div>
      </form>
    </div>
  </div>
</section>

<!-- NEWS -->

<section class="vf-news-container vf-news-container--featured">
  <div class="vf-section-header">
    <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Latest updates<svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
    <p class="vf-section-header__text"> <span class="vf-u-text--nowrap"> </span></p>
  </div>

  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php $mainloop = new WP_Query (array('posts_per_page' => 4)); 
    while ($mainloop->have_posts()) : $mainloop->the_post(); ?>
    <article class="vf-summary vf-summary--news">
      <span class="vf-summary__date" style="margin-top: 6px;"><time class="vf-summary__date vf-u-text-color--grey"
          style="margin-left: 0;" title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto; border: solid 1px #d0d0ce;' ) ); ?>
      <h3 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
      </h3>
    </article>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
</section>

<!-- RESOURCES -->
<section class="vf-card-container | vf-u-fullbleed  | vf-u-margin__bottom--600 | resources-container">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Educational resources</h2>
    </div>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/TEACHINGBASE-©-EMBL.jpg" alt="Image alt text"
        class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">

        <h3 class="vf-card__heading"><a class="vf-card__link" href="teachingbase">TeachingBASE<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Educational material supporting biology and life sciences teaching in the classroom
        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/MICROSCOPE-IN-ACTION-©-EMBL-ELLS-1.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="microscope-in-action">Microscope in Action<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Hands-on educational resource for teaching fluorescence microscopy and beyond
        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://www.embl.org/ells/wp-content/uploads/2023/03/Nexus-Island_Carpet-Design_EMBL.png"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.embl.org/ells/trec-education/">TREC education<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
        Teacher training, student activities and classroom resources on EMBL’s flagship expedition, TRaversing European Coastlines
        </p>
      </div>
    </article>
  </div>
</section>


<!-- Training -->

<section
  class="vf-card-container | vf-u-fullbleed | vf-u-margin__bottom--600 | training-container | vf-u-background-color-ui--off-white">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Teacher training</h2>
    </div>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/ON-SITE-LEARNING-LABS-©-EMBL-ELLS.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="?post_type=learninglab&llabs-format&llabs-location&llabs-type=ells-learninglab">On-site
            LearningLABs<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Face-to-face professional development courses for European school science teachers

        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/VIRTUAL-LEARNING-LABS-©-ELLS.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="?post_type=learninglab&llabs-format&llabs-location&llabs-type=ells-virtual-learninglab">Virtual
            LearningLABs<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Virtual professional development courses for international school science teachers

        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/CONNECT-LEARNING-LABS-©-ELLS-1.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="?post_type=learninglab&llabs-format&llabs-location&llabs-type=ellsconnect-learninglab">Connect
            LearningLABs<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Local professional development courses for school science teachers run by European
          partners in their language</p>
      </div>
    </article>
  </div>
</section>

<!-- EXPERIENCE SCIENCE -->

<section class="vf-card-container | vf-u-fullbleed | vf-u-margin__bottom--600 | experience-container">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Activities for young learners</h2>
    </div>
    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://www.embl.org/ells/wp-content/uploads/2023/03/WMB_Vernissage_2022-63-m.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="school-visits">School visits<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Tours and workshops at the World of Molecular Biology exhibition in Heidelberg</p>
      </div>
    </article>
    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/EMBL-INSIGHT-LECTURE-©-ELLS-1.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="insight-lecture">EMBL Insight Lectures<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Collection of scientific lectures for high-school
          students on cutting-edge life sciences topics
        </p>
      </div>
    </article>


    <article class="vf-card vf-card--brand vf-card--bordered">
      <img
        src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/EDUCATIONAL-PROJECTS-©-Paola-Bertucci-EMBL-1.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="projects">Educational projects<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Current and past science education projects</p>
      </div>
    </article>
  </div>
</section>

</section>
</div>

<?php the_content(); ?>

<!-- Team up -->

<section class="vf-card-container">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Team up with us</h2>
    </div>

    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="collaborate">Collaborate with us<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Join forces to promote science education in Europe</p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="support">Support us<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Strengthen our mission with a philanthropic contribution</p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="join">Join us<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Find open job and internship opportunities with science education and public engagement</p>
      </div>
    </article>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>