<?php

// Get all "Category" terms
$category_terms = get_terms(
  array(
    'taxonomy'   => 'training-category',
    'hide_empty' => false,
  )
);



$counterCat = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend">Category</legend>
    <?php
    foreach($category_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="category-<?php echo $counterCat; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category" data-group="data-group-1" data-name="category" data-or="category"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="category<?php echo $counterCat; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="category-<?php echo $counterCat; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>

</form>