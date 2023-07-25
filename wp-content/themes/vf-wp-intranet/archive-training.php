<?php

get_header();

global $vf_theme;
$today_date = date('Ymd');
?>

<section class="vf-intro | vf-u-margin__bottom--800">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Training catalogue
    </h1>
    <p class="vf-intro__text">Short description and invitation to browse
</p>
  </div>
</section>

<div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--500 | vf-content">
  <div></div>
  <div>

    <form action="#eventsFilter" onsubmit="return false;"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".search-data" type="text" value=""
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
    <?php include(locate_template('partials/training-filter.php', false, false)); ?>
  </div>
  <main>
        <div id="upcoming-events" data-jplist-group="data-group-1">
          <?php
          $forthcomingLoop = new WP_Query (array( 
          'posts_per_page' => -1,
          'post_type' => 'training',
          'order' => 'ASC', 
          'orderby' => 'meta_value_num',
          'meta_key' => 'vf-wp-training-start_date',
          'meta_query' => array(
            array(
                'key' => 'vf-wp-training-start_date',
                'value' => $today_date,
                'compare' => '>=',
                'type' => 'numeric'
            ),
            array(
              'key' => 'vf-wp-training-start_date',
              'value' => date('Ymd', strtotime('now')),
              'type' => 'numeric',
              'compare' => '>=',
              ) 
            ) ));
          $current_month = ""; ?>
          <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post();?>
          <?php
         include(locate_template('partials/vf-summary--training.php', false, false)); ?>
          <?php endwhile;?>
    </div>
  </main>
  <div class="vf-stack vf-stack--400">
  <article class="vf-card vf-card--brand vf-card--bordered">

<div class="vf-card__content | vf-stack vf-stack--400"><h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">Example link       <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path></svg>
</a></h3>
<p class="vf-card__text">Sapiente harum, omnis provident</p>
</div>
</article>
  <article class="vf-card vf-card--brand vf-card--bordered">


<div class="vf-card__content | vf-stack vf-stack--400"><h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">Example link       <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path></svg>
</a></h3>
<p class="vf-card__text">Sapiente harum, omnis provident</p>
</div>
</article>
  </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script type="text/javascript">
  jplist.init({
  });

</script>

<script>
  // no results handlers
  $('#checkbox-filter-organiser, #checkbox-filter-location').change(function () {
    if ($('#no-upcoming').length) {
      $("#no-upcoming").remove();
    }
    if ($('#upcoming-events').children().length <= 0) {
      $('#upcoming-events').append(
      '<p id="no-upcoming" class="vf-text-body vf-text-body--2">No results found.</p>');
    }
  });

  $('#checkbox-filter-organiser, #checkbox-filter-location').change(function () {
    if ($('#no-past').length) {
      $("#no-past").remove();
    }
    if ($('#past-events').children().length <= 0) {
      $('#past-events').append('<p id="no-past" class="vf-text-body vf-text-body--2">No results found.</p>');
    }
  });
</script>

<?php

get_footer();

?>
