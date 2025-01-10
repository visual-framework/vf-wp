<?php

get_header();

the_post();
$tag = get_queried_object();
$languages = get_field('languages');

?>

<section class="embl-grid embl-grid--has-centered-content | vf-content | vf-u-margin__top--800">
  <div>
    <!-- empty -->
  </div>
  <div>
  <h3 class="vf-text vf-text-heading--3 | vf-u-margin__bottom--0">
      Tag:
    </h3>

    <h1>
      <?php echo $tag->name; ?>
    </h1>
  </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div>
  </div>
  <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="news" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
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
    <div data-jplist-group="news">
      
    <?php
    $mainPostLoop = new WP_Query (array(
      'post_type' => array('post', 'embletc'),
      'post__not_in' => $excluded_translations_array,        
      'tag' => $tag->slug,
      'posts_per_page' => -1,
      'suppress_filters' => 0,  ));
    while ($mainPostLoop->have_posts()) : $mainPostLoop->the_post(); ?>
    <?php include(locate_template('partials/vf-summary--news-archive.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="news"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching posts found
        </p>
      </article>
    </div>

    <?php include(locate_template('partials/paging-controls.php', false, false)); ?>

  </div>
  <div>
  </div>
</section>


<script type="text/javascript">
  jplist.init({deepLinking: true});
</script>



<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
