<?php
$vfwp_indexed_search_response = function_exists('vfwp_intranet_search_frontend_current_request')
  ? vfwp_intranet_search_frontend_current_request()
  : null;
$vfwp_indexed_search_results = is_array($vfwp_indexed_search_response) && isset($vfwp_indexed_search_response['results'])
  ? $vfwp_indexed_search_response['results']
  : array();
$vfwp_indexed_search_pagination = is_array($vfwp_indexed_search_response) && isset($vfwp_indexed_search_response['pagination'])
  ? $vfwp_indexed_search_response['pagination']
  : array();
$vfwp_selected_search_filters = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_selected_filter_slugs()
  : array();
$vfwp_search_filter_definitions = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_filter_definitions()
  : array();
$vfwp_legacy_post_type = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_legacy_post_type()
  : '';
$vfwp_selected_content_type = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_selected_content_type()
  : 'all';
$vfwp_active_search_filters = class_exists('VFWP_Intranet_Search_Frontend')
  ? VFWP_Intranet_Search_Frontend::get_active_filters()
  : array();

get_header();

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}

?>


<section class="vf-hero | vf-u-fullbleed | vf-hero--800 | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      --vf-hero--bg-image: url('https://www.embl.org/internal-information/wp-content/uploads/2025/03/20250328_Intranet_hero-scaled.jpg');

            }
  </style>  
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
    <h1 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information">
        EMBL Intranet </a>
    </h1>
    <p><span class="vf-text-body vf-text-body--3 | vf-u-margin__right--100">Directories:</span>  
      <span><a class="vf-badge vf-badge--tertiary | vf-u-margin__right--100 | intranet-directory-badge" href="/internal-information/people">People</a></span>    
      <span><a class="vf-badge vf-badge--tertiary | vf-u-margin__right--100 | intranet-directory-badge" href="/internal-information/documents">Documents</a></span>    
      <!-- <span><a class="vf-badge vf-badge--tertiary | intranet-directory-badge" href="https://hd-tqportal.embl.de/EMBL_LIVE_thankQ_Web/public/network/results.aspx">Alumni</a></span></p> -->
  </div>
</section>

<?php
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}
?>
<div class="vf-grid">
  <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
          <p class="vf-banner__text"><span style="font-weight: 400">We need your help to improve your intranet experience. <a href="https://www.surveymonkey.com/r/intranetsurvey2026">Please answer three quick questions</a>.</span></p>
      </div>
  </div>
</div>
<?php
if (class_exists('VF_Intranet_Breadcrumbs')) {
  VF_Plugin::render(VF_Intranet_Breadcrumbs::get_plugin('vf_wp_breadcrumbs_intranet'));
}
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Intranet search
    </h1>
    <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
        <p class="vf-banner__text">Can't find what you need on the intranet? It may be on the public website <a
            class="vf-banner__link" href="https://www.embl.org/search">embl.org/search</a></p>
      </div>
    </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--0">
  <div></div>
  <div>
    <form role="search" method="get"
      class="vf-form vf-form--search | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search"><?php esc_html_e('Search the intranet', 'vfwp'); ?></label>
          <input id="search" class="vf-form__input vf-form__input--filter" type="text" placeholder="Enter your search term"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s"
            aria-describedby="search-result-count">
        </div>
        <div class="vf-form__item | vf-search__item" style="display: none">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="any" <?php selected($vfwp_legacy_post_type, ''); ?>>Everything</option>
            <option value="page" name="post_type[]" <?php selected($vfwp_legacy_post_type, 'page'); ?>>Pages</option>
            <option value="insites" name="post_type[]" <?php selected($vfwp_legacy_post_type, 'insites'); ?>>Internal news</option>
            <option value="events" name="post_type[]" <?php selected($vfwp_legacy_post_type, 'events'); ?>>Events</option>
            <option value="people" name="post_type[]" <?php selected($vfwp_legacy_post_type, 'people'); ?>>People</option>
            <option value="documents" name="post_type[]" <?php selected($vfwp_legacy_post_type, 'documents'); ?>>Documents</option>
          </select>
        </div>
        <?php foreach ($vfwp_selected_search_filters as $vfwp_selected_search_filter) : ?>
          <?php if (!empty($vfwp_search_filter_definitions[$vfwp_selected_search_filter])) : ?>
            <input type="hidden" name="<?php echo esc_attr(VFWP_Intranet_Search_Frontend::FILTER_PARAM); ?>[]" value="<?php echo esc_attr($vfwp_search_filter_definitions[$vfwp_selected_search_filter]['query_value']); ?>">
          <?php endif; ?>
        <?php endforeach; ?>
        <input type="hidden" name="<?php echo esc_attr(VFWP_Intranet_Search_Frontend::CONTENT_TYPE_PARAM); ?>" value="<?php echo esc_attr($vfwp_selected_content_type); ?>">
        <button type="submit" class="vf-search__button | vf-button vf-button--primary"
          value="<?php esc_attr_e('Search', 'vfwp'); ?>">
          <span class="vf-button__text">Search</span>
        </button>
      </div>
    </form>
  </div>
