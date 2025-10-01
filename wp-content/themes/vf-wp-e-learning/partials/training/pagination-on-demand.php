<script>
document.addEventListener("DOMContentLoaded", function () {

    const itemsPerPageOnDemand = 20;
    let currentPageOnDemand = 1;

    // Unified function to run on any change
    function runUpdates() {
        sortEvents();
        updatePaginationLinksOnDemand();
        showPageOnDemand(currentPageOnDemand);
    }

    // Initial display
    showPageOnDemand(currentPageOnDemand);
    updatePaginationLinksOnDemand();

    // --------------------------
    // Event listeners for checkboxes
    // --------------------------
    const checkboxesOnDemand = document.querySelectorAll(".checkboxOnDemand");
    checkboxesOnDemand.forEach((checkbox) => {
        checkbox.addEventListener("click", () => {
            currentPageOnDemand = 1;
            runUpdates();
        });
    });

    // --------------------------
    // Event listener for sort dropdown
    // --------------------------
    const sortSelectOnDemand = document.getElementById("vf-form__select");
    if (sortSelectOnDemand) {
        sortSelectOnDemand.addEventListener("change", () => {
            currentPageOnDemand = 1;
            runUpdates();
        });
    }

// --------------------------
// Event listeners for input fields (all types)
// --------------------------
const inputs = document.querySelectorAll('.inputOnDemand');

inputs.forEach(function(item) {
    const events = [
        'input',        // typing, pasting, autofill, drag-drop
        'keyup',        // key release
        'keydown',      // key press
        'keypress',     // key press (older, sometimes used)
        'change',       // blur after change
        'mousedown',    // mouse click
        'mouseup',      // mouse release
        'focus',        // field gains focus
        'blur',         // field loses focus
        'paste',        // content pasted
        'cut',          // content cut
        'drop',         // text dropped
        'compositionend'// for IME / complex input
    ];

    events.forEach(function(evt) {
        item.addEventListener(evt, runUpdates);
    });
});










    // --------------------------
    // Pagination scroll to top
    // --------------------------
    const pagination = document.getElementById("paging-data2");
    if (pagination) {
        pagination.addEventListener("click", function (event) {
            event.preventDefault();
            const targetElement = document.getElementById("vf-tabs__section--on-demand-training");
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: "smooth" });
            }
        });
    }

    // --------------------------
    // Show page function
    // --------------------------
    function showPageOnDemand(page) {
        let articles = document.querySelectorAll(".trainingOnDemand:not(.vf-u-display-none)");
        articles.forEach((article, index) => {
            if (index >= (page - 1) * itemsPerPageOnDemand && index < page * itemsPerPageOnDemand) {
                article.classList.remove('vf-u-display-none-page');
            } else {
                article.classList.add('vf-u-display-none-page');
            }
        });

        let totalArticleCountOnDemand = articles.length;
        const pagingDataElement = document.getElementById("paging-data2");
        if (pagingDataElement) {
            pagingDataElement.style.display = totalArticleCountOnDemand <= itemsPerPageOnDemand ? "none" : "block";
        }
    }

    // --------------------------
    // Update pagination links
    // --------------------------
    function updatePaginationLinksOnDemand() {
        let articleTotal = document.querySelectorAll(".trainingOnDemand:not(.vf-u-display-none)");
        const pageNumbers = document.querySelector(".paginationListOnDemand");
        if (!pageNumbers) return;

        const totalPages = Math.ceil(articleTotal.length / itemsPerPageOnDemand);
        const maxPageLinks = 5;
        let startPage = 1;
        let endPage = totalPages;

        if (totalPages > maxPageLinks) {
            const offset = Math.floor(maxPageLinks / 2);
            startPage = Math.max(1, currentPageOnDemand - offset);
            endPage = Math.min(totalPages, startPage + maxPageLinks - 1);
            if (currentPageOnDemand - offset > 1) startPage++;
            if (currentPageOnDemand + offset < totalPages) endPage--;
        }

        pageNumbers.innerHTML = "";

        // Previous
        const prevPageItem = document.createElement("li");
        prevPageItem.classList.add("vf-pagination__item", "vf-pagination__item--previous-page");
        const prevPageLink = document.createElement("a");
        prevPageLink.classList.add("vf-pagination__link");
        prevPageLink.textContent = "Previous";
        if (currentPageOnDemand > 1) {
            prevPageLink.href = "#";
            prevPageLink.addEventListener("click", (e) => {
                e.preventDefault();
                currentPageOnDemand--;
                showPageOnDemand(currentPageOnDemand);
                updatePaginationLinksOnDemand();
            });
        } else prevPageItem.classList.add("disabled");
        prevPageItem.appendChild(prevPageLink);
        pageNumbers.appendChild(prevPageItem);

        // First page + ellipsis
        if (startPage > 1) {
            const firstPageItem = document.createElement("li");
            firstPageItem.classList.add("vf-pagination__item");
            const firstPageLink = document.createElement("a");
            firstPageLink.classList.add("vf-pagination__link");
            firstPageLink.href = "#";
            firstPageLink.textContent = "1";
            firstPageLink.addEventListener("click", (e) => {
                e.preventDefault();
                currentPageOnDemand = 1;
                showPageOnDemand(currentPageOnDemand);
                updatePaginationLinksOnDemand();
            });
            firstPageItem.appendChild(firstPageLink);
            pageNumbers.appendChild(firstPageItem);
            if (startPage > 2) {
                const ellipsis = document.createElement("li");
                ellipsis.classList.add("vf-pagination__item");
                ellipsis.textContent = "...";
                pageNumbers.appendChild(ellipsis);
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const pageNumberItem = document.createElement("li");
            pageNumberItem.classList.add("vf-pagination__item");
            if (i === currentPageOnDemand) {
                const span = document.createElement("span");
                span.classList.add("vf-pagination__label");
                span.setAttribute("aria-current", "page");
                span.textContent = i;
                pageNumberItem.classList.add("vf-pagination__item--is-active");
                pageNumberItem.appendChild(span);
            } else {
                const link = document.createElement("a");
                link.classList.add("vf-pagination__link");
                link.href = "#";
                link.textContent = i;
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    currentPageOnDemand = i;
                    showPageOnDemand(currentPageOnDemand);
                    updatePaginationLinksOnDemand();
                });
                pageNumberItem.appendChild(link);
            }
            pageNumbers.appendChild(pageNumberItem);
        }

        // Last page + ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement("li");
                ellipsis.classList.add("vf-pagination__item");
                ellipsis.textContent = "...";
                pageNumbers.appendChild(ellipsis);
            }
            const lastPageItem = document.createElement("li");
            lastPageItem.classList.add("vf-pagination__item");
            const lastPageLink = document.createElement("a");
            lastPageLink.classList.add("vf-pagination__link");
            lastPageLink.href = "#";
            lastPageLink.textContent = totalPages;
            lastPageLink.addEventListener("click", (e) => {
                e.preventDefault();
                currentPageOnDemand = totalPages;
                showPageOnDemand(currentPageOnDemand);
                updatePaginationLinksOnDemand();
            });
            lastPageItem.appendChild(lastPageLink);
            pageNumbers.appendChild(lastPageItem);
        }

        // Page range counters
        let rangeTotalPages = articleTotal.length;
        let start = ((currentPageOnDemand - 1) * itemsPerPageOnDemand + 1) + ' - ';
        let end = Math.min(currentPageOnDemand * itemsPerPageOnDemand, rangeTotalPages);
        if (rangeTotalPages <= itemsPerPageOnDemand) start = "";

        document.querySelector('#start-counter-od').textContent = start;
        document.querySelector('#total-result-od').textContent = rangeTotalPages;
        document.querySelector('#end-counter-od').textContent = end;
    }

    // --------------------------
    // Sorting function
    // --------------------------
    function sortEvents() {
      console.log("sorted");
        var eventsContainer = document.getElementById("on-demand-events");
        if (!eventsContainer) return;
        var events = eventsContainer.querySelectorAll(".trainingOnDemand:not(.vf-u-display-none)"); 
        var eventsArr = Array.from(events);

        var sortSelect = document.querySelector('[data-jplist-control="select-sort"][data-group="data-group-2"]');
        if (!sortSelect) return;

        var selectedOption = sortSelect.options[sortSelect.selectedIndex];
        var dataPath = selectedOption.getAttribute("data-path");
        var order = selectedOption.getAttribute("data-order");
        var type = selectedOption.getAttribute("data-type");

        eventsArr.sort(function (a, b) {
            var aVal, bVal;

            if (type === "number") {
                aVal = parseFloat(a.querySelector(dataPath)?.textContent.trim() || 0);
                bVal = parseFloat(b.querySelector(dataPath)?.textContent.trim() || 0);
            } else if (type === "datetime") {
                aVal = new Date(a.querySelector(dataPath)?.textContent.trim() || 0);
                bVal = new Date(b.querySelector(dataPath)?.textContent.trim() || 0);
            } else {
                aVal = a.querySelector(dataPath)?.textContent.trim().toLowerCase() || "";
                bVal = b.querySelector(dataPath)?.textContent.trim().toLowerCase() || "";
            }

            let result = order === "asc" ? (aVal > bVal ? 1 : aVal < bVal ? -1 : 0)
                                         : (aVal < bVal ? 1 : aVal > bVal ? -1 : 0);

            if (result === 0) {
                const aTitle = a.querySelector(".post-title")?.textContent.trim().toLowerCase() || "";
                const bTitle = b.querySelector(".post-title")?.textContent.trim().toLowerCase() || "";
                result = aTitle.localeCompare(bTitle);
            }

            return result;
        });

        eventsArr.forEach(event => eventsContainer.appendChild(event));
    }

});
</script>
