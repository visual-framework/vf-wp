<form class="vf-stack vf-stack-400" action="<?php echo esc_url(home_url('/')); ?>" method="get">
  <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
  <fieldset class="vf-form__fieldset | vf-stack vf-stack--400">
    <legend class="vf-form__legend">Type</legend>
    <div class="vf-form__item vf-form__item--radio">
      <input type="radio" value="documents" name="post_type" id="4" class="vf-form__radio"
        <?php if($post_type == 'documents') {echo 'checked';} ?>>
      <label for="4" class="vf-form__label">Documents</label>
    </div>
    <div class="vf-form__item vf-form__item--radio">
      <input type="radio" value="events" name="post_type" id="5" class="vf-form__radio"
        <?php if($post_type == 'events') {echo 'checked';} ?>>
      <label for="5" class="vf-form__label">Events</label>
    </div>
    <div class="vf-form__item vf-form__item--radio">
      <input type="radio" value="insites" name="post_type" id="3" class="vf-form__radio"
        <?php if($post_type == 'insites') {echo 'checked';} ?>>
      <label for="3" class="vf-form__label">INsites</label>
    </div>
    <div class="vf-form__item vf-form__item--radio">
      <input type="radio" value="page" name="post_type" id="2" class="vf-form__radio"
        <?php if($post_type == 'page') {echo 'checked';} ?>>
      <label for="2" class="vf-form__label">Pages</label>
    </div>
    <div class="vf-form__item vf-form__item--radio">
      <input type="radio" value="people" name="post_type" id="1" class="vf-form__radio"
        <?php if($post_type == 'people') {echo 'checked';} ?>>
      <label for="1" class="vf-form__label">People</label>
    </div>
  </fieldset>
  <button class="vf-button vf-button--primary vf-button--sm" type="submit">
    <?php esc_html_e('Apply', 'theme'); ?>
  </button>
  <a class="vf-button vf-button--sm vf-button--tertiary" style="display: block;
    max-width: fit-content;" href="<?php echo get_home_url() . '/?s=&post_type=any'; ?>">
    <?php esc_html_e('Reset', 'theme'); ?>
  </a>
</form>
