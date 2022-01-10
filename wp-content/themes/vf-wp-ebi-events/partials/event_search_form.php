<form action="#eventsFilter" onsubmit="return false;"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
  <div class="vf-sidebar__inner">
    <div class="vf-form__item">
      <label class="vf-form__label vf-u-sr-only | vf-search__label" for="textbox-filter">Search</label>
      <input id="textbox-filter" data-jplist-control="textbox-filter" data-group="data-group-1"
             data-name="my-filter-1" data-path=".summary" data-id="search" type="text" value=""
             placeholder="<?php echo $search_placeholder_text;?>" data-clear-btn-id="name-clear-btn"
             class="vf-form__input | vf-search__input" />
    </div>
    <button href="#eventsFilter" class="vf-search__button | vf-button vf-button--primary">
      <span class="vf-button__text">Search</span>
      <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
        <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
          <path d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z" fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
        </g>
      </svg>
    </button>
  </div>
</form>
