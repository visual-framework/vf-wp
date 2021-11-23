<?php
$current_year = date('Y'); // 1 year ahead
$year_list = range(2016, $current_year); // we have imported events only from 2016 onwards
$year_list = array_reverse($year_list);
$counter = 1;
$choices= get_field_object('field_619cc059aeafd');
$type_list = $choices['choices'];
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400" >
<fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Type</label>
    <?php
    foreach($type_list as $type_key => $type_item) {
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input
          id="type-<?php echo $counter; ?>"
          type="checkbox"
          data-jplist-control="checkbox-text-filter"
          data-path=".type"
          data-group="data-group-1"
          data-name="typedata"
          data-or="type"
          value="<?php echo $type_item; ?>"
          data-id="name<?php echo $counter; ?>-<?php echo $type_item; ?>"
          class="vf-form__checkbox" >
        <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $type_item; ?></label>
      </div>
      <?php
      $counter++;
    }
    ?>

  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Event year</label>
    <?php
    foreach($year_list as $year_key => $year_item) {
      ?>
      <div class="vf-form__item vf-form__item--checkbox">
        <input
          id="type-<?php echo $counter; ?>"
          type="checkbox"
          data-jplist-control="checkbox-text-filter"
          data-path=".vf-summary__date"
          data-group="data-group-1"
          data-name="yeardata"
          data-or="year"
          value="<?php echo $year_item; ?>"
          data-id="name<?php echo $counter; ?>-<?php echo $year_item; ?>"
          class="vf-form__checkbox" >
        <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $year_item; ?></label>
      </div>
      <?php
      $counter++;
    }
    ?>

  </fieldset>

</form>
