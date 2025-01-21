<?php
$counter = 1;

// Get all "location" terms
$embl_location_terms = get_terms(
  array(
    'taxonomy'   => 'event-location',
    'hide_empty' => false,
  )
);

// Get all "topic" terms
$event_topics_terms = get_terms(
  array(
    'taxonomy'   => 'events-topic',
    'hide_empty' => false,
  )
);

?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Location</legend>
    <?php
    foreach($embl_location_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="location-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".location" data-group="data-group-1" data-name="name" data-or="name"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="name<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="location-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>&nbsp;<span 
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
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Topic</legend>
  <div class="vf-form__item vf-stack">
  <select class="vf-form__select inputLive" id="vf-form__select" data-jplist-control="select-filter" data-group="data-group-1">
  <option value="0" data-path="default">All</option>
  <?php
foreach ($event_topics_terms as $term) {
  $term_posts_query = new WP_Query([
      'post_type' => 'events',
      'tax_query' => [
          [
              'taxonomy' => 'events-topic',
              'field'    => 'slug',
              'terms'    => $term->slug,
          ],
      ],
      'meta_query' => [
          'relation' => 'OR',
          [
              'key'     => 'vf_event_internal_start_date',
              'value'   => date('Ymd'), // Current date in Ymd format
              'compare' => '>=',
              'type'    => 'numeric',
          ],
          [
              'key'     => 'vf_event_internal_end_date',
              'value'   => date('Ymd'), // Current date in Ymd format
              'compare' => '>=',
              'type'    => 'numeric',
          ],
      ],
  ]);
  $term_count = $term_posts_query->found_posts;

  // Output the option with the count
  ?>
  <option 
    data-path=".<?php echo esc_attr($term->slug); ?>" 
    value="<?php echo esc_attr($term->slug); ?>">
    <?php echo esc_html($term->name); ?> (<?php echo $term_count; ?>)
  </option>
  <?php
  wp_reset_postdata(); // Reset the global $post data
}
?>

</select>
    </div>
  </fieldset>
</form>
