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
    <p class="vf-lede">The repository holds the digital copies of internal EMBL documents, reports, brochures
      and various other publications.</p>
    <p class="vf-intro__text"><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_documents_posts()
      ); ?></p>
    <br>
  </div>
</section>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <form action="#eventsFilter" onsubmit="return false;"
    class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item">
        <label class="vf-form__label vf-u-sr-only | vf-search__label" for="textbox-filter">Search</label>
        <input id="textbox-filter" data-jplist-control="textbox-filter" data-group="data-group-1"
          data-name="my-filter-1" data-path=".vf-summary__title" data-id="search" type="text" value=""
          placeholder="Filter by document title" data-clear-btn-id="name-clear-btn"
          class="vf-form__input | vf-search__input" />
      </div>
      <button href="#eventsFilter" class="vf-search__button | vf-button vf-button--primary">
        <span class="vf-button__text">Filter</span>
      </button>
    </div>
  </form>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--document.php', false, false)); 
          }
        } ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching documents found
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
      data-items-per-page="50" data-current-page="0" data-name="pagination1">
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
  <div></div>
</section>

<style>
  .vf-form__label {
    font-size: 16px;
  }
  .vf-form__select {
    font-size: 16px;
  }
  .vf-form__legend {
    font-size: 19px;
  }
  .vf-form__checkbox+.vf-form__label::before  {
    position: unset;
  }
</style>

<script type="text/javascript">
  jplist.init();
</script>

<?php

get_footer();

?>
