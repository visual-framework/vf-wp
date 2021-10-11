<section
  class="vf-summary-container | embl-grid embl-grid--has-centered-content | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-background-color-ui--off-white vf-u-padding__top--500 vf-u-padding__bottom--500  vf-u-margin__bottom--800">

  <div class="vf-section-header">
    <a href="/internal-information/search" class="vf-section-header__heading vf-section-header__heading--is-link">Search
      <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
  </div>

  <div class="vf-section-content">
    <div>
      <form role="search" method="get" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar"
        action="<?php echo esc_url(home_url('/')); ?>">
        <div class="vf-sidebar__inner">
          <div class="vf-form__item | vf-search__item">
            <input type="search" class="vf-form__input | vf-search__input" placeholder="Enter your search term"
              value="<?php echo esc_attr(get_search_query()); ?>" name="s">
          </div>
          <div class="vf-form__item | vf-search__item">
            <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
            <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
              <option value="any" selected="">Everything</option>
              <option value="page" name="post_type[]">Pages</option>
              <option value="insites" name="post_type[]">Internal news</option>
              <option value="events" name="post_type[]">Events</option>
              <option value="people" name="post_type[]">People</option>
              <option value="documents" name="post_type[]">Documents</option>
            </select>
          </div>
          <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
            value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        </div>
      </form>
    </div>
  </div>
</section>
