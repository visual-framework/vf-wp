<?php

get_header();

?>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800 | vf-content">
  <div></div>
  <div>



  <form action="#eventsFilter" onsubmit="return false;"
    class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
    <div class="vf-sidebar__inner">
    <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
        </div>
        <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
          <span class="vf-button__text">Reset</span>
        </button>

    </div>
  </form>
  </div>

</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/training-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
    <?php
      $forthcomingLoop = new WP_Query ([
        'post_type' => 'training',
        'order' => 'ASC',
        'meta_key' => 'vf-wp-training-start_date',
        'orderby' => 'meta_value_num',
        'post_status' => 'publish',
        'meta_query' => [
          [
            'key' => 'vf-wp-training-start_date',
            'value' => date('Ymd', strtotime('now')),
            'compare' => '>=',
          ],
        ],
      ]);
      $temp_query = $wp_query;
      $wp_query = NULL;
      $wp_query = $forthcomingLoop;
      $current_month = "";
     // print_r($forthcomingLoop);exit;
      ?>
      <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post(); ?>
        <?php
        include(locate_template('partials/vf-summary--training.php', FALSE, FALSE)); ?>
      <?php endwhile; ?>

      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching trainings found
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
<!--/vf-grid-->


<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>

<?php

get_footer();

?>
