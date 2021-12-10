<?php
/**
* Template Name: Internal events (archive)
*/

get_header();

// Global Header
?>
<?php include(locate_template('partials/ebi_header.php', false, false)); ?>

<nav class="vf-breadcrumbs" aria-label="Breadcrumb">
  <ul class="vf-breadcrumbs__list | vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item">
      <a href="/about" class="vf-breadcrumbs__link">About us</a>
    </li>
    <li class="vf-breadcrumbs__item">
      <a href="/about/events" class="vf-breadcrumbs__link">Events</a>
    </li>
    <li class="vf-breadcrumbs__item" aria-current="location">
      Internal events
    </li>
  </ul>
  <span class="vf-breadcrumbs__heading">Related:</span>
  <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item" false=""><a href="https://www.embl.org/events" class="vf-breadcrumbs__link">All EMBL Events</a></li>
  </ul>
</nav>

<section class="vf-hero | vf-u-fullbleed" style=" --vf-hero--bg-image-size: auto 28.5rem">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <h2 class="vf-hero__heading"><a class="vf-hero__heading_link" href="/internal-events">Internal events</a></h2>
    <p class="vf-hero__text"></p> 
  </div>
</section>

<?php

global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
$current_date = date('Ymd');


?>

<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <li class="vf-navigation__item">
      <a href="/internal-events" class="vf-navigation__link" aria-current="page">Upcoming events</a>
    </li>
    <li class="vf-navigation__item">
      <a href="/internal-events/past-events" class="vf-navigation__link">Past events</a>
    </li>
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
<div class="vf-grid vf-grid__col-1">
  <div>
' . $html;
return $html;
};

$close_wrap = function($html, $block_name) {
  $html .= '
  </div>
  </div>';
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
    <?php
    $search_placeholder_text = "Filter by event title";
    ?>
    <?php include(locate_template('partials/event_search_form.php', false, false)); ?>
  </div>

</section>
<section class="embl-grid | vf-content">
  <div>
    <?php include(locate_template('partials/filter-internal-archive.php', false, false)); ?>
  </div>
  <div>


    <div data-jplist-group="data-group-1">
      <?php
$forthcomingLoop = new WP_Query (array( 
  'post_type' => 'events', 
  'order' => 'ASC', 
  'orderby' => 'meta_value_num',
  'meta_key' => 'vf_event_start_date', 
  'meta_query' => array(
      array(
        'key' => 'vf_event_start_date',
        'value' => $current_date,
        'compare' => '>',
        'type' => 'numeric'
      ),        
      array(
        'key' => 'vf_event_start_date',
        'value' => date('Ymd', strtotime('now')),
        'type' => 'numeric',
        'compare' => '>',
        ),
      array(
        'key' => 'vf_event_event_type',
        'value' => 'internal-event',
        ),
  


  ) ));
$temp_query = $wp_query;
  $wp_query   = NULL;
  $wp_query   = $forthcomingLoop;
  $current_month = ""; ?>
      <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
      <?php
    include(locate_template('partials/vf-summary-events.php', false, false)); ?>
      <?php endwhile;?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
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
      <nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
        data-items-per-page="20" data-current-page="0" data-name="pagination1">

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


<?php get_footer(); ?>
