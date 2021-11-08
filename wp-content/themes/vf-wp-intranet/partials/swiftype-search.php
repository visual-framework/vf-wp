<div>
  <div class="vf-u-display-none">
    <form action="" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="text">Search</label>
          <input type="search" placeholder="Enter your search terms" id="text"
            class="vf-form__input | st-default-search-input" value="<?php echo esc_attr(get_search_query()); ?>"
            autofocus data-embl-search-input data-vf-search-client-side-input>
        </div>
        <div class="vf-form__item | vf-search__item vf-u-display-none">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" data-embl-search-facet>
            <option value="all" selected>Everything</option>
            <option value="People directory">People</option>
            <option value="Jobs">Jobs</option>
            <option value="News">News</option>
          </select>
        </div>
        <button type="submit" class="vf-search__button | vf-button vf-button--primary" data-embl-search-submit>
          <span class="vf-button__text">Search</span>
          <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
            <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
              <path
                d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z"
                fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0">
              </path>
            </g>
          </svg>
        </button>
      </div>
    </form>
  </div>
  <section>
    <main class="vf-content">
      <div class="st-info-container"></div>
      <div class="vf-form__item | vf-content" data-embl-search-filters>
      </div>
      <section class="st-search-container" data-embl-search-web-results>
      </section>
      <section class="vf-u-display-none" data-embl-search-results-divider>
        <h2 class="vf-text vf-text-heading--1 vf-text--invert vf-u-display-none">Alumni</h2>
      </section>
      <div class="results-container | vf-u-display-none" data-embl-search-alumni-results-wrapper>
        <div class="results-container" data-embl-search-alumni-results>
        </div>
        <br />
        <a href="https://hd-tqportal.embl.de/EMBL_LIVE_thankQ_Web/public/network/results.aspx"
          class="vf-button vf-button--outline vf-button--sm vf-u-display-none">See Alumni Directory for more
          info</a>
      </div>
    </main>
    <aside class="vf-content | vf-u-display-none" data-embl-search-alumni-info>
    </aside>
  </section>
</div>
