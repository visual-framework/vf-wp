<?php

// Get all "Topic" terms
$topic_terms = get_terms(
  array(
    'taxonomy'   => 'document_topic',
    'hide_empty' => false,
  )
);

// Get all "Type" terms
$type_terms = get_terms(
  array(
    'taxonomy'   => 'document_type',
    'hide_empty' => false,
  )
);

$counter = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Document topic</label>
    <?php
    foreach($topic_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="topic-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".topic" data-group="data-group-1" data-name="name" data-or="name"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="name<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="topic-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <label class="vf-form__label">Document type</label>
    <?php
    foreach($type_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="data-group-1" data-name="name" data-or="name"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="name<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="type-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
</form>