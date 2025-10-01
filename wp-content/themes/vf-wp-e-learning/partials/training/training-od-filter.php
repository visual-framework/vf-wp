<?php

// Get all "organiser" terms
$provider_terms = get_terms(
  array(
    'taxonomy'   => 'training-organiser',
    'hide_empty' => false,
  )
);


$category_terms = [
    'Bioinformatics',
    'Databases and Online Tools',
    'Genome Biology',
    'Health and Disease',
    'Image Analysis',
    'Professional development',
    'Science education',
    'Structural Biology'
];
$duration_terms = ['less than 1 hour', '1 to 3 hours', '3 to 9 hours', 'more than 9 hours'];
$type_terms = ['Course materials', 'Recorded webinar', 'Online course', 'Online course collection'];
$subtype_terms = ['Tutorial', 'Collection'];
$certificate_terms = ['Certificate provided'];
$audience_terms = [
  'Life science researchers',
  'Data scientists',
  'Science Educators',
  'Early career researchers'
];


$counterDur = 1;
$counterTyp = 1;
$counterPro = 1;
$counterAud = 1;
$counterCer = 1;
$counterSub = 1;
$counterCat = 1;
  
?>

<form class="vf-stack vf-stack-400 | vf-u-margin__bottom--800">
<div class="vf-form__item vf-stack vf-u-margin__bottom--800">
  <legend class="vf-form__legend">Sort by</legend>
  <select class="vf-form__select" 
          id="vf-form__select" 
          data-jplist-control="select-sort" 
          data-group="data-group-2"
          data-name="sort-control">

    <!-- Recently Added (published date) -->
    <option value="0" data-path=".featured" data-order="desc" data-type="number" selected>
      Featured
    </option>

    <!-- Recently Added (published date) -->
    <option value="1" data-path=".added" data-order="desc" data-type="datetime" >
      Recently added
    </option>

    <!-- Recently Updated (modified date) -->
    <option value="2" data-path=".updated" data-order="desc" data-type="datetime">
      Recently updated
    </option>

    <!-- Alphabetical by visible title (anchor text) -->
    <option value="3" data-path=".post-title" data-order="asc" data-type="text">
      Alphabetical (A–Z)
    </option>
    <option value="4" data-path=".post-title" data-order="desc" data-type="text">
      Alphabetical (Z–A)
    </option>
  </select>
</div>



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

<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800"
          id="checkbox-filter-subtype"
          style="display: none; padding-left: 2rem;">
  <?php foreach ($subtype_terms as $sub) : 
    $subSlug = strtolower(str_replace(' ', '_', $sub)); ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="subtype-<?php echo $subSlug; ?>" 
             type="checkbox" 
             data-jplist-control="checkbox-text-filter"
             data-path=".subtype-<?php echo esc_attr($subSlug); ?>"
             data-group="data-group-2" 
             data-name="subtype" 
             data-or="subtype"
             value="<?php echo esc_attr($sub); ?>"
             data-id="subtype-<?php echo esc_attr($subSlug); ?>"
             class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="subtype-<?php echo $subSlug; ?>" class="vf-form__label">
        <?php echo esc_html($sub); ?>
        &nbsp;<span 
          data-jplist-control="counter"
          data-group="data-group-2"
          data-format="({count})"
          data-path=".subtype-<?php echo esc_attr($subSlug); ?>"
          data-mode="static"
          data-name="counter-subtype-<?php echo esc_attr($subSlug); ?>"
          data-filter-type="path">
        </span>
      </label>
    </div>
  <?php 
    $counterSub++;
  endforeach; ?>
