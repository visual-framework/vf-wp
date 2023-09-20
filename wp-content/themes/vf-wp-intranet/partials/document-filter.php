<?php
$counter = 1;

// Get all "location" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'embl-location',
    'hide_empty' => false,
  )
);
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <div class="vf-form__item vf-stack">
  <legend class="vf-form__legend">Sort by:</legend>
    <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-sort" data-group="data-group-1"
      data-name="name1">
      <option value="1" data-path=".added" data-order="desc" data-type="datetime" selected>Recently added</option>
      <option value="2" data-path=".update" data-order="desc" data-type="datetime">Recently updated</option>
    </select>
  </div>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">EMBL site</legend>
  <p class="vf-form__helper">Applicable to:</p>
  <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-all" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="all"
        data-id="location-all" class="vf-form__checkbox">
      <label for="location-all" class="vf-form__label">All EMBL sites</label>
    </div>
    <p class="vf-form__helper">Specific to:</p>
    <?php
    foreach(array_slice($embl_location_terms,1) as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="location<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
</form>
