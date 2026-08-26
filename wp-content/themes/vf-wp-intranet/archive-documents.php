<?php

get_header();
?>


<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
    <p class="vf-lede">The repository holds the digital copies of internal EMBL documents, reports, brochures
      and various other publications.</p>
  </div>
</section>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
    <input id="search" class="vf-form__input vf-form__input--filter inputLive" data-jplist-control="textbox-filter"
      data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
      placeholder="Filter by document title" data-clear-btn-id="name-clear-btn">
      <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
              id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
                id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
                class="counter-highlight"></span></p>
  </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>
  </div>
  <div>
    <div id="allPosts" data-jplist-group="data-group-1">
      <?php
  		$mainQuery = new WP_Query (array(
        'posts_per_page' => -1, 
        'post_type' => 'documents', 
        ));
    while ($mainQuery->have_posts()) : $mainQuery->the_post(); ?>
      <?php include(locate_template('partials/vf-summary--document.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
            <!-- no results control -->
            <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list"></ul>
        </nav>

  </div>
  <div></div>
</section>

<script type="text/javascript">
    jplist.init({
      deepLinking: true
    });
</script>
<?php  include(locate_template('partials/pagination/pagination.php', false, false)); ?>

<?php

get_footer();

?>
