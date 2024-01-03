<?php

// Get all "organiser" terms
$provider_terms = get_terms(
  array(
    'taxonomy'   => 'training-organiser',
    'hide_empty' => false,
  )
);

// Get all "Location" terms
$location_terms = get_terms(
  array(
    'taxonomy'   => 'event-location',
    'hide_empty' => false,
  )
);
unset($location_terms[5]);

$category_terms = ['Data science', 'Professional development', 'Workplace'];
$fee_terms = ['Paid', 'Free'];
$status_terms = ['Open', 'Closed'];

$currentYear = date("Y");

$counterCat = 1;
$counterLoc = 1;
$counterPro = 1;
$counterStatus = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--800">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-category">
    <legend class="vf-form__legend">Category</legend>
    <?php
    foreach($category_terms as $cat) {
      $catSlug = strtolower(str_replace(' ', '_', $cat));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="category-<?php echo $catSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category-<?php echo $catSlug; ?>" data-group="data-group-1" data-name="category" data-or="category"
        value="<?php echo esc_attr($cat); ?>"
        data-id="category-<?php echo esc_attr($catSlug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="category-<?php echo $catSlug; ?>" class="vf-form__label"><?php echo esc_html($cat); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".category-<?php echo esc_attr($catSlug); ?>"
      data-mode="static"
      data-name="counter-category-<?php echo esc_attr($catSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-year">
    <legend class="vf-form__legend">Year</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="year-<?php echo $currentYear + 1; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".year" data-group="data-group-1" data-name="year" data-or="year"
        value="<?php echo $currentYear + 1; ?>"
        data-id="year<?php echo $currentYear + 1; ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="year-<?php echo $currentYear + 1; ?>" class="vf-form__label"><?php echo $currentYear + 1; ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".year-2025"
      data-mode="static"
      data-name="counter-next-year"
      data-filter-type="path"></span>
    </label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="year-<?php echo $currentYear; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".year" data-group="data-group-1" data-name="year" data-or="year"
        value="<?php echo $currentYear; ?>"
        data-id="year<?php echo $currentYear; ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="year-<?php echo $currentYear; ?>" class="vf-form__label"><?php echo $currentYear; ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".year-2024"
      data-mode="static"
      data-name="counter-current-year"
      data-filter-type="path"></span>
    </label>
    </div>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-location">
    <legend class="vf-form__legend">Location</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-7" type="checkbox" data-jplist-control="checkbox-text-filter" data-path=".location" data-group="data-group-1" data-name="location" data-or="location" value="Online" data-id="location6-online" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-7" class="vf-form__label">Online &nbsp;
        <span data-jplist-control="counter" data-group="data-group-1" data-format="({count})" data-path=".location-online" data-mode="static" data-name="counter-location-online" data-filter-type="path"></span>
    </label>
    </div>
    <?php
    foreach($location_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counterLoc; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="location-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-<?php echo $counterLoc; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".location-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-location-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterLoc++;
    }

    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-status">
    <legend class="vf-form__legend">Status</legend>
    <?php
    foreach($status_terms as $status) {
      $statusSlug = strtolower(str_replace(' ', '_', $status));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="status-<?php echo $statusSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".status-<?php echo $statusSlug; ?>" data-group="data-group-1" data-name="status" data-or="status"
        value="<?php echo esc_attr($statusSlug); ?>"
        data-id="status-<?php echo esc_attr($statusSlug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="status-<?php echo $statusSlug; ?>" class="vf-form__label"><?php echo esc_html($status); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".status-<?php echo esc_attr($statusSlug); ?>"
      data-mode="static"
      data-name="counter-status-<?php echo esc_attr($statusSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterStatus++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-provider">
    <legend class="vf-form__legend">Provider</legend>
    <?php
    foreach($provider_terms as $term) {
      if ($term->name === 'IT Security' || $term->name === 'Ethics Academy' || $term->name === 'Data Protection') {
        continue;
    }
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="provider-<?php echo$term->slug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".provider-<?php echo$term->slug; ?>" data-group="data-group-1" data-name="provider" data-or="provider"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="provider-<?php echo$term->slug; ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="provider-<?php echo$term->slug; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span style="flex-basis: 20%;"
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".provider-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-provider-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterPro++;
    }
    ?>
  </fieldset>
  <?php /*
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-fee">
    <legend class="vf-form__legend">Fee</legend>
    <?php
    foreach($fee_terms as $fee) {
      $feeSlug = strtolower(str_replace(' ', '_', $fee));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="fee-<?php echo $feeSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".fee-<?php echo $feeSlug; ?>" data-group="data-group-1" data-name="fee" data-or="fee"
        value="<?php echo esc_attr($feeSlug); ?>"
        data-id="fee-<?php echo esc_attr($feeSlug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="fee-<?php echo $feeSlug; ?>" class="vf-form__label"><?php echo esc_html($fee); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".fee-<?php echo esc_attr($feeSlug); ?>"
      data-mode="static"
      data-name="counter-fee-<?php echo esc_attr($feeSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterFee++;
    }
    ?>
  </fieldset>
  */ ?>
</form>
<style>
  .vf-form__label {
    font-size: 16px !important;
}
.vf-form__legend {
    font-size: 21px !important;
}
</style>