<?php

/**
* Template Name: Seminars
*/

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
          data-name="my-filter-1" data-path=".vf-summary__title" data-id="search" type="text" value=""
          placeholder="Filter by seminar title" data-clear-btn-id="name-clear-btn"
          class="vf-form__input | vf-search__input" />
      </div>
      <button href="#eventsFilter" class="vf-search__button | vf-button vf-button--primary">
        <span class="vf-button__text">Filter</span>
      </button>
    </div>
  </form>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/seminars-filter.php', false, false)); ?>
  </div>
  <div>
    <div class="vf-content">
    <div class="embl-content-hub-loader | vf-grid vf-grid__col-1" data-jplist-group="data-group-1">
        <?php
        $request = wp_remote_get( 'https://www.embl.org/api/v1/events?_format=json&source=contenthub&field_event_type=Seminar&start_date=today
        ' );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = wp_remote_retrieve_body( $request );
        $data = json_decode( $body );
        // excludes Other - EBI seminars
        foreach ($data as $key => $item) {
            if($item->field_event_location == 'Other') {
                unset($data[$key]);
            }
        }      
        function sortByStartDate($param1, $param2) {
            return strcmp($param1->field_event_start_date_time, $param2->field_event_start_date_time);
        }
        usort($data, "sortByStartDate");

        if( ! empty( $data ) ) {
            foreach( $data as $event ) {
              $newDate = date("j M Y, H:i", strtotime($event->field_event_start_date_time));
              $info = $event->field_event_additional_info;  
            
                echo '<article class="vf-summary vf-summary--event" data-jplist-item>';

                //Date
                echo '<p class="vf-summary__date">' . $newDate . '&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;"><a href="https://seminarlist.embl.de/rest/calendar?seminarID=' . substr($event->field_event_unique_identifier, strpos($event->field_event_unique_identifier, "-") + 1) . '&origin=intranet.embl.de">Add to calendar</a></span></p>';

                //Title
                echo '<h3 class="vf-summary__title">';

                //Link
                if (!empty($event->field_event_more_information)) {
                    echo '<a href="'. $event->field_event_more_information . '" class="vf-summary__link">' . $event->title . '</a></h3>' ;
                }
                else {
                     echo ($event->title . '</h3>'); }

                // additional info field break down
                echo '<p class="vf-summary__text" style="font-size: 16px;">'; 
                 // show only speaker
                $speaker = substr($info, strpos($info, 'Speaker:'));
                $venue = $event->field_event_venue;
                $venue = str_replace('<br />', '', $venue );
                $venue = str_replace('\n', ', ', $venue );
                echo '<span>' . strstr($speaker, '<br />', true) . ', ' . $venue . '</span><br>';
                 // show only host
                if (strpos($info, 'Host') !== false) {     
                $host = substr($info, strpos($info, 'Host'));
                echo '<span>' . strstr($host, 'Location', true);'</span><br>'; }
                 // show location
                $newinfo = str_replace('Location', 'Place', $info); 
                echo '<span>' . substr($newinfo, strpos($newinfo, 'Place'));'</span></p>';

                // Seminar type
                echo '<p class="vf-summary__text | vf-text-heading--5 | type">' . strstr($info, '<', true) . '</p>';

                // Location
                echo '<p class="vf-summary__location | location">' . $event->field_event_location . '</p>';

                // Abstract
                if (!empty($event->field_event_summary)) {
                echo '<details class="vf-details | vf-u-padding__left--0" close>
                <summary class="vf-details--summary" style="font-size: 16px;">Show abstract</summary>
                ' . $event->field_event_summary . '</details>';
                }
                echo '</article>';
            }
        }
        ?>
  </div>

      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching seminars found
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
      data-items-per-page="25" data-current-page="0" data-name="pagination1">
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
  <div></div>
</section>

<script type="text/javascript">
  jplist.init();
</script>

<?php

get_footer();

?>
