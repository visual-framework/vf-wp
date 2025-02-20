<?php
$cities = [
  'Barcelona',
  'EBI (Hinxton)',
  'Grenoble',
  'Hamburg',
  'Heidelberg',
  'Rome',
  'Other'
]; ?>

<form class="vf-stack vf-stack--400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend">Location</legend>
      <?php foreach ($cities as $city):
        $cityFormatted = strtolower(str_replace([' ', '(', ')'], ['-', '', ''], $city)); ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input name="location[]" value="<?php echo $cityFormatted; ?>" id="location-<?php echo $cityFormatted; ?>" class="vf-form__checkbox" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="name" data-or="name"
>
       <label for="location-<?php echo $cityFormatted; ?>" class="vf-form__label"><?php echo esc_html($city); ?>&nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".location-<?php echo esc_attr($cityFormatted); ?>"
      data-mode="static"
      data-name="counter-location-<?php echo esc_attr($cityFormatted); ?>"
      data-filter-type="path"></span></label>

      </div>
      <?php endforeach; ?>
  </fieldset>
</form>