
<?php
$counter = 2;

 // Get all "Topic" terms
$category_terms = get_terms(
    array(
      'taxonomy'   => 'category',
      'hide_empty' => false,
      'include' => '5,4,3,2'
    )
  );
 ?>


<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800">
  <legend class="vf-form__legend">Category</legend>
    <?php
    foreach($category_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="cat-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category" data-group="news" data-name="cat" data-or="cat"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="cat<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="cat-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name); ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
</fieldset>




