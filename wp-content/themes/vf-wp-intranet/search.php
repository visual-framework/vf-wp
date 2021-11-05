<?php get_header(); 

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Intranet_Breadcrumbs')) {
  VF_Plugin::render(VF_Intranet_Breadcrumbs::get_plugin('vf_wp_breadcrumbs_intranet'));
}

$total_results = $wp_query->found_posts;

?>

<section class="vf-hero | vf-u-fullbleed | vf-hero--800 | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      --vf-hero--bg-image-size: auto 28.5rem;
    }

  </style>
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
    <h2 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information">
        EMBL Intranet </a>
    </h2>
  </div>
</section>

<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Search
    </h1>
  </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--400">
  <div></div>
  <div>
    <form role="search" method="get"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <input type="search" class="vf-form__input | vf-search__input" placeholder="Enter your search term"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <div class="vf-form__item | vf-search__item" style="display: none">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="any" selected="">Everything</option>
            <option value="page" name="post_type[]">Pages</option>
            <option value="insites" name="post_type[]">INsites</option>
            <option value="events" name="post_type[]">Events</option>
            <option value="people" name="post_type[]">People</option>
            <option value="documents" name="post_type[]">Documents</option>
          </select>
        </div>
        <input type="submit" class="vf-search__button | vf-button vf-button--primary"
          value="<?php esc_attr_e('Search', 'vfwp'); ?>">
      </div>
    </form>
  </div>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
  </div>
  <div>
    <div class="vf-content">
      <div class="vf-banner vf-banner--alert vf-banner--info | vf-u-margin__bottom--400">
        <div class="vf-banner__content">
          <p class="vf-banner__text">If you haven't found what you are looking for please use <a class="vf-banner__link"
              href="<?php echo 'https://www.embl.org/search/?searchQuery=' . get_search_query() . '&activeFacet=#stq=' . get_search_query() ?>"
              target="_blank">embl.org
              search</a></p>
        </div>
      </div>
      <p><?php echo $total_results; ?> result(s) found for "<?php echo esc_html(get_search_query()); ?>"</p>
      <div class="vf-tabs">
        <ul class="vf-tabs__list" data-vf-js-tabs>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--pages">Pages</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--people">People</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--documents">Documents</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--news">News</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--events">Events</a>
          </li>
        </ul>
      </div>

      <div class="vf-tabs-content" data-vf-js-tabs-content>
        <?php
      if ( have_posts() ) { ?>
        <section class="vf-tabs__section" id="vf-tabs__section--pages">
          <?php while( have_posts() ) { the_post(); ?>
          <?php if ( $post->post_type == 'page' ) { 
           include(locate_template('partials/vf-summary--page.php', false, false));  ?>
          <?php } }?>
        </section>
        <?php
        rewind_posts(); ?>
          <section class="vf-tabs__section" id="vf-tabs__section--people">
            <?php while( have_posts() ) { the_post(); ?>
            <?php if ( $post->post_type == 'people' ) { 
             include(locate_template('partials/vf-profile.php', false, false));  ?>
          <?php } }?>
          </section>
          <?php
        rewind_posts(); ?>
        <section class="vf-tabs__section" id="vf-tabs__section--documents">
          <?php while( have_posts() ) { the_post(); ?>
          <?php if ( $post->post_type == 'documents' ) { 
           include(locate_template('partials/vf-summary--document.php', false, false));  ?>
          <?php } }?>
        </section>
        <?php
        rewind_posts(); ?>
        <section class="vf-tabs__section" id="vf-tabs__section--news">
          <?php while( have_posts() ) { the_post(); ?>
          <?php if ( $post->post_type == 'insites' ) { 
           include(locate_template('partials/vf-summary-insites-latest.php', false, false));  ?>
          <?php } }?>
        </section>
        <?php
        rewind_posts(); ?>
        <section class="vf-tabs__section" id="vf-tabs__section--events">
          <?php while( have_posts() ) { the_post(); ?>
          <?php if ( $post->post_type == 'events' ) { 
           include(locate_template('partials/vf-summary-events.php', false, false));  ?>
          <?php } }?>
        </section>
        <?php
        rewind_posts(); 
      } 
        ?>
      </div>
    </div>
  </div>
</section>

<?php
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
} ?>

<?php get_footer(); ?>
