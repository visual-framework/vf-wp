<?php
/**
* Template Name: TREC Events
*/

get_header();

global $vf_theme;

?>

<?php
$vf_theme->the_content();
?>

<div
  class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--500 | vf-content">
  <div></div>
  <div>

  <form action="#eventsFilter" onsubmit="return false;"
    class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
    <div class="vf-sidebar__inner">
    <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="events" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
        </div>
        <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
          <span class="vf-button__text">Reset</span>
        </button>
    </div>
  </form>
  </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div class="vf-stack vf-stack--400">
  <?php include(locate_template('partials/trec-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="events">
      
    <?php 

$mainPostLoop = new WP_Query (array(
  'post_type' => 'vf_event',       
    ));
while ($mainPostLoop->have_posts()) : $mainPostLoop->the_post(); ?>
<?php include(locate_template('partials/vf-summary--trec-event.php', false, false)); ?>
<?php endwhile;?>
<?php wp_reset_postdata(); ?>

      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="events"
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

<!--/vf-grid-->

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>

<?php

get_footer();

?>
