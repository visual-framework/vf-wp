<?php
get_header();
// Global Header
?>
<span data-protection-message-disable="true"></span>
<!-- embl-ebi global header -->
<header id="masthead-black-bar"
        class="clearfix masthead-black-bar | ebi-header-footer vf-content vf-u-fullbleed">
</header>
<link rel="import"
      href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=6682&pattern=node-body&source=contenthub"
      data-target="self" data-embl-js-content-hub-loader>
<link rel="stylesheet"
      href="//ebi.emblstatic.net/web_guidelines/EBI-Icon-fonts/v1.3/fonts.css"
      type="text/css"
      media="all"/>
<script defer="defer"
        src="//ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.4/js/script.js"></script>
<link rel="stylesheet"
      href="https://assets.emblstatic.net/vf/v2.4.12/assets/ebi-header-footer/ebi-header-footer.css"
      type="text/css" media="all"/>
<?php include(locate_template('partials/main_top_navigation.php', FALSE, FALSE)); ?>
<?php
//if (class_exists('VF_Breadcrumbs')) {
//  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
//}
if (class_exists('VF_WP_Hero')) {
  VF_Plugin::render(VF_WP_Hero::get_plugin('vf_wp_hero_group'));
}
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
$current_date = date('Ymd');
?>

<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <?php
    if (has_nav_menu('primary')) {
      wp_nav_menu([
        'theme_location' => 'primary',
        'depth' => 1,
        'container' => FALSE,
        'items_wrap' => '%3$s',
      ]);
    }
    ?>
  </ul>
</nav>

<?php
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
?>

<?php
$open_wrap = function ($html, $block_name) {
  $html = '
  <div class="embl-grid embl-grid--has-centered-content">
    <div></div>
    <div>
  ' . $html;
  return $html;
};
$close_wrap = function ($html, $block_name) {
  $html .= '
    </div>
    <div></div>
  </div>
  <!--/embl-grid-->';
  return $html;
};
add_filter(
  'vf/__experimental__/theme/content/open_block_wrap',
  $open_wrap,
  10, 2
);
add_filter(
  'vf/__experimental__/theme/content/close_block_wrap',
  $close_wrap,
  10, 2
);
?>


<section class="embl-grid embl-grid--has-centered-content | vf-content">
  <div>
  </div>
  <div class="vf-u-margin__bottom--800">
    <form action="#eventsFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label"
                 for="textbox-filter">Search</label>
          <input id="textbox-filter" data-jplist-control="textbox-filter"
                 data-group="data-group-1"
                 data-name="my-filter-1" data-path=".vf-summary__title"
                 data-id="search" type="text" value=""
                 placeholder="Filter by event title"
                 data-clear-btn-id="name-clear-btn"
                 class="vf-form__input | vf-search__input"/>
        </div>
        <button href="#eventsFilter"
                class="vf-search__button | vf-button vf-button--primary">
          <span class="vf-button__text">Filter</span>
        </button>
      </div>
    </form>
  </div>

</section>
<section class="embl-grid | vf-content">
  <div>
    <?php include(locate_template('partials/filter-public-event.php', FALSE, FALSE)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
      $forthcomingLoop = new WP_Query ([
        'post_type' => 'events',
        'order' => 'ASC',
        'orderby' => 'meta_value_num',
        'meta_key' => 'vf_event_start_date',
        'meta_query' => [
          [
            'key' => 'vf_event_start_date',
            'value' => $current_date,
            'compare' => '>',
            'type' => 'numeric',
          ],
          [
            'key' => 'vf_event_start_date',
            'value' => date('Ymd', strtotime('now')),
            'type' => 'numeric',
            'compare' => '>',
          ],
          [
            'key' => 'vf_event_event_type',
            'value' => 'public_event',
          ],
        ],
      ]);
      $temp_query = $wp_query;
      $wp_query = NULL;
      $wp_query = $forthcomingLoop;
      $current_month = ""; ?>
      <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post(); ?>
        <?php
        include(locate_template('partials/vf-summary-events.php', FALSE, FALSE)); ?>
      <?php endwhile; ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event"
               data-jplist-control="no-results" data-group="data-group-1"
               data-name="no-results">
        <p class="vf-summary__text">
          No matching events found
        </p>
      </article>

    </div>
    <div>
      <?php
      $wp_query = NULL;
      $wp_query = $temp_query;
      ?>
      <style>
        .jplist-selected {
          background-color: #707372;
        }

        .jplist-selected a {
          color: #fff;
        }

      </style>
      <nav class="vf-pagination" aria-label="Pagination"
           data-jplist-control="pagination" data-group="data-group-1"
           data-items-per-page="20" data-current-page="0"
           data-name="pagination1">

        <ul class="vf-pagination__list">
          <li class="vf-pagination__item vf-pagination__item--previous-page"
              data-type="prev">
            <a class="vf-pagination__link">
              Previous<span class="vf-u-sr-only"> page</span>
            </a>
          </li>
          <div data-type="pages" style="display: flex;">
            <li class="vf-pagination__item" data-type="page">
              <a href="#" class="vf-pagination__link">
                {pageNumber}<span class="vf-u-sr-only">page</span>
              </a>
            </li>
          </div>
          <li class="vf-pagination__item vf-pagination__item--next-page"
              data-type="next">
            <a href="#" class="vf-pagination__link">
              Next<span class="vf-u-sr-only"> page</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div>
  </div>
</section>

<section class="vf-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; endif; ?></section>

<script type="text/javascript">
  jplist.init();

</script>

<!-- embl-ebi global footer -->
<link rel="import"
      href="https://www.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=106902&pattern=node-body&source=contenthub"
      data-target="self" data-embl-js-content-hub-loader>
<div class="vf-u-display-none" data-protection-message-disable="true"></div>

<?php get_footer(); ?>
