<?php

/**
* Template Name: Past trainings
*/


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
      Past training
    </h1>
    <!-- <p class="vf-intro__text">Browse all <b>live</b> and <b>on-demand</b> training available for EMBL staff and fellows;
      continue your professional development, improve your skills in data science or complete workplace related courses
      and activities.
    </p> -->
  </div>
</section>


    <div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 | vf-content">
      <div></div>
      <div>

        <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
                data-group="data-group-1" data-name="my-filter-1" data-path=".search-data" type="text" value=""
                placeholder="Search by title, description or keyword" data-clear-btn-id="name-clear-btn">
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
        <?php include(locate_template('partials/training-filter-past.php', false, false)); ?>
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
                      'compare' => '<='
            ),
        
          ),
          'orderby' => array(
            'date_clause' => 'DESC',
          ),


      ));
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary--training-past.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <?php include(locate_template('partials/paging-controls-training.php', false, false)); ?>
      </main>
      <div class="vf-content">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="/internal-information/training-catalogue/">Upcoming training</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <h3 class="vf-text vf-text-heading--5">Other training and development opportunities</h3>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career
            webinars</a></p>
      </div>
    </section>

<script type="text/javascript">
  jplist.init({});
</script>

<script>
let inputTimer; // Define a timer variable

function updateCheckboxAttributes() {
  // Get all the span elements with data-jplist-control="counter"
  const counterSpans = document.querySelectorAll('[data-jplist-control="counter"]');

  counterSpans.forEach((element) => {
    // Check if the inner text of the current span element is equal to '(0)'
    const isChecked = element.innerText.trim() === '(0)';

    // Find the checkbox with the class 'vf-form__checkbox' that corresponds to this span
    const checkbox = element.closest('.vf-form__item').querySelector('.vf-form__checkbox');

    if (checkbox) {
      if (isChecked) {
        checkbox.setAttribute('disabled', 'disabled');
      } else {
        checkbox.removeAttribute('disabled');
      }
    }
  });
}

// Add an event listener to run the function when the DOM content is loaded
document.addEventListener('DOMContentLoaded', updateCheckboxAttributes);

// Add an event listener for the checkbox change event
document.addEventListener('change', function(event) {
  // Check if the changed element is a checkbox with the class 'vf-form__checkbox'
  if (event.target.classList.contains('vf-form__checkbox')) {
    updateCheckboxAttributes(); // Run the function when a checkbox state changes
    checkPaginationVisibility();

  }
});

// Add an event listener for input changes in the search input form with a delay
document.querySelector('#search').addEventListener('input', function() {
  // Clear any previous timers to ensure only one timer is active
  checkPaginationVisibility();
  clearTimeout(inputTimer);

  // Set a new timer to run the function after 0.5 seconds (500 milliseconds)
  inputTimer = setTimeout(updateCheckboxAttributes,100);
});

</script>


<script type="text/javascript">
function sortEvents() {
  var eventsContainer = document.querySelectorAll("[data-jplist-group]")[0];
  var events = document.querySelectorAll("[data-jplist-item]");
  var eventsArr = [];

  for (var i in events) {
    if (events[i].nodeType == 1) {
      eventsArr.push(events[i]);
    }
  }

  eventsArr.sort(function(a, b) {
    // Compare in ascending order by reversing the order of comparison
    return +b.querySelectorAll("[data-eventtime]")[0].dataset.eventtime - +a.querySelectorAll("[data-eventtime]")[0].dataset.eventtime;
  });

  for (var i = 0; i < eventsArr.length; ++i) {
    eventsContainer.appendChild(eventsArr[i]);
  }
}

var inputs = document.querySelectorAll('input');

inputs.forEach(function(item) {
  item.addEventListener('keydown', function(e) {
    setTimeout(function(){ sortEvents() }, 300);
  });
  item.addEventListener("change", function(e) {
    sortEvents();
  });
});

// Sort on page load
sortEvents();

</script>

<?php

get_footer();

?>