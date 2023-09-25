<?php

get_header();

global $vf_theme;
$today_date = date('Ymd');
?>

<section class="vf-intro | vf-u-margin__bottom--400">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading | vf-u-margin__bottom--400">
      Professional Skills & Career Development
    </h1>
    <p class="vf-intro__subheading">Improve your skills in communication, management and leadership; learn a language,
      or find support for planning your career.
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
      <div class="vf-stack vf-stack--400">
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
        </div>
      </main>
      <div class="vf-content">
        <h4>See also:</h4>
      <div class="vf-stack vf-stack--600">
        <article class="vf-card vf-card--brand vf-card--striped">
          <div class="vf-card__content | vf-stack vf-stack--400">
            <h3 class="vf-card__heading" style="font-size: 21px;"><a class="vf-card__link" href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses<svg style="width: auto; height: 100%;"
                  aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                  height="1em" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                    fill="currentColor" fill-rule="nonzero"></path>
                </svg>
              </a></h3>
          </div>
        </article>
        <article class="vf-card vf-card--brand vf-card--striped">
          <div class="vf-card__content | vf-stack vf-stack--400">
            <h3 class="vf-card__heading" style="font-size: 21px;"><a class="vf-card__link" href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career webinars<svg style="width: auto; height: 100%;"
                  aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                  height="1em" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                    fill="currentColor" fill-rule="nonzero"></path>
                </svg>
              </a></h3>
          </div>
        </article>
        <article class="vf-card vf-card--brand vf-card--striped">
          <div class="vf-card__content | vf-stack vf-stack--400">
            <h3 class="vf-card__heading" style="font-size: 21px;"><a class="vf-card__link" href="https://bio-it.embl.de/upcoming-courses/">Bio-IT courses<svg style="width: auto; height: 100%;"
                  aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
                  height="1em" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                    fill="currentColor" fill-rule="nonzero"></path>
                </svg>
              </a></h3>
          </div>
        </article>
        <article class="vf-card vf-card--brand vf-card--striped">
          <div class="vf-card__content | vf-stack vf-stack--400">
            <h3 class="vf-card__heading" style="font-size: 21px;"><a class="vf-card__link" href="https://www.ebi.ac.uk/training/">EMBL-EBI training<svg style="width: auto; height: 100%;"
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
      </div>
    </section>
  </section>

  <section class="vf-tabs__section" id="vf-tabs__section--on-demand-training">
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div></div>
      <div class="vf-content">
        <h3>Learning-on-the-go</h3>
        <p>Learning-on-the-go is EMBL's e-learning platform for professional development and training available to use
          at any time that suits you.
        </p>
        <p>Use it to access training opportunities:
        </p>
        <ul>
          <li>Aligned to development pathways </li>
          <li>About EMBL Policies and Guidelines </li>
          <li>On Data Protection and IT Security </li>
          <li>Via the Ethics Academy </li>
        </ul>
        <p>Much content is offered in micro-learning components which means that 5 minutes are all you need to learn
          about the key aspects of these career enhancing topics.</p>
        <p>Find out more when you log-in with your EMBL credentials.</p>
        <button class="vf-button vf-button--primary vf-button--sm "
          onclick="location.href='https://embl.clcmoodle.org';">Learning-on-the-go login</button>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  jplist.init({});

</script>

<script>
  // no results handlers
  $('#checkbox-filter-organiser, #checkbox-filter-location').change(function () {
    if ($('#no-upcoming').length) {
      $("#no-upcoming").remove();
    }
    if ($('#upcoming-events').children().length <= 0) {
      $('#upcoming-events').append(
        '<p id="no-upcoming" class="vf-text-body vf-text-body--2">No results found.</p>');
    }
  });

  $('#checkbox-filter-organiser, #checkbox-filter-location').change(function () {
    if ($('#no-past').length) {
      $("#no-past").remove();
    }
    if ($('#past-events').children().length <= 0) {
      $('#past-events').append('<p id="no-past" class="vf-text-body vf-text-body--2">No results found.</p>');
    }
  });

</script>

<?php

get_footer();

?>
