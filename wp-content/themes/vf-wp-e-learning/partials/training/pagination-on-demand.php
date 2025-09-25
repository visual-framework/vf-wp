<script>
  document.addEventListener("DOMContentLoaded", function () {
    showPageOnDemand(currentPageOnDemand);
    updatePaginationLinksOnDemand();
  });
  // Add event listeners to checkboxesOnDemand
  const checkboxesOnDemand = document.querySelectorAll(".checkboxOnDemand");
  checkboxesOnDemand.forEach((checkbox) => {
    checkbox.addEventListener("click", () => {
      // Reset the current page to 1 when a 'lolo' checkbox is clicked
      currentPageOnDemand = 1;
      updatePaginationLinksOnDemand();
    });
  });

  // Add event listener for sort dropdown
const sortSelectOnDemand = document.getElementById("vf-form__select");
if (sortSelectOnDemand) {
  sortSelectOnDemand.addEventListener("change", () => {
    // Reset to first page when sorting changes
    currentPageOnDemand = 1;
    updatePaginationLinksOnDemand();
    showPageOnDemand(currentPageOnDemand);
  });
}

  const itemsPerPageOnDemand = 20;
  let currentPageOnDemand = 1;

  function showPageOnDemand(page) {
    let articles = document.querySelectorAll(".trainingOnDemand");
    articles.forEach((article, index) => {
      if (index >= (page - 1) * itemsPerPageOnDemand && index < page * itemsPerPageOnDemand) {
        article.classList.remove('vf-u-display-none'); // Remove the class to display the article
      } else {
        article.classList.add('vf-u-display-none'); // Add the class to hide the article
      }
    });

    // Count all articles on the page
    let totalArticleCountOnDemand = articles.length;

    // Count the visible articles without the 'vf-u-display-none' class
    const visibleArticles = document.querySelectorAll(".trainingOnDemand:not(.vf-u-display-none)");

    // console.log(`Total Articles: ${totalArticleCountOnDemand}`);

    // Add the condition to hide the element with id "paging-data" if totalArticleCountOnDemand is lower than itemsPerPageOnDemand
    const pagingDataElement = document.getElementById("paging-data2");
    if (totalArticleCountOnDemand < itemsPerPageOnDemand) {
      pagingDataElement.style.display = "none";
    } else {
      pagingDataElement.style.display = "block";
    }
  }

  function updatePaginationLinksOnDemand() {
  let articleTotal = document.querySelectorAll(".trainingOnDemand");
  const pageNumbers = document.querySelector(".paginationListOnDemand");

  // Calculate the total number of pages
  const totalPages = Math.ceil(articleTotal.length / itemsPerPageOnDemand);
  const maxPageLinks = 5; // Maximum number of pagination links to display

  // Calculate the start and end page numbers to display
  let startPage = 1;
  let endPage = totalPages;

  if (totalPages > maxPageLinks) {
    const offset = Math.floor(maxPageLinks / 2);
    startPage = Math.max(1, currentPageOnDemand - offset);
    endPage = Math.min(totalPages, startPage + maxPageLinks - 1);

    if (currentPageOnDemand - offset > 1) {
      startPage++;
    }

    if (currentPageOnDemand + offset < totalPages) {
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
  if (currentPageOnDemand > 1) {
    prevPageLink.textContent = "Previous";
    prevPageLink.href = "#"; // Set the href attribute as needed
    prevPageLink.classList.add("vf-pagination__link");
    prevPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      if (currentPageOnDemand > 1) {
        currentPageOnDemand--;
        showPageOnDemand(currentPageOnDemand);
        updatePaginationLinksOnDemand();
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
      currentPageOnDemand = 1;
      showPageOnDemand(currentPageOnDemand);
      updatePaginationLinksOnDemand();
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
    if (i === currentPageOnDemand) {
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
        currentPageOnDemand = i;
        showPageOnDemand(currentPageOnDemand);
        updatePaginationLinksOnDemand();
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
      currentPageOnDemand = totalPages;
      showPageOnDemand(currentPageOnDemand);
      updatePaginationLinksOnDemand();
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
  if (currentPageOnDemand < totalPages) {
    nextPageLink.textContent = "Next";
    nextPageLink.href = "#"; // Set the href attribute as needed
    nextPageLink.classList.add("vf-pagination__link");
    nextPageLink.addEventListener("click", (event) => {
      event.preventDefault();
      if (currentPageOnDemand < totalPages) {
        currentPageOnDemand++;
        showPageOnDemand(currentPageOnDemand);
        updatePaginationLinksOnDemand();
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
  var numberOfPages = Math.ceil(rangeTotalPages / itemsPerPageOnDemand),
    start = ((currentPageOnDemand - 1) * itemsPerPageOnDemand + 1) + ' - ',
    end = Math.min(currentPageOnDemand * itemsPerPageOnDemand, rangeTotalPages);

  if (rangeTotalPages <= itemsPerPageOnDemand) {
    start = "";
  }

  document.querySelector('#start-counter-od').textContent = start;
  document.querySelector('#total-result-od').textContent = rangeTotalPages;
  document.querySelector('#end-counter-od').textContent = end;
}


  // scroll to top after clicking on a pagination element
  document.addEventListener("DOMContentLoaded", function () {
    // Get the pagination element
    const pagination = document.getElementById("paging-data2");

    // Add a click event listener to the pagination element
    pagination.addEventListener("click", function (event) {
      // Prevent the default link behavior
      event.preventDefault();

      // Scroll to the element with the ID 'lolo'
      const targetElement = document.getElementById("vf-tabs__section--on-demand-training");
      targetElement.scrollIntoView({
        behavior: "smooth"
      });
    });
  });


  var inputs = document.querySelectorAll('.inputOnDemand');

  inputs.forEach(function (item) {
    item.addEventListener('keyup', function (e) {
      updatePaginationLinksOnDemand();
      showPageOnDemand(currentPageOnDemand);
    });
    item.addEventListener("change", function (e) {
      updatePaginationLinksOnDemand();
      showPageOnDemand(currentPageOnDemand);
    });
  });


</script>
