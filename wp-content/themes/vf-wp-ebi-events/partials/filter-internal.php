<?php
$current_year = date('Y') + 1; // 1 year ahead
$year_list = range(date('Y'), $current_year); // we have imported events only from 2016 onwards
$year_list = array_reverse($year_list);
$counter = 1;
$choices= get_field_object('field_61a125bb76e05');
$type_list = $choices['choices'];

?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <legend class="vf-form__legend">Refine by</legend>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
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
