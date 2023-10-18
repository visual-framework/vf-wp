<?php

// Get all "Topic" terms
$country_terms = get_terms(
  array(
    'taxonomy'   => 'country',
    'hide_empty' => false,
  )
);


// Get active taxonomy filters
$country_selected = get_query_var('country');
if ( ! is_array($country_selected)) {
  $country_selected = array($country_selected);
}

// Get taxonomy labels
$country_labels = vf_wp_ells_country_labels();

// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=ambassadors');

?>
<form class="vf-stack vf-stack-400" action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="ambassadors">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

    <fieldset class="vf-form__fieldset vf-stack vf-stack--400 vf-u-margin__bottom--400">

      <legend class="vf-form__legend">Country</legend>
      <div class="vf-form__item vf-form__item--checkbox">
        <?php 
      $selected = empty($country) || empty($country[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-country" class="vf-form__checkbox"
          name="country" <?php echo $selected ?>>
        <label for="checkbox-country" class="vf-form__label">All</label>
      </div>
      <?php
	  		  $count = 2;
      foreach ($country_terms as $term) {
        $selected = in_array($term->slug, $country_selected) ? 'checked="checked"' : '';
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-topic-<?php echo $count; ?>"
          name="country[]" class="vf-form__checkbox" <?php echo $selected ?>>
        <label for="checkbox-topic-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        </label>
      </div>
      <?php 
	    $count++;
	  } ?>
    </fieldset>

    <button class="vf-button vf-button--primary vf-button--sm" type="submit">
      <?php esc_html_e('Apply filter', 'theme'); ?>
    </button>

    <a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url() . '/ambassadors'; ?>">
      <?php esc_html_e('Reset filters', 'theme'); ?>
    </a>
  </div>
</form>
