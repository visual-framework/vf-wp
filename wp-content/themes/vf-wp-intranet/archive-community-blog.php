<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
  </div>
</section>

<div class="vf-summary-container | vf-u-background-color-ui--off-white | vf-u-fullbleed | vf-u-padding__top--500">
<h2 class="vf-section-header__heading vf-u-margin__bottom--400">Highlights</h2>

  <div class="vf-section-content | vf-u-margin__top--200">
    <div class="vf-tabs">
      <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--6"
            data-vf-js-location-nearest-activation-target="barcelona">Barcelona</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--7"
            data-vf-js-location-nearest-activation-target="grenoble">Grenoble</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--8"
            data-vf-js-location-nearest-activation-target="hamburg">Hamburg</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--9"
            data-vf-js-location-nearest-activation-target="default">Heidelberg</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--10"
            data-vf-js-location-nearest-activation-target="rome">Rome</a>
        </li>
      </ul>
    </div>

    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section" id="vf-tabs__section--6">
        <div class="vf-grid | vf-grid__col-3">
          <?php
    $communityBlogLoop_1 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('barcelona', 'all'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_1->have_posts()) : $communityBlogLoop_1->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--7">
        <div class="vf-grid | vf-grid__col-3">
          <?php
    $communityBlogLoop_2 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('grenoble', 'all'),
        ) ) )); 
    $ids = array();
    while ($communityBlogLoop_2->have_posts()) : $communityBlogLoop_2->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--8">
        <div class="vf-grid | vf-grid__col-3">
          <?php
    $communityBlogLoop_3 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('hamburg', 'all'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_3->have_posts()) : $communityBlogLoop_3->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--9">
        <div class="vf-grid | vf-grid__col-3">
          <?php
    $communityBlogLoop_4 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('heidelberg', 'all'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_4->have_posts()) : $communityBlogLoop_4->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--10">
        <div class="vf-grid | vf-grid__col-3">
          <?php
    $communityBlogLoop_5 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('rome', 'all'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_5->have_posts()) : $communityBlogLoop_5->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
    </div>
  </div>
</div>


<section
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div>
  </div>
  <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Filter by update title" data-clear-btn-id="name-clear-btn">
   </div>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/topic-updates-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-community-blog-featured.php', false, false)); 
          }
        }  ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching posts found
        </p>
      </article>
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
      data-items-per-page="50" data-current-page="0" data-name="pagination1">
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
  <article class="vf-card vf-card--brand vf-card--bordered">
<div class="vf-card__content | vf-stack vf-stack--400">
  <h3 class="vf-card__heading"><a class="vf-card__link" href="https://www.embl.org/internal-information/news/">Internal news<svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
      </svg>
    </a></h3>
</div>
</article>
  </div>
</section>

<style>
  .vf-form__label {
    font-size: 14px;
  }
  .vf-form__legend {
    font-size: 19px;
  }
  .vf-form__checkbox+.vf-form__label::before  {
    position: unset;
  }
</style>

<script type="text/javascript">
  jplist.init({deepLinking: true});
</script>

<?php

get_footer();

?>