</section>
<div class="vf-stack vf-stack--400">
  <section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--600">
    <div>
      <?php include(locate_template('partials/search-filter.php', false, false)); ?>
    </div>
    <div aria-live="polite" aria-busy="false">
      <div>
        <?php
        if (is_array($vfwp_indexed_search_response)) {
          if (!empty($vfwp_indexed_search_results)) {
            if (class_exists('VFWP_Intranet_Search_Frontend')) {
              echo '<p id="search-result-count" class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400" role="status">' . esc_html(VFWP_Intranet_Search_Frontend::get_result_count_text($vfwp_indexed_search_pagination, get_search_query(false))) . '</p>';
            }

            if (!empty($vfwp_active_search_filters) && class_exists('VFWP_Intranet_Search_Frontend')) {
              echo VFWP_Intranet_Search_Frontend::render_active_filters();
            }

            $vfwp_indexed_search_post_ids = array();

            foreach ($vfwp_indexed_search_results as $vfwp_indexed_search_result) {
              if (isset($vfwp_indexed_search_result['object_type']) && $vfwp_indexed_search_result['object_type'] === 'post' && !empty($vfwp_indexed_search_result['object_id'])) {
                $vfwp_indexed_search_post_ids[] = (int) $vfwp_indexed_search_result['object_id'];
              }
            }

            if (!empty($vfwp_indexed_search_post_ids)) {
              _prime_post_caches(array_values(array_unique($vfwp_indexed_search_post_ids)), true, false);
            }

            foreach ($vfwp_indexed_search_results as $vfwp_indexed_search_result) {
              $GLOBALS['vfwp_indexed_search_result'] = $vfwp_indexed_search_result;

              if (isset($vfwp_indexed_search_result['object_type']) && $vfwp_indexed_search_result['object_type'] === 'pdf') {
                include(locate_template('partials/vf-summary--search-pdf.php', false, false));
                continue;
              }

              $vfwp_indexed_search_post = get_post((int) $vfwp_indexed_search_result['object_id']);

              if (!$vfwp_indexed_search_post instanceof WP_Post) {
                continue;
              }

              $post = $vfwp_indexed_search_post;
              setup_postdata($post);
              include(locate_template('partials/vf-summary--page.php', false, false));
              wp_reset_postdata();
            }

            unset($GLOBALS['vfwp_indexed_search_result']);

            if (class_exists('VFWP_Intranet_Search_Frontend')) {
              echo VFWP_Intranet_Search_Frontend::render_pagination($vfwp_indexed_search_pagination);
            }
          } else {
            echo '<div class="vf-stack vf-stack--200" role="status" aria-live="polite">';
            if (class_exists('VFWP_Intranet_Search_Frontend')) {
              echo '<h2 class="vf-text-heading--3" id="search-result-count">' . esc_html(VFWP_Intranet_Search_Frontend::get_result_count_text($vfwp_indexed_search_pagination, get_search_query(false))) . '</h2>';
            } else {
              echo '<h2 class="vf-text-heading--3" id="search-result-count">' . esc_html__('No results found', 'vfwp') . '</h2>';
            }
            if (!empty($vfwp_active_search_filters) && class_exists('VFWP_Intranet_Search_Frontend')) {
              echo VFWP_Intranet_Search_Frontend::render_active_filters();
            }
            echo '<p>' . esc_html__('Try checking the spelling, using fewer words, or searching for a broader term.', 'vfwp') . '</p>';
            echo '<ul class="vf-list">';
            echo '<li class="vf-list__item">' . esc_html__('Check spelling.', 'vfwp') . '</li>';
            echo '<li class="vf-list__item">' . esc_html__('Try fewer words.', 'vfwp') . '</li>';
            echo '<li class="vf-list__item">' . esc_html__('Use a broader search term.', 'vfwp') . '</li>';
            if (class_exists('VFWP_Intranet_Search_Frontend') && VFWP_Intranet_Search_Frontend::has_active_filters()) {
              echo '<li class="vf-list__item">' . esc_html__('Remove filters to search more content.', 'vfwp') . '</li>';
            }
            echo '</ul>';
            if (class_exists('VFWP_Intranet_Search_Frontend') && VFWP_Intranet_Search_Frontend::has_active_filters()) {
              echo '<p><a class="vf-button vf-button--secondary" href="' . esc_url(VFWP_Intranet_Search_Frontend::get_clear_filters_url()) . '">' . esc_html__('Clear filters', 'vfwp') . '</a></p>';
            }
            echo '</div>';
          }
        } elseif ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--page.php', false, false));
          }
        } else {
          echo '<p>', __('No results found', 'vfwp'), '</p>';
        } ?>
      </div>
    </div>
  </section>

  <?php
    if (class_exists('VF_Global_Footer')) {
      VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
    } ?>

  <?php get_footer(); ?>
