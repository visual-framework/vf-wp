<nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
     data-items-per-page="3" data-current-page="0" data-range="5" data-name="pagination1" id="paging-data">

  <ul class="vf-pagination__list" id="pagination_list">
    <li class="vf-pagination__item vf-pagination__item--previous-page" data-type="prev">
      <a class="vf-pagination__link" href="#">
        <span class="vf-pagination__label">Previous <span class="vf-u-sr-only"> page</span></span>
      </a>
    </li>
    <div data-type="pages" style="display: flex;">
      <li class="vf-pagination__item" data-type="page" data-current-page="{pageNumber}">
        <a href="#" class="vf-pagination__link">
          {pageNumber}
        </a>
      </li>
    </div>
    <li class="vf-pagination__item vf-pagination__item--next-page" data-type="next">
      <a href="#" class="vf-pagination__link">
        Next<span class="vf-u-sr-only"> page</span>
      </a>
    </li>
  </ul>
  <div class="vf-u-display-none totalrecords" data-type="info" id="totalrecords">{itemsNumber}</div>
</nav>

<script>
    // display how many results is shown
    var displayPageRange = (currentPageNumber, totalPage, perPageNumber) => {
      // Get all list items with data-selected="true" attribute
      if (typeof currentPageNumber === 'undefined') {
  // Get all list items with data-selected="true" attribute
  const selectedItems = document.querySelectorAll('li[data-selected="true"]');
  let currentPageNumber = null;
  
  selectedItems.forEach(item => {
    const currentPage = item.getAttribute('data-current-page');
    
    if (currentPage !== null) {
      currentPageNumber = currentPage;
    }
  });

  if (currentPageNumber === null) {
    console.log('No element with data-selected="true" attribute found.');
    currentPageNumber = 1; // Set a default value if needed
  }

  // Rest of your code
  var totalPage = parseInt(document.getElementById("totalrecords").textContent);
  var perPageNumber = 3;

  var numberOfPages = Math.ceil(totalPage / perPageNumber),
      start = ((currentPageNumber - 1) * perPageNumber + 1)  + ' - ',
      end = Math.min(currentPageNumber * perPageNumber, totalPage);
  
  if (totalPage <= perPageNumber) {
    start = "";
  }  

  document.querySelector('#start-counter').textContent = start;
  document.querySelector('#total-result').textContent = totalPage;
  document.querySelector('#end-counter').textContent = end;
}


  };
  displayPageRange();


function disablePaginationUpdate() {
  var li_disabled = document.querySelector('[class*="jplist-disabled"]');
  var pagination_disable_label = 'Previous';
  if (li_disabled !== null && li_disabled.hasAttribute("data-type")) {
    const li_disabled_data_type = li_disabled.getAttribute("data-type");
    if (li_disabled_data_type) {
      if (li_disabled_data_type == 'next') {
        pagination_disable_label = "Next";
      } else {
        pagination_disable_label = "Previous";
      }
    }
    li_disabled.classList.add("disabled");
    li_disabled.innerHTML = "<span class=\"vf-pagination__label\">" + pagination_disable_label + "</span>";
  }
}

/**
 * Function to update paging classes when page item is active
 */
function activePaginationUpdate() {
  var li_active = document.querySelector('[class*="jplist-selected"]');
  li_active_page_value = 0;
  if (li_active !== null && li_active.hasAttribute("data-selected")) {
    const li_active_page = li_active.getAttribute("data-selected");
    li_initial_page_value = parseInt(li_active.getAttribute("data-page"));
    // increment paging value as by default active page starts from 0
    let li_active_page_value = li_initial_page_value + 1;
    // update relevant active classes to li
    li_active.classList.add("vf-pagination__item--is-active");
    li_active.innerHTML = "<span class=\"vf-pagination__label\" aria-current=\"page\"><span class=\"vf-u-sr-only\">Page </span>" + li_active_page_value + "</span>";

    // Update previous li to active if paging increases
    if (li_active_page_value > 1) {
      var li_previous_acive = document.querySelector('[class*="vf-pagination__item--previous-page"]');
      if (li_previous_acive !== null) {
        li_previous_acive.classList.remove("disabled");
        li_previous_acive.innerHTML = "<a class=\"vf-pagination__link\" href=\"#\">Previous <span class=\"vf-u-sr-only\"> page</span></a>";
      }
    }
    // Update next li to as active if paging decreases
    var li_next_active = document.querySelector('[class*="vf-pagination__item--next-page"]');
    if (li_next_active !== null) {
      let li_next_active_page_value = parseInt(li_next_active.getAttribute("data-page"));
      if (li_initial_page_value < li_next_active_page_value) {
        li_next_active.classList.remove("disabled");
        li_next_active.innerHTML = "<a class=\"vf-pagination__link\" href=\"#\">Next <span class=\"vf-u-sr-only\"> page</span></a>";
      }
    }
  }
}

// Function to check if there is only one page and hide pagination if needed
function checkPaginationVisibility() {
  var totalRecords = parseInt(document.getElementById("totalrecords").textContent);
  if (totalRecords <= 3) {
    document.getElementById("paging-data").style.display = "none";
  } else {
    document.getElementById("paging-data").style.display = "block";
  }
}

// Call paging functions and checkPaginationVisibility on document load as it is async client-side Jplist paging
document.addEventListener('DOMContentLoaded', function() {
  disablePaginationUpdate();
  activePaginationUpdate();
  checkPaginationVisibility();
  displayPageRange();
});

// Call paging functions and checkPaginationVisibility on click of paging element as well to update logic correctly
document.getElementById("paging-data").addEventListener("click", function(){
  disablePaginationUpdate();
  activePaginationUpdate();
  checkPaginationVisibility();
  displayPageRange();
});
</script>