</fieldset>

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-category">
    <legend class="vf-form__legend">Topic</legend>
    <?php
    foreach($category_terms as $cat) {
      $catSlug = strtolower(str_replace(' ', '_', $cat));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="category-<?php echo $catSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category-<?php echo $catSlug; ?>" data-group="data-group-1" data-name="category" data-or="category"
        value="<?php echo esc_attr($cat); ?>"
        data-id="category-<?php echo esc_attr($catSlug); ?>" class="vf-form__checkbox checkboxLive inputLive">
      <label for="category-<?php echo $catSlug; ?>" class="vf-form__label"><?php echo esc_html($cat); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-2"
      data-format="({count})"
      data-path=".category-<?php echo esc_attr($catSlug); ?>"
      data-mode="static"
      data-name="counter-category-<?php echo esc_attr($catSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterCat++;
    }
    ?>
  </fieldset>



<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-audience">
  <legend class="vf-form__legend">Audience</legend>
  <?php
  $totalAud = count($audience_terms);
  $indexAud = 1;

  foreach ($audience_terms as $aud) {
    $audSlug = strtolower(str_replace(' ', '-', $aud));
    $isLast  = ($indexAud === $totalAud);
    ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="audience-<?php echo $audSlug; ?>" 
             type="checkbox" 
             data-jplist-control="checkbox-text-filter"
             data-path=".audience-<?php echo $audSlug; ?>" 
             data-group="data-group-2" 
             data-name="audience" 
             data-or="audience"
             value="<?php echo esc_attr($aud); ?>"
             data-id="audience-<?php echo esc_attr($audSlug); ?>" 
             class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="audience-<?php echo $audSlug; ?>" class="vf-form__label">
        <?php echo esc_html($aud); ?>
        &nbsp;<span 
          <?php echo $isLast ? 'style=""' : ''; ?>
          data-jplist-control="counter"
          data-group="data-group-2"
          data-format="({count})"
          data-path=".audience-<?php echo esc_attr($audSlug); ?>"
          data-mode="static"
          data-name="counter-audience-<?php echo esc_attr($audSlug); ?>"
          data-filter-type="path">
        </span>
      </label>
    </div>
    <?php
    $counterAud++;
    $indexAud++;
  }
  ?>
</fieldset>

  </fieldset>

    <fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-duration">
    <legend class="vf-form__legend">Duration</legend>
    <?php
    foreach($duration_terms as $dur) {
      $durSlug = strtolower(str_replace(' ', '_', $dur));
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="duration-<?php echo $durSlug; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".duration-<?php echo $durSlug; ?>" data-group="data-group-2" data-name="duration" data-or="duration"
        value="<?php echo esc_attr($dur); ?>"
        data-id="duration-<?php echo esc_attr($durSlug); ?>" class="vf-form__checkbox checkboxOnDemand inputOnDemand">
      <label for="duration-<?php echo $durSlug; ?>" class="vf-form__label"><?php echo esc_html($dur); ?>
      &nbsp;<span 
      data-jplist-control="counter"
      data-group="data-group-2"
      data-format="({count})"
      data-path=".duration-<?php echo esc_attr($durSlug); ?>"
      data-mode="static"
      data-name="counter-duration-<?php echo esc_attr($durSlug); ?>"
      data-filter-type="path"></span>
    </label>
    </div>
    <?php
      $counterDur++;
    }
      
    ?>
  </fieldset>

<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800" id="checkbox-filter-certificate">
  <legend class="vf-form__legend">Certificate</legend>

  <div class="vf-form__item vf-form__item--checkbox">
    <input id="certificate-1" type="checkbox"
           data-jplist-control="checkbox-text-filter"
           data-path=".certificate-1"
           data-group="data-group-2"
           data-name="certificate"
           value="certificate-1"
           class="vf-form__checkbox checkboxOnDemand inputOnDemand">
    <label for="certificate-1" class="vf-form__label">
      Certificate provided
      &nbsp;<span 
        data-jplist-control="counter"
        data-group="data-group-2"
        data-format="({count})"
        data-path=".certificate-1"
        data-mode="static"
        data-name="counter-certificate-1"
        data-filter-type="path">
      </span>
    </label>
  </div>
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
