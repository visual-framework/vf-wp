<?php

get_header();

?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
    <h2 class="vf-intro__subheading" style="margin-bottom: 2rem;">Find anyone at EMBL.
    </h2>
    <p class="vf-intro__text">Search for a person in the <strong>People</strong> tab by name or job title. Or you can
      use the <strong>Teams</strong> tab to find people by research area or organisational group.</p>
  </div>
</section>

<div class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500">
  <div></div>

  <div>
    <div class="vf-tabs | vf-u-margin__bottom--400">
      <ul class="vf-tabs__list" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--1" onclick="ClearFields();">Person</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--2" onclick="ClearFields();">Team members</a>
        </li>
      </ul>
    </div>
    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section" id="vf-tabs__section--1">
        <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".people-search" type="text" value=""
            placeholder="Search by name or job title
" data-clear-btn-id="name-clear-btn">
        </div>
        <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
          <span class="vf-button__text">Reset</span>
        </button>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--2">
        <div class="vf-form__item">
          <input id="search2" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".team-search" type="text" value=""
            placeholder="Search by organisational group" data-clear-btn-id="name-clear-btn">
        </div>
        <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
          <span class="vf-button__text">Reset</span>
        </button>

        <fieldset class="vf-form__fieldset | vf-stack vf-stack--400 | vf-u-margin__top--200">
          <div class="vf-cluster vf-cluster--400">
            <div class="vf-cluster__inner">
              <div class="vf-form__item vf-form__item--radio">
                <input id="teamasc" class="vf-form__radio" type="radio" data-jplist-control="radio-buttons-sort"
                  data-path=".team-search" data-group="data-group-1" data-order="asc" data-type="text" data-name="sort1"
                  name="sort1" /> <label for="teamasc" class="vf-form__label">Sort A-Z</label>
              </div>

              <div class="vf-form__item vf-form__item--radio">
                <input id="teamdsc" class="vf-form__radio" type="radio" data-jplist-control="radio-buttons-sort"
                  data-path=".team-search" data-group="data-group-1" data-order="desc" data-type="text"
                  data-name="sort1" name="sort1" /> <label for="teamdsc" class="vf-form__label">Sort
                  Z-A</label>
              </div>
            </div>
          </div>
        </fieldset>
      </section>
    </div>
  </div>
  <div></div>
</div>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--800 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
          $forthcomingLoop = new WP_Query (array( 
          'post_type' => 'people',
          'orderby' => 'title', 
          'order' => 'ASC', 
          'posts_per_page' => -1,
            ));
          $temp_query = $wp_query;
          $wp_query   = NULL;
          $wp_query   = $forthcomingLoop;
          $current_month = ""; ?>
      <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
      <?php
         include(locate_template('partials/vf-profile.php', false, false)); ?>
      <?php endwhile;?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching people found
        </p>
      </article>
    </div>
    <style>
      .jplist-selected {
        background-color: #707372;
      }

      .jplist-selected a {
        color: #fff;
      }

    </style>

    <nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
      data-items-per-page="15" data-current-page="0" data-name="pagination1">
      <ul class="vf-pagination__list">
        <li class="vf-pagination__item vf-pagination__item--previous-page" data-type="prev">
          <a class="vf-pagination__link">
            Previous<span class="vf-u-sr-only"> page</span>
          </a>
        </li>
        <div data-type="pages" style="display: flex;">
          <li class="vf-pagination__item" data-type="page">
            <a href="#" class="vf-pagination__link">
              {pageNumber}<span class="vf-u-sr-only">page</span>
            </a>
          </li>
        </div>
        <li class="vf-pagination__item vf-pagination__item--next-page" data-type="next">
          <a href="#" class="vf-pagination__link">
            Next<span class="vf-u-sr-only"> page</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>

  <div class="vf-stack vf-stack--400 | vf-content">
    <article class="vf-card vf-card--brand vf-card--bordered">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="https://www.embl.org/search/?searchQuery=jo&activeFacet=Alumni#stq=">Alumni search<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
        <p class="vf-card__text">Search for EMBL Alumni on embl.org</p>
      </div>
    </article>
    <p><a href="https://www.embl.org/internal-information/departments-and-teams/">Departments and teams
      </a></p>
    <p><a href="https://www.embl.org/internal-information/documents/embls-organisational-chart/">EMBL’s organisational
        chart
      </a></p>
  </div>
</div>
</div>

<script type="text/javascript">
  function ClearFields() {
    document.getElementById("name-clear-btn").click();
  }
  jplist.init();

</script>

<?php

get_footer();

?>
