<?php

/**
* Template Name: Past events
*/


get_header();

$current_date = date('Ymd');
global $vf_theme;
$title = $vf_theme->get_title();
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Past internal events
    </h1>
  </div>
</section>


<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
      <input id="search" class="vf-form__input vf-form__input--filter inputLive" data-jplist-control="textbox-filter"
        data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
        placeholder="Filter by title" data-clear-btn-id="name-clear-btn">
        <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
              id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
                id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
                class="counter-highlight"></span></p>
   </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/events-filter-past.php', false, false)); ?>
  </div>
  <div class="vf-content">
        <div id="allPosts" data-jplist-group="data-group-1">
          <?php
          $forthcomingLoop = new WP_Query (array( 
          'posts_per_page' => -1,
          'post_type' => 'events',
          'order' => 'DESC', 
          'orderby' => 'meta_value_num',
          'meta_key' => 'vf_event_internal_start_date',
          'meta_query' => array(
            array(
                'key' => 'vf_event_internal_start_date',
                'value' => $current_date,
                'compare' => '<',
                'type' => 'numeric'
            ),
            array(
              'key' => 'vf_event_internal_start_date',
              'value' => date('Ymd', strtotime('now')),
              'type' => 'numeric',
              'compare' => '<',
              ) 
            ) ));
          $temp_query = $wp_query;
          $wp_query   = NULL;
          $wp_query   = $forthcomingLoop;
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary-events-past.php', false, false)); ?>
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
  </div>
  <div>
        <div class="vf-u-background-color-ui--off-white">
          <div class="vf-content vf-u-padding--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/events">Upcoming events</a></p>
            <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/events/">Courses and Conferences</a></p>

        <p class="vf-text-body vf-text-body--3"><a
        href="https://www.embl.org/internal-information/seminars/">Scientific Events and Seminars</a></p>

        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0"><a
        href="https://www.ebi.ac.uk/about/events/internal-events/">EMBL-EBI Events</a></p>
      </div>
    </article>

  </div>
  </div>
</section>

<script type="text/javascript">
    jplist.init({
      deepLinking: true
    });
</script>
<?php  include(locate_template('partials/pagination/pagination.php', false, false)); ?>

<?php

get_footer();

?>
