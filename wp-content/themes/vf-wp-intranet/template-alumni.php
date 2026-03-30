<?php
/**
 * Template Name: Alumni
 */
$alumni_endpoint = get_field('vf_wp_intranet_alumni_endpoint');

if (empty($alumni_endpoint)) {
  $alumni_endpoint = 'https://xs-db.embl.de/v3/alumni';
}

get_header();
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div></div>
  <div class="vf-stack | vf-content">
    <h1 class="vf-intro__heading"><?php the_title(); ?></h1>
    <h2 class="vf-intro__subheading" style="margin-bottom: 2rem;">Find EMBL alumni</h2>
    <p class="vf-intro__text">
     Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pulvinar dignissim fermentum.
    </p>
    <div class="alumni-results-toolbar">
      <div class="alumni-results-info">
        <div id="total-results-info-od" role="status" aria-live="polite" aria-atomic="true"></div>
      </div>
      <div class="alumni-sort-filter">
        <label for="alumni-sort" class="vf-form__label" style="font-size: 16px; font-weight: 500; width: 100%;">Sort by</label>
        <select id="alumni-sort" class="vf-form__select alumni-sort-select">
          <option value="last_name" selected>Last name</option>
          <option value="first_name">First name</option>
        </select>
      </div>
    </div>
  </div>
</section>

