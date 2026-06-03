(function() {
  const normalize = value => String(value || "").toLowerCase().trim();
  const clean = value => String(value || "").trim();

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function readParamValues(params, key, aliases) {
    const paramKeys = [key, ...(aliases || [])];
    const values = paramKeys.flatMap(paramKey => [
      ...params.getAll(paramKey),
      ...params.getAll(`${paramKey}[]`)
    ]);

    if (key === "year") {
      values.push(...params.getAll("m"));
    }

    return values
      .flatMap(value => String(value || "").split(","))
      .map(clean)
      .filter(Boolean);
  }

  function initArchiveBrowser(root) {
    const configEl = root.querySelector(".ells-archive-browser-config");
    if (!configEl) return;

    const config = JSON.parse(configEl.textContent || "{}");
    const filters = Array.isArray(config.filters) ? config.filters : [];
    const enableSearch = config.enableSearch !== false;
    const itemOptions = Object.fromEntries(filters.map(filter => [filter.key, filter.options || []]));
    const itemsPerPage = Number(config.itemsPerPage) || 10;

    const dom = {
      form: root.querySelector(".ells-filter-form"),
      results: root.querySelector(".ells-results-container"),
      pagination: root.querySelector(".ells-pagination-list"),
      info: root.querySelector(".ells-results-info"),
      clearAll: root.querySelector(".ells-clear-all-button"),
      search: enableSearch ? root.querySelector(".ells-filter-search") : null
    };

    const state = {
      filters: Object.fromEntries(filters.map(filter => [filter.key, []])),
      search: "",
      currentPage: 1,
      filtered: []
    };

    function syncUrl() {
      const url = new URL(window.location.href);
      filters.forEach(filter => {
        url.searchParams.delete(filter.key);
        url.searchParams.delete(`${filter.key}[]`);
        (filter.aliases || []).forEach(alias => {
          url.searchParams.delete(alias);
          url.searchParams.delete(`${alias}[]`);
        });
      });
      url.searchParams.delete("m");
      url.searchParams.delete("search");
      url.searchParams.delete("results-page");

      filters.forEach(filter => {
        const values = state.filters[filter.key] || [];
        if (values.length) {
          url.searchParams.set(filter.key, values.join(","));
        }
      });

      if (state.currentPage > 1) {
        url.searchParams.set("results-page", String(state.currentPage));
      }

      if (state.search) {
        url.searchParams.set("search", state.search);
      }

      window.history.replaceState({}, "", url.toString());
    }

    function hydrateFromUrl() {
      const params = new URLSearchParams(window.location.search);

      filters.forEach(filter => {
        const allowed = new Set((itemOptions[filter.key] || []).map(option => normalize(option.value)));
        state.filters[filter.key] = readParamValues(params, filter.key, filter.aliases)
          .filter(value => !allowed.size || allowed.has(normalize(value)));
      });

      const page = Number(params.get("results-page") || params.get("paged") || params.get("page") || 1);
      state.currentPage = page > 0 ? page : 1;
      state.search = enableSearch ? clean(params.get("search") || "") : "";
    }

    function syncControls() {
      if (dom.search) {
        dom.search.value = state.search;
      }

      filters.forEach(filter => {
        const selected = new Set((state.filters[filter.key] || []).map(normalize));

        root.querySelectorAll(`.ells-filter-checkbox[data-field="${filter.key}"]`).forEach(input => {
          input.checked = selected.has(normalize(input.value));
        });

        root.querySelectorAll(`.ells-filter-select[data-field="${filter.key}"]`).forEach(select => {
          select.value = (state.filters[filter.key] || [])[0] || "";
        });
      });
    }

    function collectCheckboxValues(field) {
      return Array.from(root.querySelectorAll(`.ells-filter-checkbox[data-field="${field}"]:checked`))
        .map(input => input.value)
        .filter(Boolean);
    }

    function matchesFilter(item, field, selectedValues) {
      const selected = selectedValues || state.filters[field] || [];
      if (!selected.length) return true;

      const itemValues = ((item.filters || {})[field] || []).map(normalize);
      return selected.some(value => itemValues.includes(normalize(value)));
    }

    function matchesSearch(item) {
      if (!enableSearch || !state.search) return true;

      const haystack = [
        item.searchText,
        item.title,
        item.excerpt
      ].filter(Boolean).join(" ");

      return normalize(haystack).includes(normalize(state.search));
    }

    function itemMatchesOtherFilters(item, excludedField) {
      if (!matchesSearch(item)) return false;

      return filters.every(filter => {
        if (filter.key === excludedField) return true;
        return matchesFilter(item, filter.key);
      });
    }

    function countForOption(field, value) {
      return (config.items || []).filter(item => {
        return itemMatchesOtherFilters(item, field) && matchesFilter(item, field, [value]);
      }).length;
    }

    function countWithoutField(field) {
      return (config.items || []).filter(item => itemMatchesOtherFilters(item, field)).length;
    }

    function updateFilterCounts() {
      filters.forEach(filter => {
        root.querySelectorAll(`.ells-filter-count[data-field="${filter.key}"]`).forEach(countEl => {
          countEl.textContent = `(${countForOption(filter.key, countEl.dataset.value)})`;
        });

        root.querySelectorAll(`.ells-filter-select[data-field="${filter.key}"]`).forEach(select => {
          Array.from(select.options).forEach(option => {
            const label = option.dataset.label || option.textContent;
            const count = option.value ? countForOption(filter.key, option.value) : countWithoutField(filter.key);
            option.textContent = `${label} (${count})`;
          });
        });
      });
    }

    function renderInfo(start, end, total) {
      if (!dom.info) return;

      if (!total) {
        dom.info.innerHTML = "";
        return;
      }

      dom.info.innerHTML = `
        <p class="vf-text-body vf-text-body--3 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0">
          Showing <span class="counter-highlight">${start}</span> - <span class="counter-highlight">${end}</span>
          of <span class="counter-highlight">${total}</span> ${escapeHtml(config.resultLabel || "results")}
        </p>
      `;
    }

    function scrollToBrowser() {
      root.scrollIntoView({ behavior: "smooth", block: "start" });
    }

    function renderPagination(totalPages) {
      if (!dom.pagination) return;

      dom.pagination.innerHTML = "";
      if (totalPages <= 1) return;

      const addItem = (child, classes = "") => {
        const li = document.createElement("li");
        li.className = `vf-pagination__item${classes ? ` ${classes}` : ""}`;
        li.appendChild(child);
        dom.pagination.appendChild(li);
      };

      const makePageLink = (page, label, className = "vf-pagination__link") => {
        const link = document.createElement("a");
        link.href = "#";
        link.className = className;
        link.textContent = label;
        link.addEventListener("click", event => {
          event.preventDefault();
          state.currentPage = page;
          renderResults();
          syncUrl();
          scrollToBrowser();
        });
        return link;
      };

      if (state.currentPage > 1) {
        addItem(makePageLink(state.currentPage - 1, "Previous"), "vf-pagination__item--previous-page");
      } else {
        const span = document.createElement("span");
        span.className = "vf-pagination__label";
        span.textContent = "Previous";
        addItem(span, "vf-pagination__item--previous-page");
      }

      const pages = Array.from(new Set([1, totalPages, state.currentPage - 1, state.currentPage, state.currentPage + 1]))
        .filter(page => page >= 1 && page <= totalPages)
        .sort((a, b) => a - b);

      let lastPage = 0;
      pages.forEach(page => {
        if (page - lastPage > 1) {
          const dots = document.createElement("span");
          dots.className = "vf-pagination__label";
          dots.textContent = "...";
          addItem(dots);
        }

        if (page === state.currentPage) {
          const span = document.createElement("span");
          span.className = "vf-pagination__label";
          span.setAttribute("aria-current", "page");
          span.textContent = String(page);
          addItem(span, "vf-pagination__item--is-active");
        } else {
          const link = makePageLink(page, String(page));
          link.setAttribute("aria-label", `Go to page ${page}`);
          addItem(link);
        }

        lastPage = page;
      });

      if (state.currentPage < totalPages) {
        addItem(makePageLink(state.currentPage + 1, "Next"), "vf-pagination__item--next-page");
      } else {
        const span = document.createElement("span");
        span.className = "vf-pagination__label";
        span.textContent = "Next";
        addItem(span, "vf-pagination__item--next-page");
      }
    }

    function renderResults() {
      if (!dom.results) return;

      const total = state.filtered.length;
      const totalPages = Math.max(1, Math.ceil(total / itemsPerPage));
      if (state.currentPage > totalPages) state.currentPage = totalPages;

      if (!total) {
        dom.results.innerHTML = "<p>No results found.</p>";
        renderInfo(0, 0, 0);
        renderPagination(0);
        return;
      }

      const startIndex = (state.currentPage - 1) * itemsPerPage;
      const pageItems = state.filtered.slice(startIndex, startIndex + itemsPerPage);
      dom.results.innerHTML = pageItems.map((item, index) => {
        const divider = index < pageItems.length - 1 ? '<hr class="vf-divider">' : "";
        return `${item.html}${divider}`;
      }).join("");

      renderInfo(startIndex + 1, startIndex + pageItems.length, total);
      renderPagination(totalPages);
    }

    function applyFilters() {
      state.filtered = (config.items || []).filter(item => {
        return matchesSearch(item) && filters.every(filter => matchesFilter(item, filter.key));
      });
      updateFilterCounts();
      renderResults();
      syncUrl();
    }

    function setFilter(field, values) {
      state.filters[field] = values;
      state.currentPage = 1;
      applyFilters();
    }

    function setSearch(value) {
      state.search = clean(value);
      state.currentPage = 1;
      applyFilters();
    }

    hydrateFromUrl();
    syncControls();

    if (dom.form) {
      dom.form.addEventListener("submit", event => event.preventDefault());

      dom.form.addEventListener("change", event => {
        const target = event.target;

        if (target.classList.contains("ells-filter-checkbox")) {
          const field = target.dataset.field;
          setFilter(field, collectCheckboxValues(field));
          return;
        }

        if (target.classList.contains("ells-filter-select")) {
          const field = target.dataset.field;
          setFilter(field, target.value ? [target.value] : []);
        }
      });

      dom.form.addEventListener("input", event => {
        const target = event.target;
        if (target.classList.contains("ells-filter-search")) {
          setSearch(target.value);
        }
      });
    }

    if (dom.search) {
      const handleSearch = () => setSearch(dom.search.value);

      ["input", "keyup", "search", "change"].forEach(eventName => {
        dom.search.addEventListener(eventName, handleSearch);
      });
    }

    if (dom.clearAll) {
      dom.clearAll.addEventListener("click", () => {
        filters.forEach(filter => {
          state.filters[filter.key] = [];
        });
        state.search = "";
        state.currentPage = 1;
        syncControls();
        applyFilters();
      });
    }

    applyFilters();
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".ells-archive-browser").forEach(initArchiveBrowser);
  });
})();
