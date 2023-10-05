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
      <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-category">
  <div class="vf-form__item vf-form__item--radio">
  <input type="radio" name="action" value="reset" id="reset-radio" class="vf-form__radio" checked>
  <label for="reset-radio" class="vf-form__label">Upcoming</label>
</div>
      <div class="vf-form__item vf-form__item--radio">
  <input type="radio" name="action" value="show_training" id="show-training-radio" class="vf-form__radio">
  <label for="show-training-radio" class="vf-form__label">Past</label>
</div>
  </fieldset>
  <div id="upcoming">
    <?php include(locate_template('partials/training-filter.php', false, false)); ?>
  </div>
  <div id="past" style="display: none";>
    <?php include(locate_template('partials/training-filter-past.php', false, false)); ?>
  </div>
</div>
      <main>
      <div id="loading-spinner" style="display: none;">
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
    <p></p>
  </div>
</div>

        <div id="upcoming-events">
          <div data-jplist-group="data-group-1">
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
          <?php endwhile;
          // wp_reset_postdata();
           ?>
          
          <!-- no results control -->
          <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
          </div>
        </div>
        <?php include(locate_template('partials/paging-controls-training.php', false, false)); ?>
        <?php //include(locate_template('partials/paging-controls-training-past.php', false, false)); ?>

      </main>
      <div class="vf-content">
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="/training-archive/">Past trainings</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <h3 class="vf-text vf-text-heading--5">Other training and development opportunities</h3>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/human-resources/language-courses/">Language courses</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="https://www.embl.org/internal-information/eicat/embl-fellows-career-service/events-and-workshops/#events">Career
            webinars</a></p>
                </div>
    </section>
  </section>

  <section class="vf-tabs__section" id="vf-tabs__section--on-demand-training">
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
      <div></div>
      <div class="vf-content">
        <h3>Professional development</h3>
        <h4><a href="https://embl.clcmoodle.org">Learning-on-the-go</a></h4>
        <p>Learning-on-the-go is EMBL's -learning platform for professional development and training.
          You can find training aligned to development pathways; learn about EMBL Policies and Guidelines; and complete
          your mandatory Data Protection and IT Security training. Plus, coming soon: opportunities via the Ethics
          Academy.</p>
        <hr class="vf-divider | vf-u-margin__top--800">
        <h3>Data Science</h3>
        <h4><a href="https://www.ebi.ac.uk/training/on-demand">EMBL-EBI on demand training catalogue</a> </h4>
            <p>Discover EMBL-EBI's on-demand training library, offering bioinformatics-themed online tutorials and
            curated collections, webinars, and course materials, all designed and delivered by EMBL-EBI experts and
            trainers from around the world. The training, which is available to anyone anytime, covers introductory
            bioinformatics concepts, step-by-step guides to using EMBL-EBI data resources, and details on how to submit
            your data.</p>
            <h4><a href="https://bio-it.embl.de/">EMBL Bio-IT</a> </h4> <p>Browse the EMBL Bio-IT catalogue to access
                materials from past computational biology training courses. Bio-IT organises and delivers internal and
                external training courses aimed at both novice computational biologists and more experienced users,
                taught by members of the EMBL computational biology community.</p>
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
    // checkPaginationVisibility();
  }
});

// Add an event listener for input changes in the search input form with a delay
document.querySelector('#search').addEventListener('input', function() {
  // Clear any previous timers to ensure only one timer is active
  clearTimeout(inputTimer);
  // checkPaginationVisibility();
  // Set a new timer to run the function after 0.5 seconds (500 milliseconds)
  inputTimer = setTimeout(updateCheckboxAttributes,100);
});


// Initialize an empty array to store all scraped data
// const allArticleData = [];

// function extractArticleInfo(article) {
//     const titleElement = article.querySelector('.vf-summary__title a');
//     const dateElement = article.querySelector('.vf-summary__date');
    
//     // Check if the titleElement exists
//     if (titleElement) {
//         const link = titleElement.href;
//         const title = titleElement.textContent.trim();
        
//         // Check if the dateElement exists
//         if (dateElement) {
//             const date = dateElement.textContent.trim();
//             return { title, date, link };
//         }
//     }

//     // Return null if any required element is missing
//     return null;
// }

// Function to scrape articles from the current page and add them to the global array
// function scrapeArticles() {
//     const articles = document.querySelectorAll('.vf-summary--event');

//     articles.forEach((article) => {
//         const articleInfo = extractArticleInfo(article);
//         if (articleInfo) {
//             allArticleData.push(articleInfo); // Add the data to the global array
//         }
//     });
// }

// // Function to sort the global array by date
// function sortArticleDataByDate() {
//     allArticleData.sort((a, b) => {
//         const dateA = new Date(a.date);
//         const dateB = new Date(b.date);
//         return dateA - dateB;
//     });
// }

