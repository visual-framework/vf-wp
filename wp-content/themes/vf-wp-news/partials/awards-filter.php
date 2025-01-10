<?php

// Get all "Type" terms
$type_terms = get_terms(
  array(
    'taxonomy'   => 'award-type',
    'hide_empty' => false,
  )
);

// Get all "Site" terms
$site_terms = get_terms(
  array(
    'taxonomy'   => 'award-site',
    'hide_empty' => false,
  )
);

// Get all "Unit" terms
$unit_terms = get_terms(
  array(
    'taxonomy'   => 'award-unit',
    'hide_empty' => false,
  )
);

$currentYear = date("Y");
$counterTyp = 1;
$counterLoc = 1;
$counterUni = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--800">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-unit">
    <legend class="vf-form__legend">Unit</legend>
    <?php
    foreach($unit_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="unit-<?php echo $counterUni; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".unit" data-group="awards" data-name="unit" data-or="unit"
        value="<?php echo esc_attr($term->name); ?>" data-id="unit-<?php echo esc_attr($term->slug); ?>"
        class="vf-form__checkbox checkboxFilter inputField">
      <label for="unit-<?php echo $counterUni; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        &nbsp;<span data-jplist-control="counter" data-group="awards" data-format="({count})"
          data-path=".unit-<?php echo esc_attr($term->slug); ?>" data-mode="static"
          data-name="counter-unit-<?php echo esc_attr($term->slug); ?>" data-filter-type="path"></span>
      </label>
    </div>
    <?php
      $counterUni++;
    }

    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-site">
    <legend class="vf-form__legend">EMBL Site</legend>
    <?php
    foreach($site_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="site-<?php echo $counterLoc; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".site" data-group="awards" data-name="site" data-or="site"
        value="<?php echo esc_attr($term->name); ?>" data-id="site-<?php echo esc_attr($term->slug); ?>"
        class="vf-form__checkbox checkboxFilter inputField">
      <label for="site-<?php echo $counterLoc; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        &nbsp;<span data-jplist-control="counter" data-group="awards" data-format="({count})"
          data-path=".site-<?php echo esc_attr($term->slug); ?>" data-mode="static"
          data-name="counter-site-<?php echo esc_attr($term->slug); ?>" data-filter-type="path"></span>
      </label>
    </div>
    <?php
      $counterLoc++;
    }

    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-type">
    <legend class="vf-form__legend">Type</legend>
    <?php
    foreach($type_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counterLoc; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="awards" data-name="type" data-or="type"
        value="<?php echo esc_attr($term->name); ?>" data-id="type-<?php echo esc_attr($term->slug); ?>"
        class="vf-form__checkbox checkboxFilter inputField">
      <label for="type-<?php echo $counterLoc; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
        &nbsp;<span data-jplist-control="counter" data-group="awards" data-format="({count})"
          data-path=".type-<?php echo esc_attr($term->slug); ?>" data-mode="static"
          data-name="counter-type-<?php echo esc_attr($term->slug); ?>" data-filter-type="path"></span>
      </label>
    </div>
    <?php
      $counterLoc++;
    }

    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-year">
    <legend class="vf-form__legend">Year</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="year-<?php echo $currentYear; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".year" data-group="awards" data-name="year" data-or="year" value="<?php echo $currentYear; ?>"
        data-id="year<?php echo $currentYear; ?>" class="vf-form__checkbox checkboxFilter inputField">
      <label for="year-<?php echo $currentYear; ?>" class="vf-form__label"><?php echo $currentYear; ?>
        &nbsp;<span data-jplist-control="counter" data-group="awards" data-format="({count})" data-path=".year-2024"
          data-mode="static" data-name="counter-current-year" data-filter-type="path"></span>
      </label>
    </div>
  </fieldset>
</form>

<style>
  .vf-form__label {
    font-size: 16px !important;
  }

  .vf-form__legend {
    font-size: 21px !important;
  }

</style>
