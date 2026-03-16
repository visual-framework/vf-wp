<?php

// Get all "organiser" terms
$provider_terms = get_terms(
  array(
    'taxonomy'   => 'training-organiser',
    'hide_empty' => false,
  )
);


$category_terms = ['Data science', 'Professional development', 'Workplace'];
$type_terms = ['Course materials', 'Recorded webinar', 'Online tutorial', 'Collection'];


$counterCat = 1;
$counterTyp = 1;
$counterPro = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--800">
  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-category-od">
    <legend class="vf-form__legend">Category</legend>
    <?php
    foreach($category_terms as $cat) {
      $catSlug = strtolower(str_replace(' ', '_', $cat));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="category-od-<?php echo $catSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category-od-<?php echo $catSlug; ?>" data-group="data-group-2" data-name="category-od" data-or="category-od"
        value="<?php echo esc_attr($cat); ?>"
        data-id="category-od-<?php echo esc_attr($catSlug); ?>" class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="category-od-<?php echo $catSlug; ?>" class="vf-form__label"><?php echo esc_html($cat); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-2"
      data-format="({count})"
      data-path=".category-od-<?php echo esc_attr($catSlug); ?>"
      data-mode="static"
      data-name="counter-category-od-<?php echo esc_attr($catSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-type">
    <legend class="vf-form__legend">Type</legend>
    <?php
    foreach($type_terms as $typ) {
      $typSlug = strtolower(str_replace(' ', '_', $typ));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="type-<?php echo $typSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".type-<?php echo $typSlug; ?>" data-group="data-group-2" data-name="type" data-or="type"
        value="<?php echo esc_attr($typ); ?>"
        data-id="type-<?php echo esc_attr($typSlug); ?>" class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="type-<?php echo $typSlug; ?>" class="vf-form__label"><?php echo esc_html($typ); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-2"
      data-format="({count})"
      data-path=".type-<?php echo esc_attr($typSlug); ?>"
      data-mode="static"
      data-name="counter-type-<?php echo esc_attr($typSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterTyp++;
    }
    ?>
  </fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400" id="checkbox-filter-provider-od">
    <legend class="vf-form__legend">Provider</legend>
    <?php
    foreach($provider_terms as $term) {
      if ($term->slug === 'fellows-complementary-skills') {
        continue;
      }
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="provider-od-<?php echo$term->slug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".provider-od-<?php echo$term->slug; ?>" data-group="data-group-2" data-name="provider-od" data-or="provider-od"
        value="<?php echo esc_attr($term->name); ?>"
        data-id="provider-od-<?php echo$term->slug; ?>" class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="provider-od-<?php echo$term->slug; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?>
      &nbsp;<span style="flex-basis: 20%;"
      data-jplist-control="counter"
      data-group="data-group-2"
      data-format="({count})"
      data-path=".provider-od-<?php echo esc_attr($term->slug); ?>"
      data-mode="static"
      data-name="counter-provider-od-<?php echo esc_attr($term->slug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterPro++;
    }
    ?>
  </fieldset>

</form>
<style>
  .vf-form__label {
    font-size: 16px !important;
}
.vf-form__legend {
    font-size: 21px !important;
}
</style>