<section id="search" class="vf-u-padding__top--400">
  <div class="embl-grid embl-grid--has-centered-content">
    <div class="vf-u-margin__bottom--400">

      <div class="filters-layout">
        <?php
        $render_search_filter = function ($key, $label, $all) {
          $inputId = "filter-" . $key;
          $triggerId = $inputId . "-trigger";
          $panelId = $inputId . "-panel";
          $listId = $inputId . "-listbox";
          ?>
          <div class="filter-item" data-field="<?php echo $key; ?>">
            <p class="field-label vf-u-margin__bottom--200 vf-u-margin__top--0"><?php echo $label; ?></p>

            <div class="filter-anchor">
              <button
                type="button"
                id="<?php echo esc_attr($triggerId); ?>"
                class="vf-form__select filter-trigger"
                aria-haspopup="listbox"
                aria-controls="<?php echo esc_attr($panelId); ?>"
                aria-expanded="false"
              >
                <span class="filter-label" data-default="<?php echo esc_attr($all); ?>"><?php echo esc_html($all); ?></span>
              </button>

              <form action="#" class="vf-form vf-form--search vf-form--search--mini | vf-sidebar--end filter-search-form">
                <div
                  id="<?php echo esc_attr($panelId); ?>"
                  class="vf-sidebar__inner filter-panel"
                  role="region"
                  aria-labelledby="<?php echo esc_attr($triggerId); ?>"
                  hidden
                >
                  <div class="filter-panel-content">
                    <label for="<?php echo $inputId; ?>" class="visually-hidden">
                      Search alumni by <?php echo strtolower($label); ?>
                    </label>

                    <div class="filter-input-row">
                      <input
                        id="<?php echo $inputId; ?>"
                        type="search"
                        class="multi-input vf-form__input custom-input"
                        data-field="<?php echo $key; ?>"
                        placeholder="Search <?php echo $label; ?>"
                        autocomplete="off"
                        aria-controls="<?php echo esc_attr($listId); ?>"
                        aria-autocomplete="list"
                        aria-expanded="false"
                      >
                      <div class="vf-search__button | search-button vf-button--primary" aria-hidden="true">
                        <span class="vf-button__text | vf-u-sr-only">Search</span>
                        <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 140" width="140" height="140">
                          <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
                            <path d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z" fill="#FFFFFF"></path>
                          </g>
                        </svg>
                      </div>
                    </div>

                    <div class="autocomplete-shell">
                      <div class="autocomplete-topbar">
                        <div class="autocomplete-topbar-text">
                          <span class="selection-hint"></span>
                          <span class="selected-hint">0 selected</span>
                        </div>
                        <button type="button" class="field-clear-btn" disabled aria-label="Clear selected <?php echo esc_attr(strtolower($label)); ?> options">Clear all</button>
                      </div>
                      <ul id="<?php echo esc_attr($listId); ?>" class="autocomplete-list" role="listbox" aria-multiselectable="true"></ul>
                    </div>
                  </div>
                </div>
                <div></div>
                <div></div>
              </form>
            </div>

            <div class="selected-badges"></div>
          </div>
          <?php
        };

        $render_list_filter = function ($key, $label, $all) {
          $inputId = "filter-" . $key;
          $triggerId = $inputId . "-trigger";
          $panelId = $inputId . "-panel";
          $listId = $inputId . "-listbox";
          ?>
          <div class="filter-item" data-field="<?php echo $key; ?>">
            <p class="field-label vf-u-margin__bottom--200 vf-u-margin__top--0"><?php echo $label; ?></p>

            <div class="filter-anchor">
              <button
                type="button"
                id="<?php echo esc_attr($triggerId); ?>"
                class="vf-form__select filter-trigger"
                aria-haspopup="listbox"
                aria-controls="<?php echo esc_attr($panelId); ?>"
                aria-expanded="false"
              >
                <span class="filter-label" data-default="<?php echo esc_attr($all); ?>"><?php echo esc_html($all); ?></span>
              </button>

              <div
                id="<?php echo esc_attr($panelId); ?>"
                class="vf-sidebar__inner filter-panel list-only-panel"
                role="region"
                aria-labelledby="<?php echo esc_attr($triggerId); ?>"
                hidden
              >
                <div class="filter-panel-content">
                  <div class="autocomplete-shell">
                    <div class="autocomplete-topbar">
                      <div class="autocomplete-topbar-text">
                        <span class="selection-hint"></span>
                        <span class="selected-hint">0 selected</span>
                      </div>
                      <button type="button" class="field-clear-btn" disabled aria-label="Clear selected <?php echo esc_attr(strtolower($label)); ?> options">Clear all</button>
                    </div>
                    <ul id="<?php echo esc_attr($listId); ?>" class="autocomplete-list list-only-options" role="listbox" aria-multiselectable="true"></ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="selected-badges"></div>
          </div>
          <?php
        };
        ?>

        <div class="filter-item filter-item--name" data-field="name">
          <h3 class="vf-form__legend">Name</h3>

          <form action="#" class="vf-form vf-form--search vf-form--search--mini name-search-form vf-u-margin__top--400">
            <label for="filter-name" class="visually-hidden">Search alumni by name</label>

            <div class="filter-input-row">
              <input
                id="filter-name"
                type="search"
                class="name-autocomplete vf-form__input"
                data-field="name"
                placeholder="Search by alumni name"
                autocomplete="off"
                aria-controls="name-autocomplete-list"
                aria-haspopup="listbox"
                aria-autocomplete="list"
                aria-expanded="false"
              >
              <div class="vf-search__button | search-button vf-button--primary" aria-hidden="true">
                <span class="vf-button__text | vf-u-sr-only">Search</span>
                <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 140" width="140" height="140">
                  <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
                    <path d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z" fill="#FFFFFF"></path>
                  </g>
                </svg>
              </div>
            </div>
          </form>

          <ul class="autocomplete-list name-list" id="name-autocomplete-list" role="listbox"></ul>
        </div>

        <div class="filter-group">
          <h2 class="vf-form__legend">Location</h2>
          <div class="group-filter-grid">
            <?php $render_search_filter("city", "City", "All cities"); ?>
            <?php $render_search_filter("country", "Country", "All countries"); ?>
          </div>
        </div>

        <div class="filter-group">
          <h2 class="vf-form__legend">EMBL History</h2>
          <div class="group-filter-grid">
            <?php $render_search_filter("groupName", "EMBL group", "All EMBL groups"); ?>
            <?php $render_search_filter("unitName", "EMBL unit", "All EMBL units"); ?>
            <?php $render_list_filter("staff_category", "Staff category", "All staff categories"); ?>
          </div>
        </div>

        <div class="filter-group">
          <h2 class="vf-form__legend">Current position</h2>
          <div class="group-filter-grid">
            <?php $render_list_filter("current_career_level", "Career level", "All career levels"); ?>
            <?php $render_search_filter("organization", "Organisation", "All organisations"); ?>
            <?php $render_list_filter("sector", "Work sector", "All sectors"); ?>
            <?php $render_list_filter("jobCategory", "Job category", "All job categories"); ?>
          </div>
        </div>

        <div class="filter-item filter-item--mentor">
          <h3 class="vf-form__legend">Mentorship</h3>
          <div class="vf-form__item vf-form__item--checkbox vf-u-margin__top--400">
            <input type="checkbox" name="isMentor" value="true" id="filter-mentor" class="vf-form__checkbox">
            <label for="filter-mentor" class="vf-form__label">Open to mentoring</label>
          </div>
        </div>
      </div>

      <button type="button" id="clearAll" class="vf-link clear-all-button">Clear all filters</button>
    </div>

    <div>
      <h2 id="alumni-results-heading" class="visually-hidden">Alumni search results</h2>
      <div id="alumni-results-container" class="vf-u-margin__top--600" role="region" aria-labelledby="alumni-results-heading" aria-busy="true"></div>
      <nav class="vf-pagination" aria-label="Results pages">
        <ul class="vf-pagination__list paginationListOnDemand"></ul>
      </nav>
    </div>

    <div>
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a href="/internal-information/past-training/#">Link 1</a></p>
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a href="/internal-information/past-training/#">Link 2</a></p>
      <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a href="/internal-information/past-training/#">Link 3</a></p>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const DATA_URL = <?php echo wp_json_encode(esc_url_raw($alumni_endpoint)); ?>;
  const ITEMS_PER_PAGE = 15;
  const MAX_MULTI_SELECT = 5;

  const SEARCH_FIELDS = ["city", "country", "organization", "groupName", "unitName"];
  const LIST_ONLY_FIELDS = ["sector", "current_career_level", "jobCategory", "staff_category"];
  const MULTI_FIELDS = [...SEARCH_FIELDS, ...LIST_ONLY_FIELDS];

  const FIELD_TEXT = {
    city: { plural: "cities", search: "Search cities" },
    country: { plural: "countries", search: "Search countries" },
    organization: { plural: "organisations", search: "Search organisations" },
    groupName: { plural: "EMBL groups", search: "Search EMBL groups" },
    unitName: { plural: "EMBL units", search: "Search EMBL units" },
    sector: { plural: "sectors", search: "" },
    current_career_level: { plural: "career levels", search: "" },
    jobCategory: { plural: "job categories", search: "" },
    staff_category: { plural: "staff categories", search: "" },
    name: { plural: "names", search: "Search by alumni name" }
  };

  const PRESET_OPTIONS = {
    sector: [
      "Academia",
      "Government, public sector",
      "CNGOs, charities, foundations",
      "Non-science-related private sector",
      "Science-related private sector",
      "Self-employed"
    ],
    current_career_level: [
      "Head of organisation",
      "Head of department",
      "Head of team / group leader",
      "Postdoctoral fellow",
      "Predoctoral fellow",
      "Student / trainee",
      "Team / staff member"
    ],
    jobCategory: [
      "Research, development",
      "Clinical research, medicine, public health",
      "Scientific services, core facilities",
      "IP, tech transfer, venture capital",
      "Legal, regulatory affairs, policy",
      "Software development, data science, IT",
      "Administration, management, consulting",
      "Communications, outreach, publishing",
      "Sales, marketing, distribution",
      "Teaching",
      "Further study"
    ],
    staff_category: [
      "Research",
      "Scientific Services",
      "Scientific or Technical Support",
      "Administrative Support",
      "Training and Outreach",
      "General Support",
      "EMBO",
      "EMBLEM",
      "EMBL Ventures",
      "SAC and Council"
    ]
  };

  const dom = {
    resultsContainer: document.getElementById("alumni-results-container"),
    paginationList: document.querySelector(".paginationListOnDemand"),
    clearAll: document.getElementById("clearAll"),
    infoBlocks: Array.from(document.querySelectorAll("#total-results-info-od")),
    sortSelect: document.getElementById("alumni-sort")
  };

  const state = {
    allData: [],
    filtered: [],
    currentPage: 1,
    sortBy: "last_name",
    filters: {
      name: [],
      city: [],
      country: [],
      organization: [],
      groupName: [],
      unitName: [],
      sector: [],
      current_career_level: [],
      jobCategory: [],
      staff_category: [],
      isMentor: []
    },
    unique: {},
    uniqueNormalized: {}
  };

  const filterItems = Array.from(document.querySelectorAll(".filter-item[data-field]"));
  const filterItemByField = Object.fromEntries(filterItems.map(el => [el.dataset.field, el]));
  const dropdownControllers = {};

  /* Normalizes strings for case-insensitive comparisons and matching. */
  function normalize(v) {
    return (v || "").toString().toLowerCase().trim();
  }

  /* Trims values safely to a clean string. */
  function cleanString(v) {
    return (v || "").toString().trim();
  }

  /* Returns a normalized sort key for name-based ordering. */
  function getSortValue(person, sortBy) {
    if (sortBy === "first_name") {
      return cleanString(person.firstName);
    }

    return cleanString(person.lastName);
  }

  /* Sorts alumni records by the active name preference with a stable fallback. */
  function sortAlumniData(data) {
    return data.sort((a, b) => {
      const primaryCompare = getSortValue(a, state.sortBy).localeCompare(getSortValue(b, state.sortBy), undefined, { sensitivity: "base" });
      if (primaryCompare !== 0) return primaryCompare;

      const secondaryCompare = cleanString(a.firstName).localeCompare(cleanString(b.firstName), undefined, { sensitivity: "base" });
      if (secondaryCompare !== 0) return secondaryCompare;

      return cleanString(a.lastName).localeCompare(cleanString(b.lastName), undefined, { sensitivity: "base" });
    });
  }

  /* Checks if a field value should be considered meaningful content. */
  function isValid(v) {
    const n = normalize(v);
    return !!n && n !== "n/a" && n !== "na";
  }

  /* Converts known truthy API values into boolean true. */
  function isTruthy(v) {
    if (v === true) return true;
    const n = normalize(v);
    return n === "true" || n === "1" || n === "yes";
  }

  /* Escapes user/content strings before injecting into HTML. */
  function escapeHtml(str) {
    return String(str || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  /* Formats rich text blocks safely into paragraphs. */
  function formatOptionalText(value, fallback = "") {
    const text = cleanString(value);
    const safe = text || fallback;
    if (!safe) return "";

    return safe
      .split(/\n\s*\n+/)
      .map(paragraph => cleanString(paragraph))
      .filter(Boolean)
      .map(paragraph => `<p>${escapeHtml(paragraph).replace(/\n/g, "<br>")}</p>`)
      .join("");
  }

  /* Closes open profile tab panels within a card or the whole results area. */
  function closeProfileTabPanels(scope = dom.resultsContainer) {
    scope.querySelectorAll(".profile-tab-content").forEach(panel => {
      panel.hidden = true;
      panel.setAttribute("aria-hidden", "true");
      panel.removeAttribute("aria-labelledby");
      panel.innerHTML = "";
    });

    scope.querySelectorAll(".profile-tab-link").forEach(link => {
      link.classList.remove("is-active");
      link.setAttribute("aria-selected", "false");
    });
  }

  /* Closes all filter dropdown panels except an optional panel to keep open. */
  function closeAllFilterPanels(exceptPanel = null) {
    document.querySelectorAll(".filter-panel").forEach(panel => {
      if (panel !== exceptPanel) {
        panel.style.display = "none";
        panel.hidden = true;
        const anchor = panel.closest(".filter-anchor");
        const trigger = anchor ? anchor.querySelector(".filter-trigger") : null;
        const input = panel.querySelector(".multi-input");
        if (trigger) trigger.setAttribute("aria-expanded", "false");
        if (input) {
          input.setAttribute("aria-expanded", "false");
          input.removeAttribute("aria-activedescendant");
        }
      }
    });
  }

  /* Returns a de-duplicated array using normalized values for uniqueness. */
  function dedupeByNormalize(values) {
    const seen = new Set();
    const out = [];
    values.forEach(v => {
      const clean = cleanString(v);
      if (!clean) return;
      const key = normalize(clean);
      if (seen.has(key)) return;
      seen.add(key);
      out.push(clean);
    });
    return out;
  }

  /* Builds unique values for each filter field from API data. */
  function getUniqueValues(field, data) {
    if (field === "name") {
      return dedupeByNormalize(
        data.map(p => `${p.firstName || ""} ${p.lastName || ""}`.trim()).filter(Boolean)
      );
    }

    if (field === "groupName" || field === "unitName") {
      return dedupeByNormalize(
        data.flatMap(p => (p[field] ? String(p[field]).split(",").map(v => v.trim()) : [])).filter(Boolean)
      );
    }

    return dedupeByNormalize(data.map(p => cleanString(p[field])).filter(Boolean));
  }

  /* Updates trigger text and input placeholder for a specific filter field. */
  function updateFilterUI(field) {
    const filterItem = filterItemByField[field];
    if (!filterItem) return;

    const selectedCount = (state.filters[field] || []).length;
    const labelEl = filterItem.querySelector(".filter-label");
    const inputEl = filterItem.querySelector(".multi-input, .name-autocomplete");

    if (labelEl) {
      const defaultText = labelEl.dataset.default || "All";
      labelEl.textContent = selectedCount ? `${selectedCount} selected` : defaultText;
    }

    if (inputEl) {
      if (field === "name") {
        inputEl.placeholder = FIELD_TEXT.name.search;
      } else {
        inputEl.placeholder = FIELD_TEXT[field] && FIELD_TEXT[field].search ? FIELD_TEXT[field].search : "Search";
      }
    }
  }

  /* Checks if a value matches any selected entries of a multi-select filter. */
  function matchesMulti(value, selected) {
    if (!selected.length) return true;
    const n = normalize(value);
    return selected.some(v => n.includes(normalize(v)));
  }

  /* Scrolls the page back to the search section after pagination changes. */
  function scrollToSearchSection() {
    const searchSection = document.getElementById("search");
    if (searchSection) {
      searchSection.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }

  /* Renders compact pagination with previous/next and nearby page links. */
  function renderPagination(totalPages) {
    const list = dom.paginationList;
    list.innerHTML = "";
    if (totalPages <= 1) return;

    const mkItem = (content, active = false) => {
      const li = document.createElement("li");
      li.className = "vf-pagination__item";
      if (active) li.classList.add("vf-pagination__item--is-active");
      li.appendChild(content);
      return li;
    };

    const mkLink = (text, onClick, ariaLabel = "") => {
      const a = document.createElement("a");
      a.href = "#search";
      a.className = "vf-pagination__link";
      a.textContent = text;
      if (ariaLabel) a.setAttribute("aria-label", ariaLabel);
      a.addEventListener("click", e => {
        e.preventDefault();
        onClick();
      });
      return a;
    };

    const prev = document.createElement("li");
    prev.className = "vf-pagination__item vf-pagination__item--previous-page";
    if (state.currentPage > 1) {
      const prevA = document.createElement("a");
      prevA.textContent = "Previous";
      prevA.href = "#search";
      prevA.className = "vf-pagination__link";
      prevA.setAttribute("aria-label", `Go to previous page, page ${state.currentPage - 1}`);
      prevA.addEventListener("click", e => {
        e.preventDefault();
        state.currentPage--;
        renderResults();
        scrollToSearchSection();
      });
      prev.appendChild(prevA);
    } else {
      prev.classList.add("disabled");
      const prevSpan = document.createElement("span");
      prevSpan.className = "vf-pagination__label";
      prevSpan.textContent = "Previous";
      prev.appendChild(prevSpan);
    }
    list.appendChild(prev);

    const pages = new Set([1, totalPages, state.currentPage - 1, state.currentPage, state.currentPage + 1]);
    const ordered = [...pages].filter(p => p >= 1 && p <= totalPages).sort((a, b) => a - b);

    let last = 0;
    for (const p of ordered) {
      if (p - last > 1) {
        const dots = document.createElement("li");
        dots.className = "vf-pagination__item";
        dots.innerHTML = '<span class="vf-pagination__label">...</span>';
        list.appendChild(dots);
      }

      if (p === state.currentPage) {
        const span = document.createElement("span");
        span.className = "vf-pagination__label";
        span.setAttribute("aria-current", "page");
        span.textContent = String(p);
        list.appendChild(mkItem(span, true));
      } else {
        list.appendChild(mkItem(mkLink(String(p), () => {
          state.currentPage = p;
          renderResults();
          scrollToSearchSection();
        }, `Go to page ${p}`)));
      }

      last = p;
    }

    const next = document.createElement("li");
    next.className = "vf-pagination__item vf-pagination__item--next-page";
    if (state.currentPage < totalPages) {
      const nextA = document.createElement("a");
      nextA.textContent = "Next";
      nextA.href = "#search";
      nextA.className = "vf-pagination__link";
      nextA.setAttribute("aria-label", `Go to next page, page ${state.currentPage + 1}`);
      nextA.addEventListener("click", e => {
        e.preventDefault();
        state.currentPage++;
        renderResults();
        scrollToSearchSection();
      });
      next.appendChild(nextA);
    } else {
      next.classList.add("disabled");
      const nextSpan = document.createElement("span");
      nextSpan.className = "vf-pagination__label";
      nextSpan.textContent = "Next";
      next.appendChild(nextSpan);
    }
    list.appendChild(next);
  }

  /* Renders result cards and info text for the current page. */
  function renderResults() {
    const total = state.filtered.length;
    if (!total) {
      dom.resultsContainer.innerHTML = "<p>No results found.</p>";
      dom.resultsContainer.setAttribute("aria-busy", "false");
      dom.paginationList.innerHTML = "";
      dom.infoBlocks.forEach(el => (el.textContent = ""));
      return;
    }

    const start = (state.currentPage - 1) * ITEMS_PER_PAGE;
    const end = Math.min(start + ITEMS_PER_PAGE, total);
    const pageRows = state.filtered.slice(start, end);

    dom.resultsContainer.innerHTML = pageRows.map((p, idx) => {
      const profileIndex = start + idx;
      const tabPanelId = `profile-tab-panel-${profileIndex}`;
      const tabBioId = `profile-tab-bio-${profileIndex}`;
      const tabResearchId = `profile-tab-research-${profileIndex}`;

      const firstName = escapeHtml(cleanString(p.firstName));
      const lastName = escapeHtml(cleanString(p.lastName));
      const fullName = [firstName, lastName].filter(Boolean).join(" ");
      const profileNameHtml = `
        <span class="alumni-name-first">${firstName}</span>${firstName && lastName ? " " : ""}<span class="alumni-name-last">${lastName}</span>
      `;
      const city = escapeHtml(p.city || "");
      const country = escapeHtml(p.country || "");
      const cityCountry = [city, country].filter(Boolean).join(", ");

      const titleParts = [];
      if (isValid(p.position)) titleParts.push(escapeHtml(cleanString(p.position)));
      if (isValid(p.organization)) titleParts.push(escapeHtml(cleanString(p.organization)));

      const profileUrl = (typeof p.url === "string" && /^https?:\/\//i.test(p.url.trim())) ? p.url.trim() : "";

      const currentLevelAndSector = [
        cleanString(p.current_career_level),
        cleanString(p.sector)
      ].filter(isValid).map(escapeHtml).join(", ");

      const emblHistory = [
        cleanString(p.staff_category),
        cleanString(p.groupName),
        cleanString(p.unitName)
      ].filter(isValid).map(escapeHtml).join(", ");

      const hasBiography = isValid(p.biography);
      const hasResearchFocus = isValid(p.research_focus);
      const biographyHtml = hasBiography ? formatOptionalText(p.biography) : "";
      const researchFocusHtml = hasResearchFocus ? formatOptionalText(p.research_focus) : "";
      const profileTabLinks = [
        hasBiography ? `
            <button
              type="button"
              id="${tabBioId}"
              class="vf-link profile-tab-link"
              role="tab"
              aria-selected="false"
              aria-controls="${tabPanelId}"
              data-tab="biography"
            >Biography</button>
          ` : "",
        hasResearchFocus ? `
            <button
              type="button"
              id="${tabResearchId}"
              class="vf-link profile-tab-link"
              role="tab"
              aria-selected="false"
              aria-controls="${tabPanelId}"
              data-tab="research_focus"
            >Research focus</button>
          ` : ""
      ].join("");
      const profileTabSources = [
        hasBiography ? `<div data-tab="biography">${biographyHtml}</div>` : "",
        hasResearchFocus ? `<div data-tab="research_focus">${researchFocusHtml}</div>` : ""
      ].join("");

      return `
        <div class="vf-profile vf-profile--medium vf-profile--inline alumni-profile-card | vf-u-margin__bottom--800 ${idx === 0 ? "" : "vf-u-margin__top--800"}" data-profile-index="${profileIndex}">
          <img class="vf-profile__image" src="https://content.embl.org/sites/default/files/default_images/vf-icon--avatar.png" alt="" loading="lazy">
          <h3 class="vf-profile__title">
            ${profileUrl ? `<a href="${escapeHtml(profileUrl)}" class="vf-link" target="_blank" rel="noopener noreferrer">${profileNameHtml}</a>` : profileNameHtml}
          </h3>
          ${titleParts.length ? `<p class="vf-profile__job-title">${titleParts.join(", ")}</p>` : ""}
          <p class="vf-profile__text vf-u-margin__bottom--100">${cityCountry}</p>

          ${currentLevelAndSector ? `
            <p class="vf-summary__meta vf-u-margin__bottom--100">
              <span>Current Level and Sector:</span>&nbsp;<span class="vf-u-text-color--grey">${currentLevelAndSector}</span>
            </p>
          ` : ""}

          ${emblHistory ? `
            <p class="vf-summary__meta vf-u-margin__bottom--100">
              <span>EMBL History -</span>&nbsp;<span class="vf-u-text-color--grey">${emblHistory}</span>
            </p>
          ` : ""}

          ${profileTabSources ? `
            <div class="profile-tab-links vf-u-margin__top--200" role="tablist" aria-label="Profile details for ${fullName}">
              ${profileTabLinks}
            </div>

            <div id="${tabPanelId}" class="profile-tab-content vf-u-margin__top--200" role="tabpanel" aria-hidden="true" tabindex="-1" hidden></div>

            <div class="profile-tab-source" hidden aria-hidden="true">
              ${profileTabSources}
            </div>
          ` : ""}
        </div>
        <hr class="vf-divider">
      `;
    }).join("");

    const infoHTML = `
      <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0" aria-live="polite">
        Showing <span class="counter-highlight">${start + 1} - </span><span class="counter-highlight">${end}</span>
        results out of <span class="counter-highlight">${total}</span>
      </p>
    `;
    dom.infoBlocks.forEach(el => (el.innerHTML = infoHTML));
    dom.resultsContainer.setAttribute("aria-busy", "false");

    renderPagination(Math.ceil(total / ITEMS_PER_PAGE));
  }

  /* Applies all active filters to the dataset and refreshes result rendering. */
  function applyFilters() {
    state.filtered = state.allData.filter(p => {
      if (!matchesMulti(`${p.firstName || ""} ${p.lastName || ""}`, state.filters.name)) return false;
      if (!matchesMulti(p.city, state.filters.city)) return false;
      if (!matchesMulti(p.country, state.filters.country)) return false;
      if (!matchesMulti(p.organization, state.filters.organization)) return false;
      if (!matchesMulti(p.groupName, state.filters.groupName)) return false;
      if (!matchesMulti(p.unitName, state.filters.unitName)) return false;
      if (!matchesMulti(p.sector, state.filters.sector)) return false;
      if (!matchesMulti(p.current_career_level, state.filters.current_career_level)) return false;
      if (!matchesMulti(p.jobCategory, state.filters.jobCategory)) return false;
      if (!matchesMulti(p.staff_category, state.filters.staff_category)) return false;
      if (state.filters.isMentor.length && !isTruthy(p.isMentor)) return false;
      return true;
    });
    sortAlumniData(state.filtered);

    state.currentPage = 1;
    renderResults();
  }

  /* Renders selected-value badges for a specific filter field. */
  function renderSelectedBadges(field) {
    const filterItem = filterItemByField[field];
    if (!filterItem) return;

    const badges = filterItem.querySelector(".selected-badges");
    if (!badges) return;

    badges.innerHTML = "";

    state.filters[field].forEach(value => {
      const badge = document.createElement("button");
      const fieldLabel = filterItem.querySelector(".field-label, .vf-form__legend");
      badge.className = "filter-badge vf-badge vf-badge--primary customBadgeDarkBlue";
      badge.type = "button";
      badge.textContent = `${value} ✕`;
      badge.setAttribute("aria-label", `Remove ${value} from ${fieldLabel ? fieldLabel.textContent.trim() : field}`);
      badge.addEventListener("click", () => {
        state.filters[field] = state.filters[field].filter(v => normalize(v) !== normalize(value));
        updateFilterUI(field);
        renderSelectedBadges(field);
        applyFilters();
        if (dropdownControllers[field]) dropdownControllers[field].refresh();
      });
      badges.appendChild(badge);
    });
  }

  /* Schedules filtering work to the next animation frame for smoother UI. */
  let applyFiltersRaf = 0;
  function scheduleApplyFilters() {
    if (applyFiltersRaf) cancelAnimationFrame(applyFiltersRaf);
    applyFiltersRaf = requestAnimationFrame(() => {
      applyFiltersRaf = 0;
      applyFilters();
    });
  }

  /* Initializes searchable dropdown filters with keyboard and multi-select support. */
  function setupSearchDropdownFilter(filterItem) {
    const field = filterItem.dataset.field;
    const trigger = filterItem.querySelector(".filter-trigger");
    const panel = filterItem.querySelector(".filter-panel");
    const form = filterItem.querySelector(".filter-search-form");
    const input = filterItem.querySelector(".multi-input");
    const list = filterItem.querySelector(".autocomplete-list");
    const clearBtn = filterItem.querySelector(".field-clear-btn");
    const hint = filterItem.querySelector(".selection-hint");
    const selectedHint = filterItem.querySelector(".selected-hint");

    if (!trigger || !panel || !form || !input || !list || !clearBtn || !hint || !selectedHint) return;

    const INITIAL_OPEN_LIMIT = 400;
    const APPEND_CHUNK_SIZE = 500;

    let focus = -1;
    let options = [];
    let renderToken = 0;
    let overLimitTimer = null;

    const selectedSet = new Set((state.filters[field] || []).map(v => normalize(v)));
    const pluralLabel = FIELD_TEXT[field] && FIELD_TEXT[field].plural ? FIELD_TEXT[field].plural : `${field}s`;

    const syncSelectedSet = () => {
      selectedSet.clear();
      (state.filters[field] || []).forEach(v => selectedSet.add(normalize(v)));
    };

    const setLiSelected = (li, isSelected) => {
      li.classList.toggle("is-selected", isSelected);
      li.setAttribute("aria-selected", isSelected ? "true" : "false");
      const check = li.querySelector(".item-check");
      if (check) check.textContent = isSelected ? "✓" : "";
    };

    const updateVisibleSelection = (key, isSelected) => {
      const items = list.children;
      for (let i = 0; i < items.length; i++) {
        const li = items[i];
        if (li.dataset.key === key) setLiSelected(li, isSelected);
      }
    };

    const renderHints = (overLimit = false) => {
      syncSelectedSet();
      const count = selectedSet.size;
      hint.textContent = `Select up to ${MAX_MULTI_SELECT} ${pluralLabel}`;
      selectedHint.textContent = `${count} selected`;
      selectedHint.classList.toggle("is-visible", count > 0);
      selectedHint.classList.toggle("is-over-limit", overLimit && count >= MAX_MULTI_SELECT);
      clearBtn.disabled = count === 0;

      if (overLimit && count >= MAX_MULTI_SELECT) {
        if (overLimitTimer) clearTimeout(overLimitTimer);
        overLimitTimer = setTimeout(() => selectedHint.classList.remove("is-over-limit"), 1000);
      }
    };

    const rowHtml = (value, index) => {
      const key = normalize(value);
      const isSelected = selectedSet.has(key);
      return `
        <li
          id="${field}-option-${index}"
          data-index="${index}"
          data-key="${escapeHtml(key)}"
          class="${isSelected ? "is-selected" : ""}"
          role="option"
          aria-selected="${isSelected ? "true" : "false"}"
          tabindex="-1"
        >
          <span class="item-label">${escapeHtml(value)}</span>
          <span class="item-check">${isSelected ? "✓" : ""}</span>
        </li>
      `;
    };

    const rowsHtml = (values, start, end) => {
      let html = "";
      for (let i = start; i < end; i++) html += rowHtml(values[i], i);
      return html;
    };

    const renderList = (values, incremental = false) => {
      renderToken++;
      const token = renderToken;

      options = values;
      focus = -1;
      list.innerHTML = "";
      input.removeAttribute("aria-activedescendant");
      renderHints(false);

      if (!incremental || values.length <= INITIAL_OPEN_LIMIT) {
        list.innerHTML = rowsHtml(values, 0, values.length);
        return;
      }

      let cursor = Math.min(INITIAL_OPEN_LIMIT, values.length);
      list.innerHTML = rowsHtml(values, 0, cursor);

      const appendMore = () => {
        if (token !== renderToken || cursor >= values.length) return;
        const end = Math.min(cursor + APPEND_CHUNK_SIZE, values.length);
        list.insertAdjacentHTML("beforeend", rowsHtml(values, cursor, end));
        cursor = end;
        if (cursor < values.length) setTimeout(appendMore, 0);
      };

      setTimeout(appendMore, 0);
    };

    const refresh = () => {
      syncSelectedSet();
      const q = normalize(input.value);
      const base = state.unique[field] || [];
      const baseNorm = state.uniqueNormalized[field] || [];

      if (!q) {
        renderList(base, true);
        return;
      }

      const values = [];
      for (let i = 0; i < baseNorm.length; i++) {
        if (baseNorm[i].includes(q)) values.push(base[i]);
      }
      renderList(values, false);
    };

    const syncStateAfterToggle = () => {
      updateFilterUI(field);
      renderSelectedBadges(field);
      renderHints(false);
      scheduleApplyFilters();
    };

    const canonicalFromUnique = (raw) => {
      const key = normalize(raw);
      const base = state.unique[field] || [];
      for (let i = 0; i < base.length; i++) {
        if (normalize(base[i]) === key) return base[i];
      }
      return raw;
    };

    const toggleValue = (rawValue, li = null) => {
      const value = cleanString(rawValue);
      if (!value) return;
      const key = normalize(value);

      if (selectedSet.has(key)) {
        selectedSet.delete(key);
        state.filters[field] = state.filters[field].filter(v => normalize(v) !== key);
        if (li) setLiSelected(li, false); else updateVisibleSelection(key, false);
        syncStateAfterToggle();
        return;
      }

      if (selectedSet.size >= MAX_MULTI_SELECT) {
        renderHints(true);
        return;
      }

      selectedSet.add(key);
      state.filters[field].push(canonicalFromUnique(value));
      if (li) setLiSelected(li, true); else updateVisibleSelection(key, true);
      syncStateAfterToggle();
    };

    const getEnterSelection = () => {
      if (focus > -1 && options[focus]) return options[focus];
      const typed = cleanString(input.value);
      if (!typed) return "";
      const key = normalize(typed);
      const base = state.unique[field] || [];
      for (let i = 0; i < base.length; i++) {
        if (normalize(base[i]) === key) return base[i];
      }
      return "";
    };

    const openPanel = () => {
      closeAllFilterPanels(panel);
      panel.style.display = "block";
      panel.hidden = false;
      trigger.setAttribute("aria-expanded", "true");
      input.setAttribute("aria-expanded", "true");
      try { input.focus({ preventScroll: true }); } catch (err) { input.focus(); }
      requestAnimationFrame(refresh);
    };

    trigger.addEventListener("click", openPanel);

    form.addEventListener("submit", e => {
      e.preventDefault();
      const selected = getEnterSelection();
      if (selected) toggleValue(selected);
    });

    input.addEventListener("input", refresh);

    input.addEventListener("keydown", e => {
      const items = list.querySelectorAll("li");

      if (e.key === "ArrowDown") {
        if (!items.length) return;
        e.preventDefault();
        focus = (focus + 1) % items.length;
        items.forEach(el => el.classList.remove("active"));
        items[focus]?.classList.add("active");
        if (items[focus]) {
          input.setAttribute("aria-activedescendant", items[focus].id);
          items[focus].scrollIntoView({ block: "nearest" });
        }
        return;
      }

      if (e.key === "ArrowUp") {
        if (!items.length) return;
        e.preventDefault();
        focus = (focus - 1 + items.length) % items.length;
        items.forEach(el => el.classList.remove("active"));
        items[focus]?.classList.add("active");
        if (items[focus]) {
          input.setAttribute("aria-activedescendant", items[focus].id);
          items[focus].scrollIntoView({ block: "nearest" });
        }
        return;
      }

      if (e.key === "Enter") {
        e.preventDefault();
        const selected = getEnterSelection();
        if (!selected) return;
        const activeLi = focus > -1 ? list.querySelector(`li[data-index="${focus}"]`) : null;
        toggleValue(selected, activeLi || null);
        input.removeAttribute("aria-activedescendant");
      }

      if (e.key === "Escape") {
        e.preventDefault();
        closeAllFilterPanels();
        trigger.focus();
      }
    });

    panel.addEventListener("click", e => e.stopPropagation());

    list.addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();
      const li = e.target.closest("li");
      if (!li) return;
      const index = Number(li.dataset.index);
      if (Number.isNaN(index) || !options[index]) return;
      toggleValue(options[index], li);
      try { input.focus({ preventScroll: true }); } catch (err) { input.focus(); }
    });

    clearBtn.addEventListener("click", e => {
      e.preventDefault();
      selectedSet.clear();
      state.filters[field] = [];
      list.querySelectorAll("li.is-selected").forEach(li => setLiSelected(li, false));
      syncStateAfterToggle();
      try { input.focus({ preventScroll: true }); } catch (err) { input.focus(); }
    });

    dropdownControllers[field] = { refresh };
    refresh();
  }

  /* Initializes fixed-option list filters (no search input), with multi-select behavior. */
  function setupListOnlyFilter(filterItem) {
    const field = filterItem.dataset.field;
    const trigger = filterItem.querySelector(".filter-trigger");
    const panel = filterItem.querySelector(".filter-panel");
    const list = filterItem.querySelector(".list-only-options");
    const clearBtn = filterItem.querySelector(".field-clear-btn");
    const hint = filterItem.querySelector(".selection-hint");
    const selectedHint = filterItem.querySelector(".selected-hint");

    if (!trigger || !panel || !list || !clearBtn || !hint || !selectedHint) return;

    const hintTextByField = {
      sector: "Select work sectors",
      current_career_level: "Select career levels",
      jobCategory: "Select job categories",
      staff_category: "Select staff categories"
    };

    let options = state.unique[field] || [];
    const selectedSet = new Set((state.filters[field] || []).map(v => normalize(v)));

    const syncSelectedSet = () => {
      selectedSet.clear();
      (state.filters[field] || []).forEach(v => selectedSet.add(normalize(v)));
    };

    const setLiSelected = (li, isSelected) => {
      li.classList.toggle("is-selected", isSelected);
      li.setAttribute("aria-selected", isSelected ? "true" : "false");
      const check = li.querySelector(".item-check");
      if (check) check.textContent = isSelected ? "✓" : "";
    };

    const updateVisibleSelection = (key, isSelected) => {
      const items = list.children;
      for (let i = 0; i < items.length; i++) {
        const li = items[i];
        if (li.dataset.key === key) setLiSelected(li, isSelected);
      }
    };

    const renderHints = () => {
      syncSelectedSet();
      const count = selectedSet.size;

      hint.textContent = hintTextByField[field] || "Select options";
      selectedHint.textContent = `${count} selected`;
      selectedHint.classList.toggle("is-visible", count > 0);
      selectedHint.classList.remove("is-over-limit");
      clearBtn.disabled = count === 0;
    };

    const renderList = () => {
      syncSelectedSet();
      options = state.unique[field] || [];

      list.innerHTML = options.map((value, index) => {
        const key = normalize(value);
        const isSelected = selectedSet.has(key);
        return `
          <li
            id="${field}-option-${index}"
            data-index="${index}"
            data-key="${escapeHtml(key)}"
            class="${isSelected ? "is-selected" : ""}"
            role="option"
            aria-selected="${isSelected ? "true" : "false"}"
            tabindex="0"
          >
            <span class="item-label">${escapeHtml(value)}</span>
            <span class="item-check">${isSelected ? "✓" : ""}</span>
          </li>
        `;
      }).join("");

      renderHints();
    };

    const syncState = () => {
      updateFilterUI(field);
      renderSelectedBadges(field);
      renderHints();
      scheduleApplyFilters();
    };

    const toggleValue = (rawValue, li = null) => {
      const value = cleanString(rawValue);
      if (!value) return;

      const key = normalize(value);

      if (selectedSet.has(key)) {
        selectedSet.delete(key);
        state.filters[field] = state.filters[field].filter(v => normalize(v) !== key);
        if (li) setLiSelected(li, false);
        else updateVisibleSelection(key, false);
        syncState();
        return;
      }

      selectedSet.add(key);
      const canonical = options.find(v => normalize(v) === key) || value;
      state.filters[field].push(canonical);
      if (li) setLiSelected(li, true);
      else updateVisibleSelection(key, true);
      syncState();
    };

    const openPanel = () => {
      closeAllFilterPanels(panel);
      panel.style.display = "block";
      panel.hidden = false;
      trigger.setAttribute("aria-expanded", "true");
      renderList();
    };

    trigger.addEventListener("click", openPanel);

    panel.addEventListener("click", e => e.stopPropagation());

    panel.addEventListener("keydown", e => {
      if (e.key === "Escape") {
        e.preventDefault();
        closeAllFilterPanels();
        trigger.focus();
      }
    });

    list.addEventListener("click", e => {
      e.preventDefault();
      e.stopPropagation();

      const li = e.target.closest("li");
      if (!li) return;

      const index = Number(li.dataset.index);
      if (Number.isNaN(index) || !options[index]) return;

      toggleValue(options[index], li);
    });

    list.addEventListener("keydown", e => {
      const li = e.target.closest("li");
      if (!li) return;

      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        const index = Number(li.dataset.index);
        if (Number.isNaN(index) || !options[index]) return;
        toggleValue(options[index], li);
      } else if (e.key === "ArrowDown") {
        e.preventDefault();
        li.nextElementSibling?.focus();
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        li.previousElementSibling?.focus();
      }
    });

    clearBtn.addEventListener("click", e => {
      e.preventDefault();
      selectedSet.clear();
      state.filters[field] = [];

      const selectedLis = list.querySelectorAll("li.is-selected");
      selectedLis.forEach(li => setLiSelected(li, false));

      syncState();
    });

    dropdownControllers[field] = { refresh: renderList };
    renderList();
  }

  /* Initializes the name autocomplete input with progressive rendering for performance. */
  function setupNameAutocomplete() {
    const nameInput = document.getElementById("filter-name");
    const nameForm = document.querySelector(".name-search-form");
    const nameList = document.querySelector(".name-list");

    if (!nameInput || !nameForm || !nameList) return;

    const NAME_INITIAL_CHUNK = 300;
    const NAME_APPEND_CHUNK = 500;

    let focus = -1;
    let options = [];
    let renderToken = 0;

    const applyNameFilter = (value, immediate = false) => {
      const v = cleanString(value);
      state.filters.name = v ? [v] : [];
      if (immediate) applyFilters();
      else scheduleApplyFilters();
    };

    const setNameExpanded = expanded => {
      nameInput.setAttribute("aria-expanded", expanded ? "true" : "false");
    };

    const renderNameList = (showAll = false) => {
      renderToken++;
      const token = renderToken;

      const q = normalize(nameInput.value);
      const base = state.unique.name || [];
      const baseNorm = state.uniqueNormalized.name || [];

      let values = [];
      if (showAll || !q) {
        values = base;
      } else {
        for (let i = 0; i < baseNorm.length; i++) {
          if (baseNorm[i].includes(q)) values.push(base[i]);
        }
      }

      options = values;
      focus = -1;
      nameList.innerHTML = "";
      nameInput.removeAttribute("aria-activedescendant");
      setNameExpanded(values.length > 0);

      const appendChunk = (start, size) => {
        if (token !== renderToken) return;
        const end = Math.min(start + size, options.length);

        let html = "";
        for (let i = start; i < end; i++) {
          html += `<li id="filter-name-option-${i}" data-index="${i}" role="option" aria-selected="false">${escapeHtml(options[i])}</li>`;
        }
        nameList.insertAdjacentHTML("beforeend", html);

        if (end < options.length) {
          setTimeout(() => appendChunk(end, NAME_APPEND_CHUNK), 0);
        }
      };

      appendChunk(0, NAME_INITIAL_CHUNK);
    };

    const commitSelection = raw => {
      const selected = cleanString(raw);
      nameInput.value = selected;
      applyNameFilter(selected, true);
      nameList.innerHTML = "";
      setNameExpanded(false);
      nameInput.removeAttribute("aria-activedescendant");
    };

    nameInput.addEventListener("focus", () => renderNameList(true));
    nameInput.addEventListener("click", () => renderNameList(true));

    nameInput.addEventListener("input", () => {
      renderNameList(false);
      applyNameFilter(nameInput.value, false);
    });

    nameForm.addEventListener("submit", e => {
      e.preventDefault();
      const selected = (focus > -1 && options[focus]) ? options[focus] : nameInput.value;
      commitSelection(selected);
    });

    nameInput.addEventListener("keydown", e => {
      const items = nameList.querySelectorAll("li");
      if (!items.length && e.key !== "Enter") return;

      if (e.key === "ArrowDown") {
        e.preventDefault();
        focus = (focus + 1) % items.length;
        items.forEach(el => el.classList.remove("active"));
        items[focus]?.classList.add("active");
        if (items[focus]) nameInput.setAttribute("aria-activedescendant", items[focus].id);
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        focus = (focus - 1 + items.length) % items.length;
        items.forEach(el => el.classList.remove("active"));
        items[focus]?.classList.add("active");
        if (items[focus]) nameInput.setAttribute("aria-activedescendant", items[focus].id);
      } else if (e.key === "Enter") {
        e.preventDefault();
        const selected = (focus > -1 && options[focus]) ? options[focus] : nameInput.value;
        commitSelection(selected);
      } else if (e.key === "Escape") {
        nameList.innerHTML = "";
        setNameExpanded(false);
        nameInput.removeAttribute("aria-activedescendant");
      }
    });

    nameList.addEventListener("click", e => {
      const li = e.target.closest("li");
      if (!li) return;
      const index = Number(li.dataset.index);
      if (Number.isNaN(index) || !options[index]) return;
      commitSelection(options[index]);
    });
  }

  /* Adds hover/focus visual feedback from the name input to the adjacent icon block. */
  function setupNameSearchVisualState() {
    const nameInput = document.getElementById("filter-name");
    const nameBtn = document.querySelector(".filter-item--name .search-button");

    if (!nameInput || !nameBtn) return;

    const setOn = () => {
      nameBtn.style.boxShadow = "0 0 0 .0625rem #54585a";
    };

    const setOff = () => {
      if (document.activeElement !== nameInput) {
        nameBtn.style.boxShadow = "";
      }
    };

    nameInput.addEventListener("focus", setOn);
    nameInput.addEventListener("blur", () => {
      nameBtn.style.boxShadow = "";
    });
    nameInput.addEventListener("mouseenter", setOn);
    nameInput.addEventListener("mouseleave", setOff);
  }

  /* Clears all filter states, resets UI elements, and re-renders full results. */
  function clearAllFilters(e) {
    if (e) e.preventDefault();

    Object.keys(state.filters).forEach(k => { state.filters[k] = []; });

    document.querySelectorAll(".selected-badges").forEach(el => { el.innerHTML = ""; });
    document.querySelectorAll(".autocomplete-list").forEach(el => { el.innerHTML = ""; });
    closeAllFilterPanels();

    const nameInput = document.getElementById("filter-name");
    if (nameInput) {
      nameInput.value = "";
      nameInput.setAttribute("aria-expanded", "false");
    }

    const mentorCheckbox = document.getElementById("filter-mentor");
    if (mentorCheckbox) mentorCheckbox.checked = false;

    filterItems.forEach(item => {
      const field = item.dataset.field;
      updateFilterUI(field);
      if (MULTI_FIELDS.includes(field) && dropdownControllers[field]) {
        dropdownControllers[field].refresh();
      }
    });

    closeProfileTabPanels();

    state.currentPage = 1;
    applyFilters();
  }

  /* Initializes all data, UI controls, event handlers, and first render. */
  async function init() {
    try {
      dom.resultsContainer.setAttribute("aria-busy", "true");
      const res = await fetch(DATA_URL);
      state.allData = await res.json();
      sortAlumniData(state.allData);
      state.filtered = [...state.allData];

      ["name", ...SEARCH_FIELDS].forEach(field => {
        state.unique[field] = getUniqueValues(field, state.allData).sort((a, b) => a.localeCompare(b));
        state.uniqueNormalized[field] = state.unique[field].map(v => normalize(v));
      });

      LIST_ONLY_FIELDS.forEach(field => {
        const preset = PRESET_OPTIONS[field] || [];
        state.unique[field] = preset.slice();
        state.uniqueNormalized[field] = state.unique[field].map(v => normalize(v));
      });

      filterItems.forEach(item => updateFilterUI(item.dataset.field));

      SEARCH_FIELDS.forEach(field => {
        const item = filterItemByField[field];
        if (item) setupSearchDropdownFilter(item);
      });

      LIST_ONLY_FIELDS.forEach(field => {
        const item = filterItemByField[field];
        if (item) setupListOnlyFilter(item);
      });

      setupNameAutocomplete();
      setupNameSearchVisualState();

      const mentorCheckbox = document.getElementById("filter-mentor");
      if (mentorCheckbox) {
        mentorCheckbox.addEventListener("change", () => {
          state.filters.isMentor = mentorCheckbox.checked ? ["true"] : [];
          applyFilters();
        });
      }

      if (dom.sortSelect) {
        dom.sortSelect.addEventListener("change", () => {
          state.sortBy = dom.sortSelect.value === "first_name" ? "first_name" : "last_name";
          applyFilters();
        });
      }

      dom.clearAll.addEventListener("click", clearAllFilters);

      document.addEventListener("click", e => {
        if (!e.target.closest(".filter-anchor")) {
          closeAllFilterPanels();
        }

        const nameItem = filterItemByField.name;
        if (nameItem && !nameItem.contains(e.target)) {
          const nameList = nameItem.querySelector(".name-list");
          if (nameList) {
            nameList.innerHTML = "";
            const nameInput = document.getElementById("filter-name");
            if (nameInput) {
              nameInput.setAttribute("aria-expanded", "false");
              nameInput.removeAttribute("aria-activedescendant");
            }
          }
        }
      });

      dom.resultsContainer.addEventListener("click", e => {
        const tabLink = e.target.closest(".profile-tab-link");
        if (!tabLink) return;

        e.preventDefault();

        const profileCard = tabLink.closest(".alumni-profile-card");
        if (!profileCard) return;

        const panel = profileCard.querySelector(".profile-tab-content");
        const tab = tabLink.dataset.tab;
        const source = profileCard.querySelector(`.profile-tab-source [data-tab="${tab}"]`);
        const isAlreadyOpen = tabLink.classList.contains("is-active") && panel && !panel.hidden;

        if (isAlreadyOpen) {
          closeProfileTabPanels(profileCard);
          return;
        }

        closeProfileTabPanels(profileCard);

        if (panel && source) {
          panel.innerHTML = source.innerHTML;
          panel.hidden = false;
          panel.setAttribute("aria-hidden", "false");
          panel.setAttribute("aria-labelledby", tabLink.id);
          tabLink.classList.add("is-active");
          tabLink.setAttribute("aria-selected", "true");
        }
      });

      dom.resultsContainer.addEventListener("keydown", e => {
        const tabLink = e.target.closest(".profile-tab-link");
        if (!tabLink) return;

        const tabList = tabLink.closest('[role="tablist"]');
        if (!tabList) return;

        const tabs = Array.from(tabList.querySelectorAll(".profile-tab-link"));
        const currentIndex = tabs.indexOf(tabLink);
        if (currentIndex === -1) return;

        let targetIndex = -1;

        if (e.key === "ArrowRight") {
          targetIndex = (currentIndex + 1) % tabs.length;
        } else if (e.key === "ArrowLeft") {
          targetIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        } else if (e.key === "Home") {
          targetIndex = 0;
        } else if (e.key === "End") {
          targetIndex = tabs.length - 1;
        }

        if (targetIndex === -1) return;

        e.preventDefault();
        tabs[targetIndex].focus();
      });

      renderResults();
    } catch (err) {
      dom.resultsContainer.innerHTML = "<p role=\"alert\">Could not load alumni data.</p>";
      dom.resultsContainer.setAttribute("aria-busy", "false");
      console.error(err);
    }
  }

  init();
});
</script>

