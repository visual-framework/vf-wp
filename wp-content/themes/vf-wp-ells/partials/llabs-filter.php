<?php

// Get all "Type" terms
$type_terms = get_terms(
  array(
    'taxonomy'   => 'llabs-type',
    'hide_empty' => false,
  )
);

// Get all "format" terms
$format_terms = get_terms(
  array(
    'taxonomy'   => 'llabs-format',
    'hide_empty' => false,
  )
);

// Get all "location" terms
$location_terms = get_terms(
  array(
    'taxonomy'   => 'llabs-location',
    'hide_empty' => false,
  )
);

// Get active taxonomy filters
$type_selected = get_query_var('llabs-type');
if ( ! is_array($type_selected)) {
  $type_selected = array($type_selected);
}
$format_selected = get_query_var('llabs-format');
if ( ! is_array($format_selected)) {
  $format_selected = array($format_selected);
}
$location_selected = get_query_var('llabs-location');
if ( ! is_array($location_selected)) {
  $location_selected = array($location_selected);
}

// Get taxonomy labels
$llabs_type_labels = vf_wp_ells_llabs_type_labels();
$llabs_format_labels = vf_wp_ells_llabs_format_labels();
$llabs_location_labels = vf_wp_ells_llabs_location_labels();

// Default empty date filter
$date_options = array();

// Get HTML for monthy archives
$yearly_archives = wp_get_archives(array(
  'type'      => 'yearly', # monthly for month and year archives
  'format'    => 'html',
  'echo'      => 0,
  'post_type' => 'learninglab'
));

// Attempt to get year/month filter
$year = (string) get_query_var('year');
# $month = str_pad(get_query_var('monthnum'), 2, '0', STR_PAD_LEFT);

// Use the `m` query filter (in case `s` search query is set)
$m = get_query_var('m');
if (strlen($m) === 6) {
  $year = substr($m, 0, 4);
#  $month = substr($m, 4, 2);
}

// to check permalinks structure '#m=([0-9]{4})([0-9]{1,2})#',

// Parse all year/month values from URLs to create date filter
if (preg_match_all(
  '#/([0-9]{4})#', # '#/([0-9]{4})/([0-9]{1,2})#' for month and year archive
  $yearly_archives, $matches, PREG_SET_ORDER
)) {

  foreach ($matches as $match) {
    $value = "{$match[1]}";
    $label = "{$match[1]}";
    $date_options[] = array(
      'value'    => $value,
      'label'    => $label,
      'selected' => $value == "{$year}"
    );
  }
}

// $archive = get_post_type_archive_link('document');
$archive = home_url('/?post_type=learninglab');

?>
<form class="vf-stack vf-stack-400" action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="learninglab">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

    <fieldset class="vf-form__fieldset vf-stack vf-stack--400">

      <legend class="vf-form__legend">Type</legend>
      <div class="vf-form__item vf-form__item--checkbox">
        <?php 
      $selected = empty($type_selected) || empty($type_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-llabs_type-1" class="vf-form__checkbox"
          name="learninglab_llabs_type" <?php echo $selected ?>>
        <label for="checkbox-llabs_type-1" class="vf-form__label">All</label>
      </div>
      <?php
	  		  $count = 2;
      foreach ($type_terms as $term) {
        $selected = in_array($term->slug, $type_selected) ? 'checked="checked"' : '';
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-type-<?php echo $count; ?>"
          name="llabs-type[]" class="vf-form__checkbox" <?php echo $selected ?>>
        <label for="checkbox-type-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        </label>
      </div>
      <?php 
	    $count++;
	  } ?>
    </fieldset>

    <fieldset class="vf-form__fieldset vf-stack vf-stack--400">

      <legend class="vf-form__legend">Format</legend>
      <div class="vf-form__item vf-form__item--checkbox">
        <?php 
      $selected = empty($format_selected) || empty($format_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-type-1" class="vf-form__checkbox" name="llabs-format"
          <?php echo $selected ?>>
        <label for="checkbox-type-1" class="vf-form__label">All</label>
      </div>
      <?php
	  		  $count = 2;
      foreach ($format_terms as $term) {

        $selected = in_array($term->slug, $format_selected) ? 'checked="checked"' : '';
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-format-<?php echo $count; ?>"
          name="llabs-format[]" class="vf-form__checkbox" <?php echo $selected ?>>
        <label for="checkbox-type-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        </label>
      </div>
      <?php 
	    $count++;
	  } ?>
    </fieldset>

    <fieldset class="vf-form__fieldset vf-stack vf-stack--400">

      <legend class="vf-form__legend">Location</legend>
      <div class="vf-form__item vf-form__item--checkbox">
        <?php 
      $selected = empty($location_selected) || empty($location_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-type-1" class="vf-form__checkbox" name="llabs-location"
          <?php echo $selected ?>>
        <label for="checkbox-type-1" class="vf-form__label">All</label>
      </div>
      <?php
	  		  $count = 2;
      foreach ($location_terms as $term) {

        $selected = in_array($term->slug, $location_selected) ? 'checked="checked"' : '';
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-location-<?php echo $count; ?>"
          name="llabs-location[]" class="vf-form__checkbox" <?php echo $selected ?>>
        <label for="checkbox-type-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        </label>
      </div>
      <?php 
	    $count++;
	  } ?>
    </fieldset>

    <button class="vf-button vf-button--primary vf-button--sm" type="submit">
      <?php esc_html_e('Apply filter', 'theme'); ?>
    </button>

    <a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url() . '/learninglab'; ?>">
      <?php esc_html_e('Reset filters', 'theme'); ?>
    </a>
  </div>
</form>
