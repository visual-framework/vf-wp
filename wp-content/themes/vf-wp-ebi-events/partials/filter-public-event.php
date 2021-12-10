<?php

$counter = 1;
$type_choices = get_field_object('field_619cc059aeb94');
$type_list = $type_choices['choices'];
$location_choices = get_field_object('field_619cc059ae8d7');
$location_list = $location_choices['choices'];
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Type</label>
    <?php
    foreach($type_list as $type_key => $type_item) {
      $events_count = get_posts(array(
        'post_type' => 'events', 
        'numberposts' => -1,
        'meta_query' => [
          [
            'key' => 'vf_event_start_date',
            'value' => $current_date,
            'compare' => '>',
            'type' => 'numeric',
          ],
          [
            'key' => 'vf_event_start_date',
            'value' => date('Ymd', strtotime('now')),
            'type' => 'numeric',
            'compare' => '>',
          ],
          [
            'key' => 'vf_event_public_subtype',
            'value' => $type_key,
          ],
        ],
      ));
      $events_number = count($events_count);
      
      //disable filters with value 0
      if ($events_number == '0') {
        $disabled = 'disabled';
      }
      else {
        $disabled = '';
      }
      ?>

    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="data-group-1" data-name="typedata" data-or="type"
        value="<?php echo $type_item; ?>" data-id="name<?php echo $counter; ?>-<?php echo $type_item; ?>"
        class="vf-form__checkbox" <?php echo $disabled; ?>>
      <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $type_item; ?> (<?php echo $events_number; ?>)</label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Location</label>
      <div class="vf-form__item vf-form__item--checkbox">
        <input id="location-1" type="checkbox" data-jplist-control="checkbox-text-filter"
               data-path=".location" data-group="data-group-1" data-name="locationdata" data-or="location"
               value="Virtual" data-id="name1-Virtual"
               class="vf-form__checkbox">
        <label for="location-1" class="vf-form__label">Virtual</label>
      </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-2" type="checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".location" data-group="data-group-2" data-name="locationdata" data-or="location"
             value="EMBL-EBI" data-id="name2-EMBL-EBI"
             class="vf-form__checkbox">
      <label for="location-2" class="vf-form__label">United Kingdom</label>
    </div>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-3" type="checkbox" data-jplist-control="checkbox-text-filter"
             data-path=".location" data-group="data-group-3" data-name="locationdata" data-or="location"
             value="Other" data-id="name3-Other"
             class="vf-form__checkbox">
      <label for="location-3" class="vf-form__label">Other</label>
    </div>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Event year</label>
    <?php
    foreach($year_list as $year_key => $year_item) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".vf-summary__date" data-group="data-group-1" data-name="yeardata" data-or="year"
        value="<?php echo $year_item; ?>" data-id="name<?php echo $counter; ?>-<?php echo $year_item; ?>"
        class="vf-form__checkbox">
      <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo $year_item; ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset | vf-stack vf-stack--400">
    <label class="vf-form__label">Open</label>
    <div class="vf-form__item vf-form__item--checkbox">
      <input type="checkbox" class="vf-form__checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".jplist-event-registration" data-group="data-group-1" data-order="asc" data-name="reg1"
        data-type="datetime" data-datetime-format="{day} {month}, {year} {hour}:{min}" name="reg1" id="reg-id1" />
      <label for="reg-id1" class="vf-form__label">Registration</label>
      <input type="checkbox" class="vf-form__checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".jplist-event-abstract" data-group="data-group-1" data-order="asc" data-name="sub1"
        data-type="datetime" data-datetime-format="{day} {month}, {year} {hour}:{min}" name="sub1" id="sub-id1" />
      <label for="sub-id1" class="vf-form__label vf-u-margin__top--400">Abstract submission</label>
    </div>
  </fieldset>

</form>
