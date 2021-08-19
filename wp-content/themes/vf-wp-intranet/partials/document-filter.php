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
    <label class="vf-form__label" for="vf-form__select">Sort by:</label>
    <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-sort" data-group="data-group-1"
      data-name="name1">
      <option value="1" data-path=".added" data-order="desc" data-type="datetime" selected>Recently added</option>
      <option value="2" data-path=".update" data-order="desc" data-type="datetime">Recently updated</option>
    </select>
  </div>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">EMBL site</label>
    <?php
    foreach($embl_location_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="name" data-or="name"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="name<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
</form>
