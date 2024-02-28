<?php
get_header();
the_post();
?>
<div class="vf-u-margin__bottom--800">
<section class="vf-hero vf-hero--1200 | vf-u-fullbleed" style=" --vf-hero--bg-image-size: auto 28.5rem">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <h1 class="vf-hero__heading"><a class="vf-hero__heading_link" href="https://www.embl.org/news/awards-honours">
    Awards & Honours</a></h1>

    <p class="vf-hero__subheading">Subheading</p>
  </div>
</section>
</div>
<div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 | vf-content">
      <div></div>
      <div class="vf-content">

        <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search" class="vf-form__input vf-form__input--filter | inputField" data-jplist-control="textbox-filter"
                data-group="awards" data-name="my-filter-1" data-path=".search-data" type="text" value=""
                placeholder="Search within awards & honours" data-clear-btn-id="name-clear-btn">
            </div>
            <button style="display: none;" type="button" id="name-clear-btn"
              class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
              <span class="vf-button__text">Reset</span>
            </button>
          </div>
        </form>
        <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
          id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
            id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
            class="counter-highlight"></span></p>
      </div>
    </div>
    <section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div>
        <?php include(locate_template('partials/awards-filter.php', false, false)); ?>
      </div>
      <main>
        <div id="upcoming-events" data-jplist-group="awards">
          <?php
         $forthcomingLoop = new WP_Query(array(
          'post_type' => 'awards',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          
        ));
        $temp_query = $wp_query;
        $wp_query   = NULL;
        $wp_query   = $forthcomingLoop;
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary--awards.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary" data-jplist-control="no-results" data-group="awards" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list"></ul>
        </nav>
      </main>
      <div class="vf-content">
      </div>
    </section>




<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

<script type="text/javascript">
  jplist.init({
  });

</script>
<?php  include(locate_template('partials/pagination.php', false, false)); ?>



<?php get_footer(); ?>
