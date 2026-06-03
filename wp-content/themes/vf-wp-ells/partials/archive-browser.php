<?php
$browser_id = isset($archive_browser['id']) ? $archive_browser['id'] : 'ells-archive-browser';
$enable_search = ! isset($archive_browser['enable_search']) || $archive_browser['enable_search'];
$items = vf_wp_ells_archive_browser_items($archive_browser);
$config = array(
  'id' => $browser_id,
  'itemsPerPage' => isset($archive_browser['items_per_page']) ? (int) $archive_browser['items_per_page'] : 10,
  'resultLabel' => isset($archive_browser['result_label']) ? $archive_browser['result_label'] : 'results',
  'enableSearch' => $enable_search,
  'filters' => $archive_browser['filters'],
  'items' => $items,
);
?>

<div class="ells-archive-browser" id="<?php echo esc_attr($browser_id); ?>">
  <div class="vf-grid vf-grid__col-4 | vf-u-padding__top--400">
    <div>
      <form class="ells-filter-form vf-stack vf-stack--400" action="#" novalidate>
        <?php if ($enable_search) { ?>
        <div class="filter-item filter-item--search">
          <h3 class="vf-form__legend">Search</h3>
          <div class="vf-form vf-form--search vf-form--search--mini name-search-form vf-u-margin__top--400">
            <div class="filter-input-row">
              <input
                id="<?php echo esc_attr($browser_id); ?>-search"
                type="search"
                class="ells-filter-search vf-form__input"
                placeholder="Search"
                aria-label="Search"
                autocomplete="off"
              >
              <div class="vf-search__button | search-button vf-button--primary" aria-hidden="true">
                <span class="vf-button__text | vf-u-sr-only">Search</span>
                <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 140" width="140" height="140">
                  <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
                    <path d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z" fill="#FFFFFF"></path>
                  </g>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>

        <?php foreach ($archive_browser['filters'] as $filter) {
          $field = $filter['key'];
          $control = isset($filter['control']) ? $filter['control'] : (($field === 'year' || $field === 'lang') ? 'select' : 'checkboxes');
          $options = isset($filter['options']) && is_array($filter['options']) ? $filter['options'] : array();

          if ($control === 'select') {
            $select_id = $browser_id . '-' . $field;
            $select_label_class = $field === 'year' ? 'vf-form__legend' : 'vf-form__label';
            ?>
            <div class="filter-item filter-item--select" data-field="<?php echo esc_attr($field); ?>">
              <label for="<?php echo esc_attr($select_id); ?>" class="<?php echo esc_attr($select_label_class); ?>"><?php echo esc_html($filter['label']); ?></label>
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
      <div class="ells-results-info vf-u-margin__bottom--400" role="status" aria-live="polite" aria-atomic="true"></div>
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
