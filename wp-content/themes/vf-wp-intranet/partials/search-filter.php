<?php
$vfwp_search_filter_definitions = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_filter_definitions()
  : array();
$vfwp_selected_search_filters = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_selected_filter_slugs()
  : array();
$vfwp_content_type_definitions = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_content_type_definitions()
  : array();
$vfwp_selected_content_type = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_selected_content_type()
  : 'all';
$vfwp_search_filter_counts = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_filter_counts()
  : array('content_type' => array(), 'search_type' => array());
$vfwp_search_show_clear_filters = isset($vfwp_search_show_clear_filters)
  ? (bool) $vfwp_search_show_clear_filters
  : true;
?>
<form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="vf-stack vf-stack-400 | vf-u-margin__bottom--400" id="vf-search-filters">
  <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query(false)); ?>">
  <fieldset id="content-type-container" class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend"><?php esc_html_e('Content type', 'vfwp'); ?></legend>
    <?php foreach ($vfwp_content_type_definitions as $vfwp_content_type_slug => $vfwp_content_type_definition) : ?>
    <div class="vf-form__item vf-form__item--radio">
      <input id="content-type-<?php echo esc_attr($vfwp_content_type_slug); ?>" type="radio"
        name="<?php echo esc_attr(VFWP_Intranet_Search_Frontend::CONTENT_TYPE_PARAM); ?>"
        value="<?php echo esc_attr($vfwp_content_type_slug); ?>"
        class="vf-form__radio" <?php checked($vfwp_selected_content_type, $vfwp_content_type_slug); ?>>
      <label for="content-type-<?php echo esc_attr($vfwp_content_type_slug); ?>" class="vf-form__label">
        <?php echo esc_html($vfwp_content_type_definition['label']); ?>
        <?php
        if (class_exists('VFWP_Intranet_Search_Frontend')) {
          echo VFWP_Intranet_Search_Frontend::render_filter_count(isset($vfwp_search_filter_counts['content_type'][$vfwp_content_type_slug]) ? $vfwp_search_filter_counts['content_type'][$vfwp_content_type_slug] : 0);
        }
        ?>
      </label>
    </div>
    <?php endforeach; ?>
  </fieldset>
  <fieldset id="checkbox-container" class="vf-form__fieldset vf-stack vf-stack--400">
    <legend class="vf-form__legend"><?php esc_html_e('Category', 'vfwp'); ?></legend>
    <?php foreach ($vfwp_search_filter_definitions as $vfwp_filter_slug => $vfwp_filter_definition) : ?>
    <div class="vf-form__item vf-form__item--checkbox">
      <input id="<?php echo esc_attr($vfwp_filter_slug); ?>" type="checkbox"
        name="<?php echo esc_attr(VFWP_Intranet_Search_Frontend::FILTER_PARAM); ?>[]" value="<?php echo esc_attr($vfwp_filter_definition['query_value']); ?>"
        data-id="<?php echo esc_attr($vfwp_filter_slug); ?>" class="vf-form__checkbox" <?php checked(in_array($vfwp_filter_slug, $vfwp_selected_search_filters, true)); ?>>
      <label for="<?php echo esc_attr($vfwp_filter_slug); ?>" class="vf-form__label">
        <?php echo esc_html($vfwp_filter_definition['label']); ?>
        <?php
        if (class_exists('VFWP_Intranet_Search_Frontend')) {
          echo VFWP_Intranet_Search_Frontend::render_filter_count(isset($vfwp_search_filter_counts['search_type'][$vfwp_filter_slug]) ? $vfwp_search_filter_counts['search_type'][$vfwp_filter_slug] : 0);
        }
        ?>
      </label>
    </div>
    <?php endforeach; ?>
    <button type="submit" class="vf-u-sr-only"><?php esc_html_e('Apply filters', 'vfwp'); ?></button>
  </fieldset>
  <?php if (class_exists('VFWP_Intranet_Search_Frontend') && VFWP_Intranet_Search_Frontend::has_active_filters()) : ?>
    <a class="vf-button vf-button--link" href="<?php echo esc_url(VFWP_Intranet_Search_Frontend::get_clear_filters_url()); ?>"><?php esc_html_e('Clear filters', 'vfwp'); ?></a>
  <?php endif; ?>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('vf-search-filters');

    if (!filterForm) {
      return;
    }

    filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function (input) {
      input.addEventListener('change', function () {
        filterForm.submit();
      });
    });
  });
</script>
