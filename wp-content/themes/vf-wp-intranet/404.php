<?php

get_header();

?>
<section class="vf-intro">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading ">Error: 404</h1>
    <p class="vf-lede">We’re sorry - we can’t find the page or file you requested.</p>
    <p class="vf-intro__text">It may have been removed, had its name changed, or be temporarily unavailable.</p>
    <p class="vf-intro__text">You might try searching for it.</p>
    <form role="search" method="get"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end | vf-u-margin__bottom--600"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <input type="search" class="vf-form__input" placeholder="Enter your search term"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <div class="vf-form__item | vf-search__item" style="display: none">
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
        <input type="submit" class="vf-search__button | vf-button vf-button--primary"
          value="<?php esc_attr_e('Search', 'vfwp'); ?>">
      </div>
    </form>
    <p class="vf-intro__text">If you need assistance, please <a href="https://www.embl.org/contact/websupport/">contact
        web support</a>.</p>
  </div>
</section>
<?php

get_footer();

?>
