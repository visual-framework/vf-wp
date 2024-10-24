<script>
  document.addEventListener("DOMContentLoaded", function () {
    showPage(currentPage);
    updatePaginationLinks();
  });
// Get the select element
const selectElement = document.getElementById("vf-form__select");

// Add event listener to the select element
selectElement.addEventListener("change", () => {
  // Reset the current page to 1 when a new option is selected
  currentPage = 1;
  showPage(currentPage);
  updatePaginationLinks();
  });

  const itemsPerPage = 10;
  let currentPage = 1;

  function showPage(page) {
    let articles = document.querySelectorAll(".vf-summary--publication");
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
    const visibleArticles = document.querySelectorAll(".vf-summary--publication:not(.vf-u-display-none)");

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
    console.log('run');
    let articleTotal = document.querySelectorAll(".vf-summary--publication");

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
      const targetElement = document.getElementById("search");
      targetElement.scrollIntoView({
        behavior: "smooth"
      });
    });
  });




  var inputs = document.querySelectorAll('.vf-form__input--filter');

  inputs.forEach(function (item) {
    item.addEventListener('keyup', function (e) {
      updatePaginationLinks();
      showPage(currentPage);
    });
    item.addEventListener("change", function (e) {
      updatePaginationLinks();
      showPage(currentPage);
    });
  });

  // Sort on page load

</script>
