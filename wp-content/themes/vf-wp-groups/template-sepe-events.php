<?php
/**
* Template Name: Public Engagement Events
*/

get_header();

global $vf_theme;
$current_date = date('Ymd');
?>

<div class="embl-grid embl-grid--has-centered-content | vf-content">
  <div></div>
  <div>
    <div class="vf-u-margin__bottom--400">
      <h2 class="vf-text vf-text-heading--1">
        <?php the_title(); ?>
      </h2>
      <h3>EMBL organises and takes part in public engagement and outreach events across Europe.</h3>
    </div>

    <div class="vf-tabs">
      <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--upcoming-events">Upcoming</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--past-events">Past</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="vf-tabs-content" data-vf-js-tabs-content>
  <section class="vf-tabs__section" id="vf-tabs__section--upcoming-events">
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div class="vf-stack vf-stack--400">
        <?php include locate_template('partials/sepe-filter.php', false, false); ?>
      </div>
      <main>
        <form class="vf-form vf-form--search vf-form--search--responsive vf-sidebar vf-sidebar--end vf-u-margin__bottom--800"
          action="#eventsFilter" onsubmit="return false;">
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

        <div id="upcoming-events" data-jplist-group="data-group-1">
          <?php
          $forthcomingLoop = new WP_Query([
            'posts_per_page' => 100,
            'post_type' => 'vf_event',
            'order' => 'ASC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'vf_event_start_date',
            'meta_query' => [
              'relation' => 'OR',
              [
                'key' => 'vf_event_start_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'numeric'
              ],
              [
                'key' => 'vf_event_end_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'numeric'
              ],
              [
                'key' => 'vf_event_start_date',
                'value' => date('Ymd', strtotime('now')),
                'type' => 'numeric',
                'compare' => '>='
              ]
            ]
          ]);

          while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();
            include locate_template('partials/vf-summary--pb-event.php', false, false);
          endwhile;
          wp_reset_postdata();
          ?>
           <!-- no results control -->
           <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
            <p class="vf-summary__text">
              No upcoming events found
            </p>
          </article>

        </div>
      </main>
    </div>
  </section>

  <section class="vf-tabs__section" id="vf-tabs__section--past-events">
    <div id="past-events">
      <?php $vf_theme->the_content(); ?>
    </div>
  </section>
</div>

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector('#upcoming-events').children.length <= 0) {
      document.querySelector('#upcoming-events').innerHTML = '<p id="no-upcoming" class="vf-text-body vf-text-body--2">No upcoming events found</p>';
    }


  });
</script>
<script type="text/javascript">
    jplist.init({
    });
  </script>

<style>
  #wp-block-1 .vf-card-container {
    padding-top: 0;
  }
</style>

<?php get_footer(); ?>
