<?php

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
      Internal events
    </h1>
  </div>
</section>


<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
      <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
        data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
        placeholder="Filter by title" data-clear-btn-id="name-clear-btn">
   </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/events-filter.php', false, false)); ?>
  </div>
  <div class="vf-content">
    <div class="vf-tabs">
      <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link vf-u-padding__top--0" href="#vf-tabs__section--1">Upcoming</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link vf-u-padding__top--0" href="#vf-tabs__section--2">Past</a>
        </li>
      </ul>
    </div>
    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section" id="vf-tabs__section--1">
        <div data-jplist-group="data-group-1">
          <?php
          $forthcomingLoop = new WP_Query (array( 
          'posts_per_page' => -1,
          'post_type' => 'events',
          'order' => 'ASC', 
          'orderby' => 'meta_value_num',
          'meta_key' => 'vf_event_internal_start_date',
          'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'vf_event_internal_start_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'numeric'
            ),
            array(
                'key' => 'vf_event_internal_end_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'numeric'
            ),
            array(
              'key' => 'vf_event_internal_start_date',
              'value' => date('Ymd', strtotime('now')),
              'type' => 'numeric',
              'compare' => '>=',
              ) 
            ) ));
          $temp_query = $wp_query;
          $wp_query   = NULL;
          $wp_query   = $forthcomingLoop;
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary-events.php', false, false)); ?>
          <?php endwhile;?>
        </div>
      </section>

      <section class="vf-tabs__section" id="vf-tabs__section--2">
        <div data-jplist-group="data-group-1">
          <?php
          $pastLoop = new WP_Query (array( 
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
          $wp_query   = $pastLoop;
          $current_month = ""; ?>
          <?php while ($pastLoop->have_posts()) : $pastLoop->the_post();?>
          <?php
          include(locate_template('partials/vf-summary-events-past.php', false, false)); ?>
          <?php endwhile;?>
        </div>
        <?php include(locate_template('partials/paging-controls.php', false, false)); ?>

      </section>
    </div>

  </div>
  <div class="vf-stack vf-stack--400">
    <article class="vf-card vf-card--brand vf-card--bordered">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.embl.org/events/">Courses and Conferences<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a>
        </h3>
      </div>
    </article>
    <article class="vf-card vf-card--brand vf-card--bordered">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.embl.org/internal-information/seminars/">Scientific Events and Seminars<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a>
        </h3>
      </div>
    </article>
    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.ebi.ac.uk/about/events/internal-events/">EMBL-EBI Events<svg
              aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
              height="1em" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
      </div>
    </article>

  </div>
</section>

<script type="text/javascript">
    jplist.init({
      deepLinking: true
    });
</script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {

    // this is a sort of callback on jplist to enforce showing newest events first.
    // Unfortunately, the jplist sorting does not combine reliably will multiple facets. 
    // This is an interim solution until we replace jplist.
    function sortEvents() {
      var eventsContainer = document.querySelectorAll("[data-jplist-group]")[0];
      var events = document.querySelectorAll("[data-jplist-item]");
      var eventsArr = [];

      // eventsContainer.innerHTML = "Rendering";

      for (var i in events) {
        if (events[i].nodeType == 1) { // get rid of the whitespace text nodes
          eventsArr.push(events[i]);
        }
      }

      eventsArr.sort(function(a, b) {
        // console.log(a.querySelectorAll("[data-eventtime]")[0]);
        return +b.querySelectorAll("[data-eventtime]")[0].dataset.eventtime - +a.querySelectorAll("[data-eventtime]")[0].dataset.eventtime;
      });

      // console.log('eventsArr',eventsArr)

      for (i = 0; i < eventsArr.length; ++i) {
        eventsContainer.appendChild(eventsArr[i]);
      }
    }

    var inputs = document.querySelectorAll('input');
    // brute force to refresh jplist to ensure date filtering is intact
    inputs.forEach(function(item) {
      item.addEventListener('keydown', function(e) {
        setTimeout(function(){ sortEvents() }, 300);
      });
      item.addEventListener("change", function(e) {
        // jplist.refresh();
        sortEvents();
        // setTimeout(function(){ sortEvents() }, 300);
      });
    });

    // sort on page load
    sortEvents();
    // setTimeout(function(){ sortEvents() }, 300);
  });
</script>

<?php

get_footer();

?>
