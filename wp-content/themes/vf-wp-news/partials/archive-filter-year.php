
<?php
$counter = 2;
?>
<fieldset class="vf-form__fieldset vf-stack vf-stack--400 | vf-u-margin__bottom--800">
  <legend class="vf-form__legend">Year</legend>

  <div class="vf-form__item vf-stack">
    <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="news">
      <option value="0" data-path="default">All</option>
      <?php
        if (is_tax() || is_category() || is_tag()) {
          global $wpdb;

          // Get the current term ID and taxonomy
          $term_id = get_queried_object_id();
          $taxonomy = get_queried_object()->taxonomy;

          // Query to count posts by year for the current term
          $years_with_counts = $wpdb->get_results($wpdb->prepare("
            SELECT YEAR(p.post_date) AS year, COUNT(p.ID) AS count
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE p.post_type = 'post'
              AND p.post_status = 'publish'
              AND tt.term_id = %d
              AND tt.taxonomy = %s
            GROUP BY year
            ORDER BY year DESC
          ", $term_id, $taxonomy));

          foreach ($years_with_counts as $year) {
            echo '<option value="' . esc_attr($year->year) . '" data-path=".a' . esc_attr($year->year) . '">'
              . esc_html($year->year . ' (' . $year->count . ')')
              . '</option>';
          }
        }
      ?>
    </select>
  </div>
</fieldset>





