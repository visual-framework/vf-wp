<?php
$counter = 2;

// Get all "location" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'embl-location',
    'hide_empty' => false,
  )
);

?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">EMBL site</legend>
  <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-1" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="name1" data-or="name1"
        value="all"
        data-id="name1-all" class="vf-form__checkbox">
      <label for="location-1" class="vf-form__label">All EMBL Sites</label>
    </div>

    <?php
    foreach(array_slice($embl_location_terms,1) as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="name1" data-or="name1"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="name<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
  <!-- <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Type</legend>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-1" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path="default" data-group="data-group-1" data-name="type" data-or="type"
        value="All"
        data-id="type-all" class="vf-form__checkbox" checked>
      <label for="type-1" class="vf-form__label">All</label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-2" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="data-group-1" data-name="type" data-or="type"
        value="Internal news"
        data-id="type-news" class="vf-form__checkbox">
      <label for="type-2" class="vf-form__label">Internal news</label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-3" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="data-group-1" data-name="type" data-or="type"
        value="Important updates"
        data-id="type-updates" class="vf-form__checkbox">
      <label for="type-3" class="vf-form__label">Important updates</label>
    </div>

  </fieldset> -->
</form>
