<?php

get_header();

global $vf_theme;

?>
<div class="vf-u-display-none | used-for-search-index" data-swiftype-name="page-description" data-swiftype-type="text">
  <?php echo swiftype_metadata_description(); ?>
</div>


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
    <?php 
    // Get sticky posts
    $sticky_posts = get_option('sticky_posts');

    // Prepare query arguments for sticky posts
    $sticky_args = array(
        'posts_per_page' => 4,
        'post__in'       => $sticky_posts, // Include sticky posts first
        'orderby'        => 'date',       // Order by date
        'ignore_sticky_posts' => true,    // Treat sticky posts as normal posts
    );

    // Query sticky posts
    $sticky_query = new WP_Query($sticky_args);

    // Count the number of sticky posts
    $sticky_count = $sticky_query->post_count;

    // Calculate how many regular posts to fetch
    $remaining_posts = 4 - $sticky_count;

    // Query regular posts if needed
    $regular_query = null;
    if ($remaining_posts > 0) {
        $regular_args = array(
            'posts_per_page' => $remaining_posts,
            'post__not_in'   => $sticky_posts, // Exclude sticky posts
            'orderby'        => 'date',
        );
        $regular_query = new WP_Query($regular_args);
    }

    // Display sticky posts
    if ($sticky_query->have_posts()) :
        while ($sticky_query->have_posts()) : $sticky_query->the_post(); 
    ?>
    <article class="vf-summary vf-summary--news">
      <span class="vf-summary__date" style="margin-top: 6px;">
        <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      </span>
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto; border: solid 1px #d0d0ce;' ) ); ?>
      <h3 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
      </h3>
    </article>
    <?php 
        endwhile; 
    endif;

    // Display regular posts
    if ($regular_query && $regular_query->have_posts()) :
        while ($regular_query->have_posts()) : $regular_query->the_post(); 
    ?>
    <article class="vf-summary vf-summary--news">
      <span class="vf-summary__date" style="margin-top: 6px;">
        <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      </span>
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto; border: solid 1px #d0d0ce;' ) ); ?>
      <h3 class="vf-summary__title">
        <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
      </h3>
    </article>
    <?php 
        endwhile; 
    endif;

    // Reset post data
    wp_reset_postdata(); 
    ?>
</div>

</section>

<hr class="vf-divider">

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
  <div class="vf-card-container__inner" id="trainingGrid">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Teacher training</h2>
    </div>

    <article class="vf-card vf-card--brand vf-card--striped">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/ON-SITE-LEARNING-LABS-©-EMBL-ELLS.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="<?php echo esc_url(home_url('/teacher-training/?type=train-the-trainer-course')); ?>">Train-the-Trainer Courses<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Intensive, in-person professional development courses to become an EMBL Teacher Ambassador

        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--striped">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/VIRTUAL-LEARNING-LABS-%C2%A9-ELLS.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="<?php echo esc_url(home_url('/teacher-training/?type=ells-virtual-learninglab')); ?>">Virtual LearningLABs<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">
          Virtual professional development courses for educators across the world

        </p>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--striped">
      <img src="https://www.embl.org/ells/wp-content/uploads/2024/10/Screenshot-2024-10-17-at-16.31.09.png"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="<?php echo esc_url(home_url('/teacher-training/?location=online')); ?>">Online Courses
            <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
              width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg></a></h3>
        <p class="vf-card__text">Flexible online courses that educators can complete based on their interests</p>
      </div>
    </article>
    <article class="vf-card vf-card--brand vf-card--striped">
      <img src="https://wwwdev.embl.org/ells/wp-content/uploads/2021/06/CONNECT-LEARNING-LABS-©-ELLS-1.jpg"
        alt="Image alt text" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="<?php echo esc_url(home_url('/teacher-training/?type=ellsconnect-learninglab')); ?>">EMBLconnect Courses
            <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end"
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

<style>
  #trainingGrid {
    grid-template-columns: repeat(4, 1fr) !important;
}

</style>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
