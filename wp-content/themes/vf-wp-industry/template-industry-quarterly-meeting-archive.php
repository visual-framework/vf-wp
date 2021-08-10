<?php
/**
* Template Name: Quarterly meetings archive
*/

get_header();

// Global Header
if (class_exists('VF_EBI_Global_Header')) {
  VF_Plugin::render(VF_EBI_Global_Header::get_plugin('vf_ebi_global_header'));
}
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
}
if (class_exists('VF_WP_Hero')) {
  VF_Plugin::render(VF_WP_Hero::get_plugin('vf_wp_hero_group'));
}
?>
<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <li id="menu-item-63" class="vf-navigation__item"><a href="<?php echo get_home_url() ?>"
        class="vf-navigation__link">Home</a></li>
    <li id="menu-item-409" class="vf-navigation__item"><a
        href="<?php echo get_home_url() . '/private/members-area/'; ?>" class="vf-navigation__link" aria-current="page"
        >Members area</a></li>
    <li id="menu-item-472" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/private/workshops/'; ?>"
        class="vf-navigation__link" >Workshops</a></li>
    <li id="menu-item-410" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/smes/'; ?>"
        class="vf-navigation__link">SMEs</a></li>
    <li id="menu-item-51" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/our-approach/'; ?>"
        class="vf-navigation__link">Our approach</a></li>
    <li id="menu-item-50" class="vf-navigation__item"><a href="<?php echo get_home_url() . '/contact-us'; ?>"
        class="vf-navigation__link">Contact us</a></li>
  </ul>
</nav>

<?php

global $post;
setup_postdata($post);

global $vf_theme;

$title = get_the_title();

?>

<?php 

$open_wrap = function($html, $block_name) {
  $html = '
<div class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
' . $html;
return $html;
};

$close_wrap = function($html, $block_name) {
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

<?php

  if ( has_block( 'acf/vfwp-intro', $post ) ) {  
  parse_blocks( 'acf/vfwp-intro' ); } 

  else if ( has_block( 'acf/vfwp-page-header', $post ) ) {
  parse_blocks( 'acf/vfwp-page-header' ); }

  else if ( has_block( 'acf/vfwp-hero', $post ) ) {
    parse_blocks( 'acf/vfwp-hero' ); }
  
  else if ( has_block( 'acf/vfwp-masthead', $post ) ) {
    parse_blocks( 'acf/vfwp-masthead' ); }

  else {  ?>
<section class="embl-grid embl-grid--has-sidebar">
  <div>
    <!-- empty -->
  </div>
  <div>
    <h1 class="vf-text vf-text-heading--1">
      <?php echo $title;?>
    </h1>
  </div>
  <div>
    <!-- empty -->
  </div>
</section>
<?php } ?>

<section class="embl-grid embl-grid--has-sidebar | vf-content">
  <div>
    <?php include(locate_template('partials/filter-year.php', false, false)); ?>
  </div>
  <div>
  <div class="vf-u-margin__bottom--800">
    <form action="#eventsFilter" onsubmit="return false;"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="textbox-filter">Search</label>
          <input id="textbox-filter" data-jplist-control="textbox-filter" data-group="data-group-1"
            data-name="my-filter-1" data-path=".vf-summary__title" data-id="search" type="text" value=""
            placeholder="Filter by workshop title" data-clear-btn-id="name-clear-btn"
            class="vf-form__input | vf-search__input" />
        </div>
        <button href="#eventsFilter" class="vf-search__button | vf-button vf-button--primary">
          <span class="vf-button__text">Filter</span>
        </button>
      </div>
    </form>
  </div>

    <div data-jplist-group="data-group-1">
      <?php
     $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$forthcomingLoop = new WP_Query (array( 
  'paged' => $paged,
  'tax_query' => array(
    array (
        'taxonomy' => 'type',
        'field' => 'slug',
        'terms' => 'industry-quarterly-meeting',
    )
  ), 
  'posts_per_page' => 12, 
  'post_type' => 'industry_event', 
  'order' => 'DESC', 
  'orderby' => 'meta_value_num',
  'meta_key' => 'vf_event_industry_start_date', 
));
  $temp_query = $wp_query;
  $wp_query   = NULL;
  $wp_query   = $forthcomingLoop;
  $current_month = ""; ?>
      <?php
  while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post(); ?>
      <?php include(locate_template('partials/vf-summary-event.php', false, false)); ?>
      <?php endwhile;?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching events found;
        </p>
      </article>
      <?php wp_reset_postdata();   ?>
      <?php 
        $wp_query = NULL;
        $wp_query = $temp_query;
        ?>
    </div>
    <style>
      .jplist-selected {
        background-color: #707372;
      }

      .jplist-selected a {
        color: #fff;
      }

    </style>

    <nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
      data-items-per-page="10" data-current-page="0" data-name="pagination1">

      <ul class="vf-pagination__list">
        <li class="vf-pagination__item vf-pagination__item--previous-page" data-type="prev">
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
        <li class="vf-pagination__item vf-pagination__item--next-page" data-type="next">
          <a href="#" class="vf-pagination__link">
            Next<span class="vf-u-sr-only"> page</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
  <div>
  <?php if ( is_active_sidebar( 'members-area' ) ) : ?>
        <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
          <?php dynamic_sidebar( 'members-area' ); ?>
        </div>
        <?php endif; ?>
</div>
</section>

<script type="text/javascript">
  jplist.init();

</script>

<?php 
// Global Footer
if (class_exists('VF_EBI_Global_Footer')) {
  VF_Plugin::render(VF_EBI_Global_Footer::get_plugin('vf_ebi_global_footer'));
}

get_footer(); ?>
