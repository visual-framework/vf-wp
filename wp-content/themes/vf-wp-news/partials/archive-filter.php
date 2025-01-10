
<?php
$counter = 2;

  $links = wp_get_archives(array('type' => 'yearly', 'format' => 'custom','echo'=>'0'));
  $year_array = explode(', ', $links);

 // Get all "Topic" terms
$category_terms = get_terms(
    array(
      'taxonomy'   => 'category',
      'hide_empty' => false,
      'include' => '17591, 17593, 17595, 17597, 3'
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
    <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="news">
      <option value="0" data-path="default">All</option>
      <?php
      global $wpdb;

      // List of category IDs to filter
      $category_ids = [501, 488, 511, 514, 910, 485];

      // Prepare the query to count posts by year for the given categories
      $query = "
        SELECT YEAR(p.post_date) as year, COUNT(DISTINCT p.ID) as count
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post'
          AND p.post_status = 'publish'
          AND tt.taxonomy = 'category'
          AND tt.term_id IN (" . implode(',', array_map('intval', $category_ids)) . ")
        GROUP BY YEAR(p.post_date)
        ORDER BY year DESC
      ";

      // Execute the query
      $results = $wpdb->get_results($query);

      // Output the dropdown options
      if ($results) {
        foreach ($results as $result) {
          echo '<option value="' . esc_attr($result->year) . '" data-path=".a' . esc_attr($result->year) . '">'
            . esc_html($result->year) . ' (' . esc_html($result->count) . ')</option>';
        }
      } else {
        echo '<option value="0">No posts available</option>';
      }
      ?>
    </select>
  </div>
</fieldset>