<style>
.alumni-results-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem 2rem;
  flex-wrap: wrap;
  margin-top: 3rem;
}

.alumni-results-info {
  flex: 1 1 18rem;
}

.alumni-sort-filter {
  display: flex;
  align-items: center;
  flex-direction: row;
  gap: 0.5rem;
  flex-wrap: nowrap;
}

.alumni-sort-filter .vf-form__label {
  margin-bottom: 0;
}

.alumni-sort-select {
  min-width: 8rem;
}

.alumni-name-first {
  font-weight: 400;
}

.alumni-name-last {
  font-weight: 600;
}

.filters-layout {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  margin-bottom: 2.5rem;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.group-filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 1rem;
}

.selected-badges {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
  flex-wrap: wrap;
}

.filter-badge {
  cursor: pointer;
  border: 0;
}

.filter-badge:hover {
  background: #c9d3df;
}

.filter-item {
  position: relative;
}

.filter-search-form {
  margin: 0;
}

.filter-anchor {
  position: relative;
}

.filter-trigger {
  cursor: pointer;
  text-align: left;
  width: 100%;
}

.filter-trigger:focus-visible {
  outline: 2px solid #2f6fb7;
  outline-offset: 2px;
}

.filter-panel {
  position: absolute;
  display: none;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  box-sizing: border-box;
  z-index: 1000;
  background: #fff;
  border: 1px solid #cfcfcf;
}

