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
          <a class="vf-tabs__link" href="#vf-tabs__section--live-training">Live Training</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--on-demand-training">On-demand Training</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="vf-tabs-content" id="typeTraining" data-vf-js-tabs-content>
  <section class="vf-tabs__section" id="vf-tabs__section--live-training">
    <div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 | vf-content">
      <div></div>
      <div>

        <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
                data-group="data-group-1" data-name="my-filter-1" data-path=".search-data" type="text" value=""
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

    <section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div>
        <?php include(locate_template('partials/training-filter.php', false, false)); ?>
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
                      'compare' => '>='
            ),
        
          ),
          'orderby' => array(
            'date_clause' => 'ASC',
          ),


      ));
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary--training.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>

        </div>
      </main>
      <div class="vf-content">
        <h4>See also:</h4>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career
            webinars</a></p>
        <!-- <hr class="vf-divider | vf-u-margin__bottom--400">      
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a href="https://catalogue.bio-it.embl.de/courses/catalogue/">EMBL Bio-IT training courses</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">      
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a href=https://www.ebi.ac.uk/training/">EMBL-EBI training</a></p> -->
      </div>
    </section>
  </section>

  <section class="vf-tabs__section" id="vf-tabs__section--on-demand-training">
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div></div>
      <div class="vf-content">
        <h3>Get professional and scientific training at your own pace</h3>
        <p>Take the step towards your personal and professional goals with on-demand training.
        </p>
        <div class="vf-grid vf-grid__col-2">
          <article class="vf-card vf-card--brand vf-card--bordered">


            <div class="vf-card__content | vf-stack vf-stack--400">
              <h3 class="vf-card__heading" style="font-size: 23px;"><a class="vf-card__link"
                  href="https://embl.clcmoodle.org">Professional development <svg style="width: auto; height: 100%;"
                    aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                    height="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                      fill="currentColor" fill-rule="nonzero"></path>
                  </svg>
                </a></h3>
              <p class="vf-card__subheading">Learning on-the-go</p>
              <p class="vf-text-body vf-text-body--3">Use it to access training opportunities:

                <ul>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Aligned to development pathways
                  </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">About EMBL Policies and Guidelines
                  </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">On Data Protection and IT Security
                  </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Via the Ethics Academy </li>
                </ul>
              </p>
            </div>
          </article>
          <article class="vf-card vf-card--brand vf-card--bordered">


            <div class="vf-card__content | vf-stack vf-stack--400">
              <h3 class="vf-card__heading" style="font-size: 23px;"><a class="vf-card__link"
                  href="https://www.ebi.ac.uk/training/on-demand">Data science <svg style="width: auto; height: 100%;"
                    aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                    height="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                      fill="currentColor" fill-rule="nonzero"></path>
                  </svg>
                </a></h3>
              <p class="vf-card__subheading">With sub–heading</p>
              <p class="vf-text-body vf-text-body--3">Use it to access training opportunities:

                <ul>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Text </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Text </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Text </li>
                  <li class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--200">Text </li>
                </ul>
              </p>
            </div>
          </article>
        </div>

      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  jplist.init({});

</script>

<script>
  // no results handlers
  // $('#checkbox-filter-organiser, #checkbox-filter-location').change(function () {
  //   if ($('#no-upcoming').length) {
  //     $("#no-upcoming").remove();
  //   }
  //   if ($('#upcoming-events').children().length <= 0) {
  //     $('#upcoming-events').append(
  //       '<p id="no-upcoming" class="vf-text-body vf-text-body--2">No results found.</p>');
  //   }
  // });

</script>

<?php

get_footer();

?>
