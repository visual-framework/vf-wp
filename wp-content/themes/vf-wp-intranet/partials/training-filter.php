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



$counterCat = 1;
$counterLoc = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-organiser">
    <legend class="vf-form__legend">Organiser</legend>
    <?php
    foreach($organiser_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="organiser-<?php echo $counterCat; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".organiser" data-group="data-group-1" data-name="organiser" data-or="organiser"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="organiser<?php echo $counterCat; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="organiser-<?php echo $counterCat; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-location">
    <legend class="vf-form__legend">Location</legend>
    <?php
    foreach($location_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counterLoc; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="location<?php echo $counterLoc; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="location-<?php echo $counterLoc; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counterLoc++;
    }
    ?>
  </fieldset>

</form>