// // Function to handle checkbox click events and sort data
// function handleCheckboxClick() {
//     const checkboxes = document.querySelectorAll('.vf-form__checkbox');

//     checkboxes.forEach((checkbox) => {
//         checkbox.addEventListener('click', () => {
//             sortArticleDataByDate();
//             console.log('Data sorted by date:', allArticleData);
//         });
//     });
// }

// // Function to navigate to the next page if it exists
// function goToNextPage() {
//     const nextPageButton = document.querySelector('.vf-pagination__item--next-page a');
//     if (nextPageButton) {
//         nextPageButton.click();
//         setTimeout(scrapeAndNavigate, 1000); // Adjust the delay as needed
//     } else {
//         console.log('No more pages to scrape.');
//         console.log('Initial data:', allArticleData);
//         handleCheckboxClick(); // Attach event listeners to checkboxes
//     }
// }

// // Function to scrape articles and navigate to the next page if available
// function scrapeAndNavigate() {
//     scrapeArticles();
//     console.log('Scraping current page...');
//     goToNextPage();
// }

// // Start scraping from the first page
// scrapeAndNavigate();


</script>

<script type="text/javascript">
// function sortEvents() {
//   var eventsContainer = document.querySelectorAll("[data-jplist-group]")[0];
//   var events = document.querySelectorAll("[data-jplist-item]");
//   var eventsArr = [];

//   for (var i in events) {
//     if (events[i].nodeType == 1) {
//       eventsArr.push(events[i]);
//     }
//   }

//   eventsArr.sort(function(a, b) {
//     // Compare in ascending order by reversing the order of comparison
//     return +a.querySelectorAll("[data-eventtime]")[0].dataset.eventtime - +b.querySelectorAll("[data-eventtime]")[0].dataset.eventtime;
//   });

//   for (var i = 0; i < eventsArr.length; ++i) {
//     eventsContainer.appendChild(eventsArr[i]);
//   }
// }

// var inputs = document.querySelectorAll('input');

// inputs.forEach(function(item) {
//   item.addEventListener('keydown', function(e) {
//     setTimeout(function(){ sortEvents() }, 300);
//   });
//   item.addEventListener("change", function(e) {
//     sortEvents();
//   });
// });

// // Sort on page load
// sortEvents();

</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const showTrainingRadio = document.getElementById('show-training-radio');
    const resetRadio = document.getElementById('reset-radio');
    const trainingPostsContainer = document.getElementById('upcoming-events');
    const loadingSpinner = document.getElementById('loading-spinner');

    let defaultContent = ''; // Store the default content

    // Initialize jplist
    function initializeJplist() {
      jplist.init();
      updateCheckboxAttributes();
      // Add your jplist initialization code here
      // For example:
      // $('#training-posts-container').jplist({ options });
    }

    // Function to show the spinner
    function showSpinner() {
      loadingSpinner.style.display = 'block';
    }

    // Function to hide the spinner
    function hideSpinner() {
      loadingSpinner.style.display = 'none';
    }

    // Function to fetch and display training posts
    function showTrainingPosts() {
      // Show the spinner while loading
      showSpinner();

      // Send an AJAX request to fetch training posts
      const xhr = new XMLHttpRequest();
      xhr.open('GET', '<?php echo admin_url('admin-ajax.php'); ?>?action=get_training_posts', true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Hide the spinner when the request is completed
          hideSpinner();

          // Display the fetched posts in the container
          trainingPostsContainer.innerHTML = xhr.responseText;

          // Refresh jplist after updating content
          initializeJplist();
        }
      };
      xhr.send();
    }

    // Click event handler for radio buttons
    showTrainingRadio.addEventListener('change', function () {
      if (showTrainingRadio.checked) {
        showTrainingPosts();
      }
    });

    resetRadio.addEventListener('change', function () {
      if (resetRadio.checked) {
        // Restore default content
        trainingPostsContainer.innerHTML = defaultContent;

        // Refresh jplist after resetting
        initializeJplist();
      }
    });

    // Save the default content when the page loads
    defaultContent = trainingPostsContainer.innerHTML;

    // Initialize jplist on page load
    initializeJplist();
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get references to the radio buttons and divs
    var radioReset = document.getElementById("reset-radio");
    var showTraining = document.getElementById("show-training-radio");
    var upcomingDiv = document.getElementById("upcoming");
    var pastDiv = document.getElementById("past");

    // Add event listeners to the radio buttons
    radioReset.addEventListener("click", function() {
        // Show the "upcoming" div and hide the "past" div
        upcomingDiv.style.display = "block";
        pastDiv.style.display = "none";
    });

    showTraining.addEventListener("click", function() {
        // Show the "past" div and hide the "upcoming" div
        pastDiv.style.display = "block";
        upcomingDiv.style.display = "none";
    });
});
</script>

<?php

get_footer();

?>