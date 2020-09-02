<form role="search" method="get" class="vf-form  | vf-search vf-search--inline" action="<?php echo esc_url(home_url('/')); ?>">
  <div class="vf-form__item | vf-search__item">
    <input type="search" class="vf-form__input | vf-search__input" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
  </div>
  <input type="submit" class="vf-search__button | vf-button vf-button--primary" value="<?php esc_attr_e('Search', 'vfwp'); ?>" placeholder="Enter your search terms">
</form>




