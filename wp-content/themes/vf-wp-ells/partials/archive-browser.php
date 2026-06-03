<?php
$browser_id = isset($archive_browser['id']) ? $archive_browser['id'] : 'ells-archive-browser';
$items = vf_wp_ells_archive_browser_items($archive_browser);
$config = array(
  'id' => $browser_id,
  'itemsPerPage' => isset($archive_browser['items_per_page']) ? (int) $archive_browser['items_per_page'] : 10,
  'resultLabel' => isset($archive_browser['result_label']) ? $archive_browser['result_label'] : 'results',
  'filters' => $archive_browser['filters'],
  'items' => $items,
);
?>

<div class="ells-archive-browser" id="<?php echo esc_attr($browser_id); ?>">
  <div class="ells-results-toolbar">
    <div class="ells-results-info" role="status" aria-live="polite" aria-atomic="true"></div>
  </div>

  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>
      <form class="ells-filter-form vf-stack vf-stack--400" action="#" novalidate>
        <?php foreach ($archive_browser['filters'] as $filter) {
          $field = $filter['key'];
          $control = isset($filter['control']) ? $filter['control'] : (($field === 'year' || $field === 'lang') ? 'select' : 'checkboxes');
          $options = isset($filter['options']) && is_array($filter['options']) ? $filter['options'] : array();

          if ($control === 'select') {
            $select_id = $browser_id . '-' . $field;
            ?>
            <div class="filter-item filter-item--select" data-field="<?php echo esc_attr($field); ?>">
              <label for="<?php echo esc_attr($select_id); ?>" class="vf-form__label"><?php echo esc_html($filter['label']); ?></label>
              <select id="<?php echo esc_attr($select_id); ?>" class="vf-form__select ells-filter-select" data-field="<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($field); ?>">
                <option value="" data-label="<?php echo esc_attr($filter['all_label']); ?>"><?php echo esc_html($filter['all_label']); ?></option>
                <?php foreach ($options as $option) { ?>
                  <option value="<?php echo esc_attr($option['value']); ?>" data-label="<?php echo esc_attr($option['label']); ?>"><?php echo esc_html($option['label']); ?></option>
                <?php } ?>
              </select>
            </div>
            <?php
            continue;
          }
          ?>
          <fieldset class="filter-item vf-form__fieldset vf-stack vf-stack--400" data-field="<?php echo esc_attr($field); ?>">
            <legend class="vf-form__legend"><?php echo esc_html($filter['label']); ?></legend>
            <?php if (empty($options)) { ?>
              <p class="vf-text-body vf-text-body--5 | vf-u-text-color--grey">No filter options available.</p>
            <?php } ?>
            <?php foreach ($options as $index => $option) {
              $input_id = $browser_id . '-' . $field . '-' . $index;
              ?>
              <div class="vf-form__item vf-form__item--checkbox">
                <input
                  type="checkbox"
                  value="<?php echo esc_attr($option['value']); ?>"
                  id="<?php echo esc_attr($input_id); ?>"
                  name="<?php echo esc_attr($field); ?>[]"
                  class="vf-form__checkbox ells-filter-checkbox"
                  data-field="<?php echo esc_attr($field); ?>"
                >
                <label for="<?php echo esc_attr($input_id); ?>" class="vf-form__label">
                  <?php echo esc_html($option['label']); ?>
                  <span class="ells-filter-count" data-field="<?php echo esc_attr($field); ?>" data-value="<?php echo esc_attr($option['value']); ?>">(0)</span>
                </label>
              </div>
            <?php } ?>
          </fieldset>
        <?php } ?>

        <button type="button" class="vf-link clear-all-button ells-clear-all-button">Clear all filters</button>
      </form>
    </div>

    <div class="vf-grid__col--span-3">
      <div class="ells-results-container vf-u-margin__top--0" role="region" aria-live="polite"></div>
      <nav class="vf-pagination" aria-label="Results pages">
        <ul class="vf-pagination__list ells-pagination-list"></ul>
      </nav>
    </div>
  </div>

  <script type="application/json" class="ells-archive-browser-config">
    <?php echo wp_json_encode($config); ?>
  </script>
</div>
