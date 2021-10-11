
<section
  class="vf-news-container vf-news-container--featured | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
  <div class="vf-section-header">
    <a href="/internal-information/news"
      class="vf-section-header__heading vf-section-header__heading--is-link vf-u-margin__bottom--400">Internal news<svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
  </div>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php
          $args = array(
            'post_type' => 'insites',
            'posts_per_page' => 4,
            'post__not_in'   => array( get_the_ID() ),
            'no_found_rows'  => true,
          );
          $featured = new WP_Query ($args);
            while ($featured->have_posts()) : $featured->the_post(); 
            include(locate_template('partials/vf-summary-insites-latest.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>
</section>
