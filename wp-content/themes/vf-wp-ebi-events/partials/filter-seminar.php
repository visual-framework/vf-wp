<?php
$counter = 1;
$choices= get_field_object('field_619cc059aeafd');
$type_list = $choices['choices'];
$location_choices= get_field_object('field_619cc059ae8d7');
$location_list = $location_choices['choices'];

?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <legend class="vf-form__legend">Refine by</legend>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" style="margin-right: 15px;">
    <label class="vf-form__legend">Type</label>
    <?php
    foreach($type_list as $type_key => $type_item) {

      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input type="checkbox" id="checkbox-type-<?php echo $counter; ?>" class="vf-form__checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".jplist-event-type"
             data-group="data-group-1"
             data-name="type"
             data-or="eventtypes"
             data-id="type-<?php echo $type_key; ?>"
             value="<?php echo $type_key; ?>">
      <label for="checkbox-type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $type_item; ?>
        <div>&nbsp;<span
                  data-jplist-control="counter"
                  data-group="data-group-1"
                  data-name="counter-type-filter-<?php echo $counter; ?>"
                  data-format="({count})"
                  data-value="<?php echo $type_key; ?>"
                  data-path=".type_<?php echo $type_key; ?>"
                  data-mode="dynamic"
                  data-filter-type="path">

         </span>
        </div>
      </label>
    </div>

    <?php
      $counter++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__legend">Location</label>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-1" type="checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".jplist-event-location" data-group="data-group-1" data-name="location" data-or="location"
             value="Virtual" data-id="location-Virtual"
             class="vf-form__checkbox">
      <label for="location-1" class="vf-form__label">Virtual
        <div>&nbsp;<span
                  data-jplist-control="counter"
                  data-group="data-group-1"
                  data-name="counter-location-filter-1"
                  data-format="({count})"
                  data-value="Virtual"
                  data-path=".location_Virtual"
                  data-mode="static"
                  data-filter-type="path">

         </span>
        </div>
      </label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-2" type="checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".jplist-event-location" data-group="data-group-1" data-name="location" data-or="location"
             value="EMBL-EBI" data-id="location-EMBL-EBI"
             class="vf-form__checkbox">
      <label for="location-2" class="vf-form__label">EMBL-EBI
        <div>&nbsp;<span
                  data-jplist-control="counter"
                  data-group="data-group-1"
                  data-name="counter-location-filter-2"
                  data-format="({count})"
                  data-value="EMBL-EBI"
                  data-path=".location_EMBL-EBI"
                  data-mode="static"
                  data-filter-type="path">

         </span>
        </div>
      </label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-3" type="checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".jplist-event-location" data-group="data-group-1" data-name="location" data-or="location"
             value="Other" data-id="location-Other"
             class="vf-form__checkbox">
      <label for="location-3" class="vf-form__label">Other
        <div>&nbsp;<span
                  data-jplist-control="counter"
                  data-group="data-group-1"
                  data-name="counter-location-filter-3"
                  data-format="({count})"
                  data-value="Other"
                  data-path=".location_Other"
                  data-mode="static"
                  data-filter-type="path">

         </span>
        </div>
      </label>
    </div>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__legend">Event year</label>
    <?php
    foreach($year_list as $year_key => $year_item) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".jplist-event-year" data-group="data-group-1" data-name="yeardata" data-or="year"
        value="<?php echo $year_item; ?>" data-id="name<?php echo $counter; ?>-<?php echo $year_item; ?>"
        class="vf-form__checkbox">
      <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $year_item; ?>
        <div>&nbsp;<span
                  data-jplist-control="counter"
                  data-group="data-group-1"
                  data-name="counter-year-filter-<?php echo $counter; ?>"
                  data-format="({count})"
                  data-value="<?php echo $year_item; ?>"
                  data-path=".year_<?php echo $year_item; ?>"
                  data-mode="static"
                  data-filter-type="path">

         </span>
        </div>
      </label>
    </div>
    <?php
      $counter++;
    }
    ?>

  </fieldset>


</form>
