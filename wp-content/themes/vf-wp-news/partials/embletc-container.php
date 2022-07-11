<div class="embl-grid | vf-u-margin__bottom--0 embl-etc-container vf-u-background-color--blue--dark | vf-u-fullbleed vf-u-padding__top--600 vf-u-padding__bottom--600"
  style="grid-column-gap: 0">
  <div class="embl-etc-left-col | vf-u-background-color--blue--dark | vf-u-margin__right--0">
    <h3 class="vf-text vf-text-heading--2 | vf-u-text-color--ui--white | vf-u-padding__bottom--600 | embl-etc-heading">EMBLetc.</h3>
  </div>
  <div class="embl-etc-right-col | vf-u-background-color--blue | vf-u-text-color--ui--white | vf-u-padding--400">
    <h3 class="vf-text vf-text-heading--4 | embl-etc">Read the latest Issues of our magazine - <span
        style="font-style: italic;">EMBLetc.</span></h3>
    <div class="vf-grid | vf-grid__col-2">
      <div class="magazine">
        <?php if ( is_active_sidebar( 'magazine_cover_2' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'magazine_cover_2' ); ?>
        </div><?php endif; ?>

        <?php if ( is_active_sidebar( 'topics_left' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'topics_left' ); ?>
        </div><?php endif; ?>
      </div>
      <div class="magazine">
        <?php if ( is_active_sidebar( 'magazine_cover_1' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'magazine_cover_1' ); ?>
        </div><?php endif; ?>
        <?php if ( is_active_sidebar( 'topics_right' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'topics_right' ); ?>
        </div><?php endif; ?>
      </div>
    </div>
    <p class="vf-text--body">Looking for past print editions of <em>EMBLetc.</em>?
      Browse our archive, going back 20 years.
    </p>
    <a class="vf-link"
      href="https://www.embl.org/news/embletc-archive/"
      style="color: white;"><em>EMBLetc. </em>archive</a>
  </div>
</div>