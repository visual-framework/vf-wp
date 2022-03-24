<?php
$counter = 2;

// Get all "location" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'embl-location',
    'hide_empty' => false,
  )
);

// Get all "Topic" terms
$topic_terms = get_terms(
    array(
      'taxonomy'   => 'updates-topic',
      'hide_empty' => false,
    )
  );
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">EMBL site</legend>
  <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-1" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="all"
        data-id="location-all" class="vf-form__checkbox">
      <label for="location-1" class="vf-form__label">All EMBL Sites</label>
    </div>

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

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Topic</legend>
  <?php /*
  <div class="vf-form__item vf-form__item--checkbox" >
      <input id="topic-1" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path="default" data-group="data-group-1" data-name="topic" data-or="topic"
        value="all"
        data-id="topic-all" class="vf-form__checkbox" checked>
      <label for="topic-1" class="vf-form__label">All</label>
    </div>

    <?php
    foreach(array_slice($topic_terms,1) as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="topic-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".topic" data-group="data-group-1" data-name="topic" data-or="topic"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="topic<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="topic-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  */ ?>
  <div class="vf-form__item vf-stack">
  <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="data-group-1" >
  <option value="0" data-path="default" >All</option>
    <?php
    foreach($topic_terms as $term) {
      ?>
      <option  
        data-path=".<?php echo esc_attr($term->slug); ?>" 
        value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?> </option>
      <?php } ?>
    </select>
    </div>
  </fieldset>
</form>

<style>
  .vf-form__label {
    font-size: 16px;
  }
  .vf-form__select {
    font-size: 16px;
  }
  .vf-form__legend {
    font-size: 19px;
  }
  .vf-form__checkbox+.vf-form__label::before  {
    position: unset;
  }
</style>
