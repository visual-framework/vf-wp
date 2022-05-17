
<?php
$counter = 2;

  $links = wp_get_archives(array('type' => 'yearly', 'format' => 'custom','echo'=>'0'));
  $year_array = explode(', ', $links);

 // Get all "Topic" terms
$category_terms = get_terms(
    array(
      'taxonomy'   => 'category',
      'hide_empty' => false,
      'include' => '5,4,3,2'
    )
  );
 ?>


<fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Category</legend>
    <?php
    foreach($category_terms as $term) {
      ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="cat-<?php echo $counter; ?>" type="checkbox" data-jplist-control="checkbox-text-filter"
        data-path=".category" data-group="news" data-name="cat" data-or="cat"
        value="<?php echo esc_attr($term->slug); ?>"
        data-id="cat<?php echo $counter; ?>-<?php echo esc_attr($term->slug); ?>" class="vf-form__checkbox">
      <label for="cat-<?php echo $counter; ?>" class="vf-form__label"><?php echo esc_html($term->name . '&nbsp;(' . $term->count) . ')'; ?></label>
    </div>
    <?php
      $counter++;
    }
    ?>
  </fieldset>
<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800">
  <legend class="vf-form__legend">Year</legend>

  <div class="vf-form__item vf-stack">
  <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="news" >
  <option value="0" data-path="default" >All</option>
    <?php echo wp_get_archives(array('type' => 'yearly', 'format' => 'custom', 'echo'=>'0', 'show_post_count' => 'true')) ?>
    </select>
    </div>
</fieldset>



