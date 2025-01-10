<?php

get_header();

the_post();
$term = get_queried_object(); // Get the current taxonomy term object
$languages = get_field('languages', $term); // Assuming 'languages' is a custom field on the taxonomy term

?>

<section class="embl-grid embl-grid--has-centered-content | vf-content | vf-u-margin__top--800">
  <div>
    <!-- empty -->
  </div>
  <div>
    <h3 class="vf-text vf-text-heading--3 | vf-u-margin__bottom--0">
      EMBL Taxonomy:
    </h3>

    <h1>
      <?php echo esc_html($term->name); ?> <!-- Display term name -->
    </h1>
  </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div>
  </div>
  <div class="vf-form__item">
    <input id="search" class="vf-form__input vf-form__input--filter inputLive" data-jplist-control="textbox-filter"
           data-group="news" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
           placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
           <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
              id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
                id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
                class="counter-highlight"></span></p>
  </div>
  <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
    <span class="vf-button__text">Reset</span>
  </button>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div class="vf-stack vf-stack--400">
    <?php include(locate_template('partials/archive-filter-year.php', false, false)); ?>
  </div>
  <div>
    <div id="allPosts" data-jplist-group="news">
      
      <?php
      $mainPostLoop = new WP_Query(array(
        'post_type' => array('post', 'embletc'), // Adjust post types if necessary
        'post__not_in' => $excluded_translations_array,
        'tax_query' => array(
          array(
            'taxonomy' => 'embl_taxonomy', // Set to your custom taxonomy name
            'field' => 'slug',
            'terms' => $term->slug, // Use term slug to filter posts
          ),
        ),
        'posts_per_page' => -1,
        'suppress_filters' => 0,
      ));
      if ($mainPostLoop->have_posts()) :
        while ($mainPostLoop->have_posts()) : $mainPostLoop->the_post();
          include(locate_template('partials/vf-summary--news-archive.php', false, false));
        endwhile;
      else : ?>
        <!-- no results control -->
        <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="news"
                 data-name="no-results">
          <p class="vf-summary__text">
            No matching posts found
          </p>
        </article>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
  
     <!-- no results control -->
     <article class="vf-summary" data-jplist-control="no-results" data-group="news" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list"></ul>
        </nav>


  </div>
  <div>
  </div>
</section>

<script type="text/javascript">
  jplist.init({deepLinking: true});
</script>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>
<?php  include(locate_template('partials/pagination.php', false, false)); ?>

<?php get_footer(); ?>
