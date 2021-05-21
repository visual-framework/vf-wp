<div>
  <form role="search" method="get" class="vf-form vf-form--search vf-form--search--mini | vf-sidebar vf-sidebar--end"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item | vf-search__item">
        <input type="search" class="vf-form__input | vf-search__input"
          value="<?php echo esc_attr(get_search_query()); ?>" name="s" placeholder="Enter your search term">
      </div>
      <button type="submit" class="vf-search__button | vf-button vf-button--primary">
      <span class="vf-button__text | vf-u-sr-only">Search</span>

      <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
        <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
          <path d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z" fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
        </g>
      </svg>

    </button>    </div>
  </form>
</div>
