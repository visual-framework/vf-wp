<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-content | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div>
    <h3 class="vf-text vf-text-heading--3 | vf-u-margin__bottom--0">
      Events / Topic:
    </h3>
    <h1>
      <?php echo single_tag_title(); ?>
    </h1>
  </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div>
  </div>
  <div class="vf-form__item">
    <input id="search" class="vf-form__input vf-form__input--filter | inputLive" data-jplist-control="textbox-filter"
      data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
      placeholder="Filter by news title" data-clear-btn-id="name-clear-btn">
      <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
              id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
                id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
                class="counter-highlight"></span></p>
  </div>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/topic-filter.php', false, false)); ?>
  </div>
  <div>
    <div id="allPosts" data-jplist-group="data-group-1">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary-topic.php', false, false)); 
          }
        }  ?>
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
  <div>

    <div class="vf-u-background-color-ui--off-white">
      <div class="vf-content vf-u-padding--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
        href="https://www.embl.org/internal-information/events">Upcoming events</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0"><a
        href="https://www.embl.org/internal-information/past-events">Past events</a></p>
        
      </div>
    </article>
    
  </div>
  </div>
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
