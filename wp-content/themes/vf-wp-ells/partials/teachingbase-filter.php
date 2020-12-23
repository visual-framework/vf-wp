<?php

// Get all "Topic" terms
$age_group_terms = get_terms(
  array(
    'taxonomy'   => 'age-group',
    'hide_empty' => false,
  )
);

// Get all "Type" terms
$topic_area_terms = get_terms(
  array(
    'taxonomy'   => 'topic-area',
    'hide_empty' => false,
  )
);

// Get active taxonomy filters
$age_selected = get_query_var('age-group');
if ( ! is_array($age_selected)) {
  $age_selected = array($age_selected);
}
$topic_selected = get_query_var('topic-area');
if ( ! is_array($topic_selected)) {
  $topic_selected = array($topic_selected);
}

// Get taxonomy labels
$age_group_labels = vf_wp_ells_age_group_labels();
$topic_area_labels = vf_wp_ells_topic_area_labels();

// Default empty date filter
$date_options = array();

// Get HTML for monthy archives
$yearly_archives = wp_get_archives(array(
  'type'      => 'yearly', # monthly for month and year archives
  'format'    => 'html',
  'echo'      => 0,
  'post_type' => 'teachingbase'
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

/*
For month and year archive

  foreach ($matches as $match) {
    $value = "{$match[1]}{$match[2]}";
    $label = date('F Y', strtotime("{$match[1]}-{$match[2]}-01"));
    $date_options[] = array(
      'value'    => $value,
      'label'    => $label,
      'selected' => $value == "{$year}{$month}"
    );
  }
  */

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
$archive = home_url('/?post_type=teachingbase');

?>
<form action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="teachingbase">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

	  <fieldset class="vf-form vf-form__fieldset">

	  <legend class="vf-form__legend">Age group</legend>
	  <div class="vf-form__item vf-form__item--checkbox">
    <?php 
      $selected = empty($age_selected) || empty($age_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-age_group-1" class="vf-form__checkbox" name="teachingbase_age_group" <?php echo $selected ?>>
        <label for="checkbox-age_group-1" class="vf-form__label">All</label>
		  </div>
		        <?php
	  		  $count = 2;
      foreach ($age_group_terms as $term) {
        $selected = in_array($term->slug, $age_selected) ? 'checked="checked"' : '';
      ?>
	  <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-topic-<?php echo $count; ?>" name="age-group[]" class="vf-form__checkbox" <?php echo $selected ?>>		       
		  <label for="checkbox-topic-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      </label>
		</div>
      <?php 
	    $count++;
	  } ?>
	  </fieldset>

	  <fieldset class="vf-form vf-form__fieldset">

	  <legend class="vf-form__legend">Topic area</legend>
	  <div class="vf-form__item vf-form__item--checkbox">
    <?php 
      $selected = empty($topic_selected) || empty($topic_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-type-1" class="vf-form__checkbox" name="topic-area" <?php echo $selected ?>>
        <label for="checkbox-type-1" class="vf-form__label">All</label>
		</div>
		        <?php
	  		  $count = 2;
      foreach ($topic_area_terms as $term) {

        $selected = in_array($term->slug, $topic_selected) ? 'checked="checked"' : '';
      ?>
	  <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-type-<?php echo $count; ?>" name="topic-area[]" class="vf-form__checkbox" <?php echo $selected ?>>		       
		  <label for="checkbox-type-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      </label>
		</div>
      <?php 
	    $count++;
	  } ?>
	  </fieldset>

	  <fieldset class="vf-form vf-form__fieldset">
	  <legend class="vf-form__legend">Publication year</legend>

    <?php if ( ! empty($date_options)) { ?>
    <select class='vf-form__select' id='vf-form__select' name="m" style="padding: 3px 4px;">
      <option value=""><?php echo esc_attr( __( 'Year' ) ); ?></option>
      <?php
      foreach ($date_options as $date) {
        $selected = $date['selected'] ? 'selected="selected"' : '';
      ?>
      <option value="<?php echo esc_attr($date['value']); ?>" <?php echo $selected ?>>
        <?php echo esc_html($date['label']); ?>
      </option>
      <?php } ?>
    </select>
    <?php } ?>
	  </fieldset>

    <button class="vf-button vf-button--primary vf-button--sm" type="submit">
      <?php esc_html_e('Apply filter', 'theme'); ?>
    </button>
	 
	<a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url() . '/teachingbase'; ?>">
  <?php esc_html_e('Reset filters', 'theme'); ?>
</a>
  </div>
</form>
