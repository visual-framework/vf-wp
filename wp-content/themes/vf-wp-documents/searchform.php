<form role="search" method="get" class="vf-form | vf-search vf-search--inline" action="<?php echo esc_url(home_url('/')); ?>">

  <input type="hidden" name="post_type" value="document">
  <?php
  /*
  <input type="hidden" name="document_topics" value="<?php echo esc_attr(get_query_var('document_topics')); ?>">
  <input type="hidden" name="document_types" value="<?php echo esc_attr(get_query_var('document_types')); ?>">
  */
    ?>
  <div class="vf-form__item | vf-search__item">
    <label class="vf-form__label vf-sr-only | vf-search__label" for="s"><?php esc_html_e('Search for:', 'vfwp'); ?></label>
    <input type="search" class="vf-form__input | vf-search__input" value="<?php esc_attr_e(get_search_query()); ?>" name="s">
  </div>
  <button type="submit" class="vf-search__button | vf-button vf-button--primary">
    <?php esc_html_e('Search', 'vfwp'); ?>
  </button>
</form>
