<div class="embl-grid | vf-u-margin__bottom--0 embl-etc-container vf-u-background-color--blue--dark | vf-u-fullbleed">
  <div class="embl-etc-left-col | vf-u-background-color--blue--dark">
    <h3 class="vf-text vf-text-heading--2 | vf-u-text-color--ui--white | vf-u-padding__top--200 vf-u-margin__bottom--0 | embl-etc-heading">EMBLetc.</h3>
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
  <div class="embl-etc-right-col | vf-u-background-color--blue | vf-u-text-color--ui--white | vf-u-padding--400">
  <h3 class="vf-text vf-text-heading--3 | embl-etc | vf-u-margin__bottom--0">From the current issue:</h3>
  <section class="vf-card-container vf-card-container__col-3 | vf-u-padding__bottom--400 vf-u-padding__top--400" id="cardContainer" style="--vf-card__image--aspect-ratio: 16 / 9;">
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
        'value' => 58531
    ))
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
    <a class="vf-link"
      href="https://www.embl.org/news/embletc-archive/"
      style="color: white;"><em>EMBLetc. </em>archive</a>
        </div>
    </div>
