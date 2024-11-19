<script>
  document.addEventListener("DOMContentLoaded", function () {
    showPage(currentPage);
    updatePaginationLinks();
  });
  // Add event listeners to checkboxes
  const checkboxes = document.querySelectorAll(".checkboxLive");
  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("click", () => {
      // Reset the current page to 1 when a 'lolo' checkbox is clicked
      currentPage = 1;
      updatePaginationLinks();
    });
  });

  const itemsPerPage = 15;
  let currentPage = 1;

  function showPage(page) {
    let articles = document.querySelectorAll(".newsItem");
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
    const visibleArticles = document.querySelectorAll(".newsItem:not(.vf-u-display-none)");
    // console.log(`Total Articles: ${totalArticleCount}`);

    // Add the condition to hide the element with id "paging-data" if totalArticleCount is lower than itemsPerPage
    const pagingDataElement = document.getElementById("paging-data");
    if (totalArticleCount < itemsPerPage) {
      pagingDataElement.style.display = "none";
    } else {
      pagingDataElement.style.display = "block";
    }
  }

  function updatePaginationLinks() {
  let articleTotal = document.querySelectorAll(".newsItem");
  const pageNumbers = document.querySelector(".vf-pagination__list");

  // Calculate the total number of pages
  const totalPages = Math.ceil(articleTotal.length / itemsPerPage);
  const maxPageLinks = 5; // Maximum number of pagination links to display

  // Calculate the start and end page numbers to display
  let startPage = 1;
  let endPage = totalPages;

  if (totalPages > maxPageLinks) {
    const offset = Math.floor(maxPageLinks / 2);
    startPage = Math.max(1, currentPage - offset);
    endPage = Math.min(totalPages, startPage + maxPageLinks - 1);

    if (currentPage - offset > 1) {
      startPage++;
    }

    if (currentPage + offset < totalPages) {
      endPage--;
    }
  }

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

  // Add first page
  if (startPage > 1) {
    const firstPageItem = document.createElement("li");
    const firstPageLink = document.createElement("a");
    firstPageLink.textContent = "1";
    firstPageLink.href = "#"; // Set the href attribute as needed
    firstPageLink.classList.add("vf-pagination__link");
    firstPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      currentPage = 1;
      showPage(currentPage);
      updatePaginationLinks();
    });
    firstPageItem.appendChild(firstPageLink);
    firstPageItem.classList.add("vf-pagination__item");
    pageNumbers.appendChild(firstPageItem);

    if (startPage > 2) {
      // Add '...' if there are more pages before the first page
      const ellipsisItem = document.createElement("li");
      ellipsisItem.textContent = "...";
      ellipsisItem.classList.add("vf-pagination__item");
      pageNumbers.appendChild(ellipsisItem);
    }
  }

  // Create and display page numbers as list items
  for (let i = startPage; i <= endPage; i++) {
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

  // Add last page
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      // Add '...' if there are more pages after the last page
      const ellipsisItem = document.createElement("li");
      ellipsisItem.textContent = "...";
      ellipsisItem.classList.add("vf-pagination__item");
      pageNumbers.appendChild(ellipsisItem);
    }

    const lastPageItem = document.createElement("li");
    const lastPageLink = document.createElement("a");
    lastPageLink.textContent = totalPages;
    lastPageLink.href = "#"; // Set the href attribute as needed
    lastPageLink.classList.add("vf-pagination__link");
    lastPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      currentPage = totalPages;
      showPage(currentPage);
      updatePaginationLinks();
    });
    lastPageItem.appendChild(lastPageLink);
    lastPageItem.classList.add("vf-pagination__item");
    pageNumbers.appendChild(lastPageItem);
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

  // Page range display
  var rangeTotalPages = articleTotal.length;
  var numberOfPages = Math.ceil(rangeTotalPages / itemsPerPage),
    start = ((currentPage - 1) * itemsPerPage + 1) + ' - ',
    end = Math.min(currentPage * itemsPerPage, rangeTotalPages);

  if (rangeTotalPages <= itemsPerPage) {
    start = "";
  }

  document.querySelector('#start-counter').textContent = start;
  document.querySelector('#total-result').textContent = rangeTotalPages;
  document.querySelector('#end-counter').textContent = end;
}


  // scroll to top after clicking on a pagination element
  document.addEventListener("DOMContentLoaded", function () {
    // Get the pagination element
    const pagination = document.getElementById("paging-data");

    // Add a click event listener to the pagination element
    pagination.addEventListener("click", function (event) {
      // Prevent the default link behavior
      event.preventDefault();

      // Scroll to the element with the ID 'lolo'
      const targetElement = document.getElementById("total-results-info");
      targetElement.scrollIntoView({
        behavior: "smooth"
      });
    });
  });


  // sort events
  function sortEvents() {
    var eventsContainer = document.getElementById("allPosts");
    var events = eventsContainer.querySelectorAll(".newsItem"); // Select events only within "upcoming-events"
    var eventsArr = [];

    for (var i = 0; i < events.length; i++) { // Loop through the events
      eventsArr.push(events[i]);
    }

    eventsArr.sort(function (a, b) {
      var aEventTime = a.querySelector("[data-eventtime]");
      var bEventTime = b.querySelector("[data-eventtime]");

      if (aEventTime && bEventTime) {
        return +bEventTime.dataset.eventtime - aEventTime.dataset.eventtime;
      } else {
        // Handle the case where one or both elements don't have the expected data-eventtime attribute.
        return 0; // You can return 0 or another value as needed.
      }
    });

    for (var i = 0; i < eventsArr.length; ++i) {
      eventsContainer.appendChild(eventsArr[i]);
    }
  }


  var inputs = document.querySelectorAll('.inputLive');

  inputs.forEach(function (item) {
    item.addEventListener('keyup', function (e) {
      updatePaginationLinks();
      sortEvents();
      showPage(currentPage);
    });
    item.addEventListener("change", function (e) {
      updatePaginationLinks();
      sortEvents();
      showPage(currentPage);
    });
  });

  // Sort on page load
  sortEvents();

</script>