.filter-panel-content {
  margin: 0;
  width: 100%;
  min-width: 0;
}

.filter-input-row {
  display: flex;
  align-items: stretch;
}

.filter-input-row .multi-input {
  flex: 1 1 auto;
  min-width: 0;
}

.search-button {
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  -ms-flex-item-align: center;
  align-self: center;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  background-color: #000;
  background-color: var(--vf-button-background-color, var(--vf-button__color__background--default));
  color: #fff;
  color: var(--vf-button-text-color, #ffffff);
  display: inline-block;
  margin: 0;
  outline: 0;
  padding: 12px 12px;
  position: relative;
  text-align: center;
  text-decoration: none;
}

.autocomplete-shell {
  background: #fafafa;
  border-top: 1px solid #d5d5d5;
}

.list-only-panel .autocomplete-shell {
  border-top: 0;
}

.autocomplete-topbar {
  position: sticky;
  top: 0;
  z-index: 2;
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  gap: 0.75rem;
  padding: 0.5rem 0.75rem;
  background: #f1f1f1;
  border-bottom: 1px solid #d5d5d5;
}

.selection-hint {
  font-size: 13px;
  line-height: 1.57;
}

.autocomplete-topbar-text {
  display: flex;
  flex-direction: column;
}

.selected-hint {
  display: none;
  font-weight: 500;
  font-size: 11px;
  transition: color 0.1s ease;
}

.selected-hint.is-visible {
  display: block;
}

.selected-hint.is-over-limit {
  color: #D41645;
}

.field-clear-btn {
  background: transparent;
  border: 0;
  padding: 0;
  margin: 0;
  font-size: 13px;
  color: #2f6fb7;
  cursor: pointer;
  white-space: nowrap;
}

.field-clear-btn:disabled {
  color: #9b9b9b;
  cursor: not-allowed;
  text-decoration: none;
}

.autocomplete-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.filter-panel .autocomplete-list {
  position: static;
  max-height: 220px;
  overflow-y: auto;
  background: #fafafa;
}

.autocomplete-list li {
  padding: 0.6rem 0.75rem;
  cursor: pointer;
}

.filter-panel .autocomplete-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.filter-panel .autocomplete-list li:hover,
.filter-panel .autocomplete-list li.active {
  background: #dcecff;
}

.filter-panel .autocomplete-list li.is-selected {
  background: #edf5ff;
}

.item-label {
  font-size: 14px;
  display: inline-block;
  min-width: 0;
}

.item-check {
  color: #2f6fb7;
  font-weight: 700;
  min-width: 16px;
  text-align: right;
}

.name-list {
  z-index: 30;
  margin: -1px;
  max-height: 200px;
  overflow-y: auto;
  background: #fafafa;
  border-right: 1px solid #ccc;
  border-left: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
}

.name-list li:hover,
.name-list li.active {
  background: #dcecff;
}

.name-autocomplete {
  z-index: 100;
}

.visually-hidden {
  position: absolute !important;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0 0 0 0);
  white-space: nowrap;
  border: 0;
}

