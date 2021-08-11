<?php

// Get all "Topic" terms
$topic_terms = get_terms(
  array(
    'taxonomy'   => 'document_topic',
    'hide_empty' => false,
  )
);

// Get all "Type" terms
$type_terms = get_terms(
  array(
    'taxonomy'   => 'document_type',
    'hide_empty' => false,
  )
);

// Get active taxonomy filters
$topic_selected = get_query_var('document_topic');
if ( ! is_array($topic_selected)) {
  $topic_selected = array($topic_selected);
}
$type_selected = get_query_var('document_type');
if ( ! is_array($type_selected)) {
  $type_selected = array($type_selected);
}

// Get taxonomy labels
$topic_labels = vf_wp_document_topic_labels();
$type_labels = vf_wp_document_type_labels();

// Default empty date filter
$date_options = array();

// Get HTML for monthy archives
$yearly_archives = wp_get_archives(array(
  'type'      => 'yearly', # monthly for month and year archives
  'format'    => 'html',
  'echo'      => 0,
  'post_type' => 'document'
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
$archive = home_url('/?post_type=document');

?>
<form class="vf-stack vf-stack-400" action="<?php echo esc_url($archive); ?>" method="get">
  <div>

    <input type="hidden" name="post_type" value="document">

    <?php
    $search = trim(get_search_query());
    if ( ! empty($search)) {
    ?>
    <input type="hidden" name="s" value="<?php echo esc_attr($search); ?>">
    <?php } ?>

	  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" style="margin-top: 1rem;">

	  <label class="vf-form__label">Document topic</label>
	  <div class="vf-form__item vf-form__item--checkbox">
    <?php 
      $selected = empty($topic_selected) || empty($topic_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-topic-1" class="vf-form__checkbox" name="document_topic" <?php echo $selected ?>>
        <label for="checkbox-topic-1" class="vf-form__label"><?php echo esc_html($topic_labels['all_items']); ?></label>
		  </div>
		        <?php
	  		  $count = 2;
      foreach ($topic_terms as $term) {
        $selected = in_array($term->slug, $topic_selected) ? 'checked="checked"' : '';
      ?>
	  <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-topic-<?php echo $count; ?>" name="document_topic[]" class="vf-form__checkbox" <?php echo $selected ?>>		       
		  <label for="checkbox-topic-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      </label>
		</div>
      <?php 
	    $count++;
	  } ?>
	  </fieldset>

	  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" style="margin-top: 1rem;">

	  <label class="vf-form__label">Document type</label>
	  <div class="vf-form__item vf-form__item--checkbox">
    <?php 
      $selected = empty($type_selected) || empty($type_selected[0]) ? 'checked="checked"' : '';
    ?>
        <input type="checkbox" value="" id="checkbox-type-1" class="vf-form__checkbox" name="document_type" <?php echo $selected ?>>
        <label for="checkbox-type-1" class="vf-form__label"><?php echo esc_html($type_labels['all_items']); ?></label>
		</div>
		        <?php
	  		  $count = 2;
      foreach ($type_terms as $term) {

        $selected = in_array($term->slug, $type_selected) ? 'checked="checked"' : '';
      ?>
	  <div class="vf-form__item vf-form__item--checkbox">
        <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>" id="checkbox-type-<?php echo $count; ?>" name="document_type[]" class="vf-form__checkbox" <?php echo $selected ?>>		       
		  <label for="checkbox-type-<?php echo $count; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      </label>
		</div>
      <?php 
	    $count++;
	  } ?>
	  </fieldset>

	  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" style="margin-top: 1rem; margin-bottom: 1rem;">
	  <label class="vf-form__label">Publication year</label>

    <?php if ( ! empty($date_options)) { ?>
    <select class='vf-form__select' id='vf-form__select' name="m">
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
	 
	<a class="vf-button vf-button--sm vf-button--tertiary" href="<?php echo get_home_url(); ?>">
  <?php esc_html_e('Reset filters', 'theme'); ?>
</a>
  </div>
</form>
