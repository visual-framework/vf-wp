<?php
/**
* Template Name: TREC Events
*/

get_header();

global $vf_theme;
$current_date = date('Ymd');
$vf_theme->the_content();
?>

<div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--500 | vf-content">
  <div></div>
  <div>

    <form action="#eventsFilter" onsubmit="return false;"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
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

<section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
  <div class="vf-stack vf-stack--400">
    <?php include(locate_template('partials/trec-filter.php', false, false)); ?>
  </div>
  <main>
    <div class="vf-tabs">
      <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link vf-u-padding__top--0" href="#vf-tabs__section--upcoming-events">Upcoming</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link vf-u-padding__top--0" href="#vf-tabs__section--past-events">Past</a>
        </li>
      </ul>
    </div>
    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section vf-u-padding__top--800" id="vf-tabs__section--upcoming-events">
        <div id="upcoming-events" data-jplist-group="data-group-1">
          <?php
          $forthcomingLoop = new WP_Query (array( 
          'posts_per_page' => -1,
          'post_type' => 'vf_event',
          'order' => 'ASC', 
          'orderby' => 'meta_value_num',
          'meta_key' => 'vf_event_start_date',
          'meta_query' => array(
            array(
                'key' => 'vf_event_start_date',
                'value' => $current_date,
                'compare' => '>=',
                'type' => 'numeric'
            ),
            array(
              'key' => 'vf_event_start_date',
              'value' => date('Ymd', strtotime('now')),
              'type' => 'numeric',
              'compare' => '>=',
              ) 
            ) ));
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary--trec-event.php', false, false)); ?>
          <?php endwhile;?>
        </div>
      </section>

      <section class="vf-tabs__section vf-u-padding__top--800" id="vf-tabs__section--past-events">
        <div id="past-events" data-jplist-group="data-group-1">
          <?php
          $pastLoop = new WP_Query (array( 
          'posts_per_page' => -1,
          'post_type' => 'vf_event',
          'order' => 'DESC', 
          'orderby' => 'meta_value_num',
          'meta_key' => 'vf_event_start_date',
          'meta_query' => array(
            array(
                'key' => 'vf_event_start_date',
                'value' => $current_date,
                'compare' => '<',
                'type' => 'numeric'
            ),
            array(
              'key' => 'vf_event_start_date',
              'value' => date('Ymd', strtotime('now')),
              'type' => 'numeric',
              'compare' => '<',
              ) 
            ) ));
          $current_month = ""; ?>
          <?php while ($pastLoop->have_posts()) : $pastLoop->the_post();?>
          <?php 
          include(locate_template('partials/vf-summary--trec-event-past.php', false, false)); ?>
          <?php endwhile;?>
        </div>
        <?php // include(locate_template('partials/paging-controls.php', false, false)); ?>
      </section>
    </div>
  </main>
</section>

<script type="text/javascript">
  jplist.init({
  });

</script>
<?php

get_footer();

?>
<script>
  // no results handlers
     if ( $('#upcoming-events').children().length <= 0 ) {
        $('#upcoming-events').append('<p id="no-upcoming" class="vf-text-body vf-text-body--2">No upcoming events found</p>');
      }

      $('#vf-form__select').change(function(){
        if  ( $('#no-upcoming').length ) {
        $( "#no-upcoming" ).remove();
      }
      if ( $('#upcoming-events').children().length <= 0 ) {
        $('#upcoming-events').append('<p id="no-upcoming" class="vf-text-body vf-text-body--2">No upcoming events found</p>');
      }
    });
      $('#vf-form__select').change(function(){
        if  ( $('#no-past').length ) {
        $( "#no-past" ).remove();
      }
      if ( $('#past-events').children().length <= 0 ) {
        $('#past-events').append('<p id="no-past" class="vf-text-body vf-text-body--2">No results found.</p>');
      }
    });
</script>