.vf-form__input:not([type=file]) {
  font-size: 14px;
}

.vf-form__legend {
  margin: 0;
  font-size: 21px;
}

.vf-summary__meta {
  margin-bottom: 0;
}

.meta-wrapper {
  display: flex;
  gap: 2rem;
}

.custom-input:not([type=file]):focus,
.custom-input:not([type=file]):hover,
.vf-form__select:focus {
  box-shadow: none !important;
}

.profile-tab-links {
  display: flex;
  gap: 1rem;
}

.profile-tab-link,
.clear-all-button {
  background: transparent;
  border: 0;
  padding: 0;
  cursor: pointer;
  font: inherit;
}

.profile-tab-link.is-active {
  text-decoration: underline;
  font-weight: 600;
}

.profile-tab-link:focus-visible {
  outline: 2px solid #2f6fb7;
  outline-offset: 2px;
}

.clear-all-button:focus-visible,
.filter-badge:focus-visible {
  outline: 2px solid #2f6fb7;
  outline-offset: 2px;
}

.profile-tab-content {
  background: #fafafa;
  padding: 0.75rem;
}

.profile-tab-content p {
  margin: 0 0 1.5rem;
}

.profile-tab-content p:last-child {
  margin-bottom: 0;
}

.vf-profile--medium .vf-profile__job-title {
  font-size: 19px !important;
}
</style>

<?php get_footer(); ?>
