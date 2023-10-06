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
      <div class="vf-content">

        <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
                data-group="data-group-1" data-name="my-filter-1" data-path=".search-data" type="text" value=""
                placeholder="Search within live training" data-clear-btn-id="name-clear-btn">
            </div>
            <button style="display: none;" type="button" id="name-clear-btn"
              class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
              <span class="vf-button__text">Reset</span>
            </button>
          </div>
        </form>
        <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600" id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span id="end-counter" class="counter-highlight"></span> results out of <span id="total-result" class="counter-highlight"></span></p>
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
          <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1"
            data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data" class="vf-pagination" aria-label="Pagination">
  <ul class="vf-pagination__list"></ul>
  </nav>
   <?php // include(locate_template('partials/paging-controls-training.php', false, false)); ?>

      </main>
      <div class="vf-content">
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="/internal-information/training-archive/">Past training</a></p>
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
document.addEventListener("DOMContentLoaded", function () {
    showPage(currentPage);
    updatePaginationLinks();
  });
  // Add event listeners to checkboxes with class 'lolo'
  const checkboxes = document.querySelectorAll(".vf-form__checkbox");
  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("click", () => {
    // Reset the current page to 1 when a 'lolo' checkbox is clicked
    currentPage = 1;
    updatePaginationLinks();    });
  });

  const itemsPerPage = 3;
  let currentPage = 1;
  
  function showPage(page) {
  let articles = document.querySelectorAll(".vf-summary--event");
  articles.forEach((article, index) => {
    if (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) {
      article.classList.remove('vf-u-display-none'); // Remove the class to display the article
    } else {
      article.classList.add('vf-u-display-none'); // Add the class to hide the article
    }
  });

  // Count all articles on the page
  let totalArticleCount = articles.length;

  // Count the visible articles without the 'vf-u-display-none' class
  const visibleArticles = document.querySelectorAll(".vf-summary--event:not(.vf-u-display-none)");

  console.log(`Total Articles: ${totalArticleCount}`);


  // Add the condition to hide the element with id "paging-data" if totalArticleCount is lower than itemsPerPage
  const pagingDataElement = document.getElementById("paging-data");
  if (totalArticleCount < itemsPerPage) {
    pagingDataElement.style.display = "none";
  } else {
    pagingDataElement.style.display = "block";
  }
}

// Call showPage to display the initial page
function updatePaginationLinks() {
  let articleTotal = document.querySelectorAll(".vf-summary--event");

  const pageNumbers = document.querySelector(".vf-pagination__list");

  // Calculate the total number of pages
  const totalPages = Math.ceil(articleTotal.length / itemsPerPage);
  // Clear existing pagination links
  pageNumbers.innerHTML = "";

  // Add "Previous" link
  const prevPageItem = document.createElement("li");
  prevPageItem.classList.add("vf-pagination__item");
  prevPageItem.classList.add("vf-pagination__item--previous-page");
  const prevPageLink = document.createElement("a");
  if (currentPage > 1) {
    prevPageLink.textContent = "Previous";
    prevPageLink.href = "#"; // Set the href attribute as needed
    prevPageLink.classList.add("vf-pagination__link");
    prevPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
        updatePaginationLinks();
      }
    });
  } else {
    prevPageLink.textContent = "Previous";
    prevPageItem.classList.add("disabled");
  }
  prevPageItem.appendChild(prevPageLink);
  pageNumbers.appendChild(prevPageItem);

  // Create and display page numbers as list items
  for (let i = 1; i <= totalPages; i++) {
    const pageNumberItem = document.createElement("li");
    const pageNumberLink = document.createElement("a");
    if (i === currentPage) {
      const pageNumberSpan = document.createElement("span");
      pageNumberSpan.classList.add("vf-pagination__label");
      pageNumberItem.classList.add("vf-pagination__item--is-active");
      pageNumberSpan.setAttribute("aria-current", "page");
      pageNumberSpan.textContent = i;
      pageNumberItem.appendChild(pageNumberSpan);
    } else {
      pageNumberLink.textContent = i;
      pageNumberLink.href = "#"; // Set the href attribute as needed
      pageNumberLink.classList.add("vf-pagination__link");
      pageNumberLink.addEventListener("click", (event) => {
        event.preventDefault();
        currentPage = i;
        showPage(currentPage);
        updatePaginationLinks();
      });
      pageNumberItem.appendChild(pageNumberLink);
    }
    pageNumberItem.classList.add("vf-pagination__item");
    pageNumbers.appendChild(pageNumberItem);
  }

  // Add "Next" link
  const nextPageItem = document.createElement("li");
  nextPageItem.classList.add("vf-pagination__item");
  nextPageItem.classList.add("vf-pagination__item--next-page");
  const nextPageLink = document.createElement("a");
  if (currentPage < totalPages) {
    nextPageLink.textContent = "Next";
    nextPageLink.href = "#"; // Set the href attribute as needed
    nextPageLink.classList.add("vf-pagination__link");
    nextPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      const totalPages = Math.ceil(articleTotal.length / itemsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
        updatePaginationLinks();
      }
    });
  } else {
    nextPageLink.textContent = "Next";
    nextPageItem.classList.add("disabled");
  }
  nextPageItem.appendChild(nextPageLink);
  pageNumbers.appendChild(nextPageItem);

  var rangeTotalPages = articleTotal.length;


  var numberOfPages = Math.ceil(rangeTotalPages / itemsPerPage),
      start = ((currentPage - 1) * itemsPerPage + 1)  + ' - ',
      end = Math.min(currentPage * itemsPerPage, rangeTotalPages);
  
  if (rangeTotalPages <= itemsPerPage) {
    start = "";
  }  

  document.querySelector('#start-counter').textContent = start;
  document.querySelector('#total-result').textContent = rangeTotalPages;
  document.querySelector('#end-counter').textContent = end;
}

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
    return +a.querySelectorAll("[data-eventtime]")[0].dataset.eventtime - +b.querySelectorAll("[data-eventtime]")[0].dataset.eventtime;
  });

  for (var i = 0; i < eventsArr.length; ++i) {
    eventsContainer.appendChild(eventsArr[i]);
  }
}

var inputs = document.querySelectorAll('input');

inputs.forEach(function(item) {
  item.addEventListener('keyup', function(e) {
    updatePaginationLinks();
    sortEvents();
    showPage(currentPage);
  });
  item.addEventListener("change", function(e) {
    updatePaginationLinks();
    sortEvents();
    showPage(currentPage);
  });
});

// Sort on page load
sortEvents();

</script>

<script>

</script>

<?php

get_footer();

?>