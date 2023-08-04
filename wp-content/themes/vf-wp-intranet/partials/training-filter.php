<?php

// Get all "organiser" terms
$organiser_terms = get_terms(
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

$currentYear = date("Y");

$counterCat = 1;
$counterLoc = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-organiser">
    <legend class="vf-form__legend">Provider</legend>
    <?php
    foreach($organiser_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="organiser-<?php echo $counterCat; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".organiser" data-group="data-group-1" data-name="organiser" data-or="organiser"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="organiser<?php echo $counterCat; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="organiser-<?php echo $counterCat; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".organiser-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-organiser-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-location">
    <legend class="vf-form__legend">Year</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="year-<?php echo $currentYear; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".year" data-group="data-group-1" data-name="year" data-or="year"
        value="<?php echo $currentYear; ?>"
        data-id="year<?php echo $currentYear; ?>" class="vf-form__checkbox">
      <label for="year-<?php echo $currentYear; ?>" class="vf-form__label"><?php echo $currentYear; ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".year-2023"
      data-mode="static"
      data-name="counter-current-year"
      data-filter-type="path"></span>
    </label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="year-<?php echo $currentYear + 1; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".year" data-group="data-group-1" data-name="year" data-or="year"
        value="<?php echo $currentYear + 1; ?>"
        data-id="year<?php echo $currentYear + 1; ?>" class="vf-form__checkbox">
      <label for="year-<?php echo $currentYear + 1; ?>" class="vf-form__label"><?php echo $currentYear + 1; ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".year-2024"
      data-mode="static"
      data-name="counter-next-year"
      data-filter-type="path"></span>
    </label>
    </div>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-location">
    <legend class="vf-form__legend">Location</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-7" type="checkbox" data-jplist-control="checkbox-text-filter" data-path=".location" data-group="data-group-1" data-name="location" data-or="location" value="Online" data-id="location6-online" class="vf-form__checkbox">
      <label for="location-7" class="vf-form__label">Online&nbsp;
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
        data-id="location<?php echo $counterLoc; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
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

</form>