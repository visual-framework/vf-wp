<?php

get_header();
global $vf_theme;
$today_date = date('Ymd');
?>

<section class="vf-intro | vf-u-margin__bottom--600">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading | vf-u-margin__bottom--400">
      Training catalogue
    </h1>
    <p class="vf-intro__text">Browse all <b>live</b> and <b>on-demand</b> training available for EMBL staff and fellows;
      continue your professional development, improve your skills in data science or complete workplace related courses
      and activities.
    </p>
  </div>
</section>

<div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
  <div></div>
  <div>
    <div class="vf-tabs">
      <ul class="vf-tabs__list" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--live-training" onclick="resetInputs()">Live Training</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--on-demand-training" onclick="resetInputs()">On-demand Training</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="vf-tabs-content" id="typeTraining" data-vf-js-tabs-content>
  <section class="vf-tabs__section" id="vf-tabs__section--live-training">
    <div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 | vf-content">
      <div></div>
      <div class="vf-content">

        <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search" class="vf-form__input vf-form__input--filter | inputLive" data-jplist-control="textbox-filter"
                data-group="data-group-1" data-name="my-filter-1" data-path=".search-data" type="text" value=""
                placeholder="Search within live training" data-clear-btn-id="name-clear-btn">
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
        <?php include(locate_template('partials/training/training-filter.php', false, false)); ?>
      </div>
      <main>
        <div id="upcoming-events" data-jplist-group="data-group-1">
          <?php
         $forthcomingLoop = new WP_Query(array(
          'post_type' => 'training',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'meta_query' => array(
            'relation' => 'AND',
            'date_clause' => array(
              'key' => 'vf-wp-training-start_date',
              'value' => $today_date,
              'type' => 'DATE',
              'compare' => '>=',
            ),
            'training_type_clause' => array(
              'key' => 'vf-wp-training-training_type',
              'value' => 'live',
              'compare' => 'LIKE',
            ),
          ),
          'orderby' => array(
            'date_clause' => 'ASC',
          ),
        ));
        $temp_query = $wp_query;
        $wp_query   = NULL;
        $wp_query   = $forthcomingLoop;
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/training/vf-summary--training.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1" data-name="no-results">
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
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="/internal-information/past-training/">Past live training</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <h3 class="vf-text vf-text-heading--5">Other training and development opportunities</h3>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career
            webinars</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/about/info/alumni/resources/mentorship-progamme/">Mentorship programme</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/entrepreneurship-training-and-events/">Entrepreneurship training & events</a></p>
            <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
        href="https://app.smartsheet.eu/b/form/572da589f818438f967f218736f3a18b">Provide your feedback</a></p>
      </div>
    </section>
  </section>

  <section class="vf-tabs__section" id="vf-tabs__section--on-demand-training">
    <div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 | vf-content">
      <div></div>
      <div class="vf-content">

        <form action="#onDemandFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search-od" class="vf-form__input vf-form__input--filter | inputOnDemand" data-jplist-control="textbox-filter"
                data-group="data-group-2" data-name="my-filter-2" data-path=".search-data-od" type="text" value=""
                placeholder="Search within on-demand training" data-clear-btn-id="name-clear-btn">
            </div>
            <button style="display: none;" type="button" id="name-clear-btn"
              class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
              <span class="vf-button__text">Reset</span>
            </button>
          </div>
        </form>
        <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
          id="total-results-info-od">Showing <span id="start-counter-od" class="counter-highlight"></span><span
            id="end-counter-od" class="counter-highlight"></span> results out of <span id="total-result-od"
            class="counter-highlight"></span></p>
      </div>
    </div>
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">

      <div>
        <?php include(locate_template('partials/training/training-od-filter.php', false, false)); ?>
      </div>
      <main>
        <div id="on-demand-events" data-jplist-group="data-group-2">
          <?php
         $onDemandLoop = new WP_Query(array(
          'post_type' => 'training',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'meta_query' => array(
            'training_type_clause' => array(
              'key' => 'vf-wp-training-training_type',
              'value' => 'on-demand',
              'compare' => 'LIKE',
            ),
          ),
          'orderby' => 'title',
          'order'   => 'ASC',
        ));
        $temp_query = $wp_query;
        $wp_query   = NULL;
        $wp_query   = $onDemandLoop;
          $current_month = ""; ?>
          <?php while ($onDemandLoop->have_posts()) : $onDemandLoop->the_post();?>
          <?php
         include(locate_template('partials/training/vf-summary--training-on-demand.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-2" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data2" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list | paginationListOnDemand"></ul>
        </nav>
      </main>
      <div class="vf-content">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="/internal-information/past-training/">Past live training</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <h3 class="vf-text vf-text-heading--5">Other training and development opportunities</h3>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
        href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
        href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career
        webinars</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
             href="https://www.embl.org/about/info/alumni/resources/mentorship-progamme/">Mentorship programme</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/entrepreneurship-training-and-events/">Entrepreneurship training & events</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
        href="https://app.smartsheet.eu/b/form/572da589f818438f967f218736f3a18b">Provide your feedback</a></p>
      </div>

    </div>
  </section>
</div>
<style>
  :root {
    --embl-grid-module--prime: 16.1rem !important;
  }

</style>

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>


<script>
function resetInputs() {
  // to be added
}
</script>
<?php  include(locate_template('partials/training/pagination.php', false, false)); ?>
<?php  include(locate_template('partials/training/pagination-on-demand.php', false, false)); ?>

<?php

get_footer();

?>
