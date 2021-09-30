<?php
$counter = 1;

// Get all "location" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'event-location',
    'hide_empty' => false,
  )
);

?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">EMBL site</legend>
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
