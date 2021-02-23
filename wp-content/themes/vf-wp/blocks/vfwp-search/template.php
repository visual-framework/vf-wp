<style>
  .vf-search--inline .vf-search__input {
    min-width: 300px;
  }

  .vf-search--inline .vf-form__select {
    padding: 8px 12px;
  }

  .vf-search--inline .vf-search__item:not(:first-child) {
    padding-left: 10px;
  }

  .vf-search--inline .vf-search__button {
    top: -5px;
  }

</style>
<div>
  <form role="search" method="get" class="vf-form | vf-search vf-search--inline"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-form__item | vf-search__item">
      <?php if (get_field('search_type') == 'default') { ?>
      <input type="search" class="vf-form__input | vf-search__input" value="<?php echo esc_attr(get_search_query()); ?>"
        name="s" placeholder="Enter your search term">
    </div>
    <?php }
    else if (get_field('search_type') == 'filter') { ?>
    <input type="search" class="vf-form__input | vf-search__input" value="<?php echo esc_attr(get_search_query()); ?>"
      name="s" placeholder="Enter your search term">
</div>
<div class="vf-form__item | vf-search__item">
  <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
  <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
    <option value="all" selected="">Everything</option>
    <option value="page" name="post_type[]">Pages</option>
    <option value="post" name="post_type[]">Posts</option>
  </select>
</div>

<?php }?>
<input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
  value="<?php esc_attr_e('Search', 'vfwp'); ?>">
</form>
</div>
