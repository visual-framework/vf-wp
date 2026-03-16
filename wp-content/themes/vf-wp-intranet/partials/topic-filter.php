<?php
$counter = 2;

// Get the current post type
$post_type = get_post_type();

// Check if the post type is 'events'
if ($post_type == 'events') {
    // Set the counter to 1 and get terms for 'event-location'
    $counter = 1;
    $taxonomy = 'event-location';
    $legend_text = 'Location';  // For events, we use 'Location'
    $helper_text_1 = '';  // Remove "Applicable to" for events
    $helper_text_2 = '';  // Remove "Specific to" for events
} else {
    // Use the default 'embl-location' taxonomy for other post types
    $taxonomy = 'embl-location';
    $legend_text = 'EMBL site';  // For other post types, use 'EMBL site'
    $helper_text_1 = 'Applicable to:';  // Standard helper text
    $helper_text_2 = 'Specific to:';  // Standard helper text
}

// Get terms based on the selected taxonomy
$location_terms = get_terms(
  array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
  )
);
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
<fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend"><?php echo esc_html($legend_text); ?></legend>

  <?php if ($helper_text_1) : ?>
    <p class="vf-form__helper"><?php echo esc_html($helper_text_1); ?></p>
  <?php endif; ?>

  <?php if ($post_type !== 'events') : ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-all" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="all"
        data-id="location-all" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-all" class="vf-form__label">All EMBL sites
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".location-all"
      data-mode="static"
      data-name="counter-category-all"
      data-filter-type="path"></span>
      </label>
    </div>
  <?php endif; ?>

  <?php if ($helper_text_2) : ?>
    <p class="vf-form__helper"><?php echo esc_html($helper_text_2); ?></p>
  <?php endif; ?>

  <?php
  // For events, show all terms (without skipping the first one)
  if ($post_type == 'events') {
      foreach ($location_terms as $term) {
  ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="location<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".location-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-location-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span></label>
    </div>
    <?php
        $counter++;
      }
  } else {
      // For non-events post types, exclude the first term
      foreach (array_slice($location_terms, 1) as $term) {
  ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="location" data-or="location"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="location<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-1"
      data-format="({count})"
      data-path=".location-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-location-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span></label>
    </div>
    <?php
        $counter++;
      }
  }
  ?>
</fieldset>
</form>
