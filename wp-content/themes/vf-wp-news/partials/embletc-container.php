<div class="embl-grid | vf-u-margin__bottom--0 embl-etc-container vf-u-background-color--blue--dark | vf-u-fullbleed">
  <div class="embl-etc-left-col | vf-u-background-color--blue--dark">
    <h3
      class="vf-text vf-text-heading--2 | vf-u-text-color--ui--white | vf-u-padding__top--200 vf-u-margin__bottom--0 | embl-etc-heading">
      EMBLetc.</h3>
    <div class="magazine">
      <?php if ( is_active_sidebar( 'topics_left' ) ) : ?>
      <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <?php dynamic_sidebar( 'topics_left' ); ?>
      </div><?php endif; ?>

      <?php if ( is_active_sidebar( 'magazine_cover_2' ) ) : ?>
      <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <?php dynamic_sidebar( 'magazine_cover_2' ); ?>
      </div><?php endif; ?>
    </div>
  </div>
  <div
    class="embl-etc-right-col | vf-u-background-color--blue | vf-u-text-color--ui--white | vf-u-padding__top--400 vf-u-padding__bottom--400 vf-u-padding__left--800 vf-u-padding__right--800">
    <div class="vf-section-header">
      <a href="https://www.embl.org/news/embletc/issue-100"
        class="vf-section-header__heading vf-section-header__heading--is-link cardWhiteLink">From the current issue <svg
          class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="" fill-rule="nonzero"></path>
        </svg></a>
    </div>
    <section class="vf-card-container vf-card-container__col-3 | vf-u-padding__bottom--400 vf-u-padding__top--400"
      id="cardContainer" style="--vf-card__image--aspect-ratio: 16 / 9;">
      <div class="vf-card-container__inner">
        <?php
      $latestPostLoop2 = new WP_Query(array(
        'post_type' => 'embletc',
        'posts_per_page' => -1, 
        'shuffle' => 3,
        'meta_key'  => 'embletc_issue',
        'meta_query' => array(
          array (
        'key' => 'embletc_issue',
        'compare' => 'LIKE',
        'value' => 58531 ))
    ));
      while ($latestPostLoop2->have_posts()) : $latestPostLoop2->the_post(); ?>
        <?php	$ids[] = get_the_ID(); ?>
        <?php include(locate_template('partials/vf-summary--embletc.php', false, false)); ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      </div>

    </section>
    <p class="vf-text--body">Looking for past print editions of <em>EMBLetc.</em>?
      Browse our archive, going back 20 years.
    </p>
    <a class="vf-link" href="https://www.embl.org/news/embletc-archive/" style="color: white;"><em>EMBLetc.
      </em>archive</a>
  </div>
</div>