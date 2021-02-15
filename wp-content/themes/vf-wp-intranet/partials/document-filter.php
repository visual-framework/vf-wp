<?php

// Get all "Topic" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'embl-location',
    'hide_empty' => false,
  )
);
  
  // Get active taxonomy filters
  $embl_location_selected = get_query_var('embl-location');
  if ( ! is_array($embl_location_selected)) {
      $embl_location_selected = array($embl_location_selected);
    }
    

// Get taxonomy labels
$location_labels = vf_wp_intranet_location_labels();


// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=documents');

?>
<form action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="documents">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

    <fieldset class="vf-form vf-form__fieldset">

      <legend class="vf-form__legend">EMBL Location</legend>
      <div class="vf-form__item vf-form__item--checkbox">
        <?php 
      $selected = empty($embl_location_selected) || empty($embl_location_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-embl_location-1" class="vf-form__checkbox"
          name="documents_embl_location" <?php echo $selected ?>>
        <label for="checkbox-age_group-1" class="vf-form__label">All</label>
      </div>
      <?php
	  		  $count = 2;
      foreach ($embl_location_terms as $term) {
        $selected = in_array($term->slug, $embl_location_selected) ? 'checked="checked"' : '';
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-topic-<?php echo $count; ?>"
          name="embl-location[]" class="vf-form__checkbox" <?php echo $selected ?>>
        <label for="checkbox-embl-location-<?php echo $count; ?>"
          class="vf-form__label"><?php echo esc_html($term->name); ?>
        </label>
      </div>
      <?php 
	    $count++;
	  } ?>
    </fieldset>


      <button class="vf-button vf-button--primary vf-button--sm" type="submit">
        <?php esc_html_e('Apply filter', 'theme'); ?>
      </button>

      <a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url() . '/insites'; ?>">
        <?php esc_html_e('Reset filters', 'theme'); ?>
      </a>
  </div>
</form>
