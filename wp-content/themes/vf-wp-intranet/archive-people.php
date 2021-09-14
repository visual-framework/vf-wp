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
  </div>
</section>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <form action="#eventsFilter" onsubmit="return false;"
    class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item">
        <label class="vf-form__label vf-u-sr-only | vf-search__label" for="textbox-filter">Search</label>
        <input id="textbox-filter" data-jplist-control="textbox-filter" data-group="data-group-1"
          data-name="my-filter-1" data-path=".people-search" data-id="search" type="text" value=""
          placeholder="Filter by person name or job title" data-clear-btn-id="name-clear-btn"
          class="vf-form__input | vf-search__input" />
      </div>
      <button href="#eventsFilter" class="vf-search__button | vf-button vf-button--primary">
        <span class="vf-button__text">Filter</span>
      </button>
    </div>
  </form>
</div>

<section class="embl-grid">
  <div>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-profile.php', false, false)); 
          }
        } ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching people found
        </p>
      </article>
    </div>

  </div>
</section>

<script type="text/javascript">
  jplist.init();
</script>

<?php

get_footer();

?>
