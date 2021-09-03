<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
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
          placeholder="Filter by article title" data-clear-btn-id="name-clear-btn"
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
    <?php include(locate_template('partials/insites-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-insites-latest.php', false, false)); 
          }
        }  ?>
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
      data-items-per-page="10" data-current-page="0" data-name="pagination1">
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

<section
  class="vf-news-container vf-news-container--featured | vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
  <h2 class="vf-section-header__heading vf-u-margin__bottom--400">Featured stories</h2>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
  <?php
			$featured = new WP_Query (array(
        'posts_per_page' => 4, 
        'post_type' => 'insites', 
        'meta_key' => 'featured',
        'meta_value' => '1'  ));
      $ids = array();
    while ($featured->have_posts()) : $featured->the_post();
      $ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary-insites.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>  </div>
</section>

<script type="text/javascript">
  jplist.init();
</script>

<?php

get_footer();

?>