<div>
  <form role="search" method="get" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item | vf-search__item">
        <input type="search" class="vf-form__input | vf-search__input"
          value="<?php echo esc_attr(get_search_query()); ?>" name="s" placeholder="Enter your search term">
      </div>
      <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
        value="<?php esc_attr_e('Search', 'vfwp'); ?>">
    </div>
  </form>
</div>
