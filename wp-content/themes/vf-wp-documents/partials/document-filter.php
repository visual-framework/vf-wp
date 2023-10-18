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

$counterTyp = 1;
$counterTop = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend">Document topic</legend>
    <?php
    foreach($topic_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="topic-<?php echo $counterTop; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".topic" data-group="documents" data-name="topic" data-or="topic"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="topic<?php echo $counterTop; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="topic-<?php echo $counterTop; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counterTop++;
    }
    ?>
  </fieldset>
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend">Document type</legend>
    <?php
    foreach($type_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $counterTyp; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type" data-group="documents" data-name="type" data-or="type"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="type<?php echo $counterTyp; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="type-<?php echo $counterTyp; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counterTyp++;
    }
    ?>
  </fieldset>
</form>